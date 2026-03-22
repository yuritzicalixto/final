<?php

namespace App\Http\Controllers\Client;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Stylist;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Notifications\AppointmentConfirmation;

class AppointmentController extends Controller
{
    // =====================================================
    // CONSTANTES DE NEGOCIO
    // =====================================================
    const OPEN_HOUR  = 10; // 10:00 AM
    const CLOSE_HOUR = 17; // 5:00 PM (última hora de inicio posible)
    const SLOT_INTERVAL = 30; // Intervalos de 30 minutos

    /**
     * Listado de citas del cliente autenticado.
     */
    public function index()
    {
        $appointments = Appointment::where('client_id', Auth::id())
            ->with(['service', 'stylist.user'])
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        return view('client.appointments.index', compact('appointments'));
    }

    /**
     * Formulario para agendar una nueva cita.
     * Recibe opcionalmente ?service_id=X desde el frontend de servicios.
     */
    public function create(Request $request)
    {
        // Si viene un service_id desde el frontend, precargamos ese servicio
        $selectedService = null;
        if ($request->has('service_id')) {
            $selectedService = Service::active()->find($request->service_id);

            if (!$selectedService) {
                return redirect()
                    ->route('client.appointments.create')
                    ->with('error', 'El servicio seleccionado no está disponible.');
            }
        }

        // Cargar todos los servicios activos agrupados por categoría
        $services = Service::active()
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        return view('client.appointments.create', compact(
            'services',
            'selectedService'
        ));
    }

    /**
     * Guardar la cita con toda la lógica de validación.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'stylist_id' => 'required|exists:stylists,id',
            'date'       => 'required|date|after:today',
            'start_time' => 'required|date_format:H:i',
            'notes'      => 'nullable|string|max:500',
        ], [
            'service_id.required' => 'Selecciona un servicio.',
            'stylist_id.required' => 'Selecciona un estilista.',
            'date.required'       => 'Selecciona una fecha.',
            'date.after'          => 'La fecha debe ser a partir de mañana.',
            'start_time.required' => 'Selecciona un horario.',
        ]);

        // 1. Validar que la fecha sea de Lunes a Sábado (1-6 en Carbon)
        $date = Carbon::parse($validated['date']);
        if ($date->isSunday()) {
            return back()->withErrors(['date' => 'No atendemos los domingos. Selecciona de lunes a sábado.'])->withInput();
        }

        // 2. Validar horario de atención (10:00 - 17:00)
        $startTime = Carbon::createFromFormat('H:i', $validated['start_time']);
        if ($startTime->hour < self::OPEN_HOUR || $startTime->hour >= self::CLOSE_HOUR) {
            return back()->withErrors(['start_time' => 'El horario de atención es de 10:00 AM a 5:00 PM.'])->withInput();
        }

        // 3. Obtener el servicio y calcular hora de fin
        $service = Service::findOrFail($validated['service_id']);
        $endTime = $startTime->copy()->addMinutes($service->duration);

        // 4. Validar que el servicio termine antes de la hora de cierre (17:00)
        $closeTime = Carbon::createFromTime(self::CLOSE_HOUR, 0);
        if ($endTime->gt($closeTime)) {
            return back()->withErrors(['start_time' => 'El servicio terminaría después de las 5:00 PM. Elige un horario más temprano.'])->withInput();
        }

        // 5. Verificar que el estilista ofrece ese servicio
        $stylist = Stylist::findOrFail($validated['stylist_id']);
        if (!$stylist->services()->where('services.id', $service->id)->exists()) {
            return back()->withErrors(['stylist_id' => 'Este estilista no realiza el servicio seleccionado.'])->withInput();
        }

        // 6. Verificar disponibilidad del estilista
        if (!$stylist->isAvailableAt($validated['date'], $validated['start_time'], $endTime->format('H:i'))) {
            return back()->withErrors(['start_time' => 'El estilista no está disponible en ese horario. Intenta con otro horario.'])->withInput();
        }

        // 7. Verificar que el CLIENTE no tenga otra cita que se traslape
        //    Un cliente no se puede clonar: no puede estar en dos servicios al mismo tiempo.
        $clientHasConflict = Appointment::where('client_id', Auth::id())
            ->where('date', $validated['date'])
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($validated, $endTime) {
                $query->whereBetween('start_time', [$validated['start_time'], $endTime->format('H:i')])
                      ->orWhereBetween('end_time', [$validated['start_time'], $endTime->format('H:i')])
                      ->orWhere(function ($q) use ($validated, $endTime) {
                          $q->where('start_time', '<=', $validated['start_time'])
                            ->where('end_time', '>=', $endTime->format('H:i'));
                      });
            })
            ->exists();

        if ($clientHasConflict) {
            return back()->withErrors(['start_time' => 'Ya tienes otra cita agendada en ese horario. Elige un horario diferente.'])->withInput();
        }

        // 8. Crear la cita
        $appointment = Appointment::create([
            'client_id'  => Auth::id(),
            'service_id' => $validated['service_id'],
            'stylist_id' => $validated['stylist_id'],
            'date'       => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time'   => $endTime->format('H:i'),
            'status'     => 'pending',
            'notes'      => $validated['notes'] ?? null,
        ]);

        // 9. Enviar notificación push de confirmación
        if (Auth::user()->pushSubscriptions()->exists()) {
            $appointment->load('service');
            Auth::user()->notify(new AppointmentConfirmation($appointment));
        }


        return redirect()
            ->route('client.appointments.index')
            ->with('success', '¡Cita agendada exitosamente! Te confirmaremos pronto.');
    }

    /**
     * Ver detalle de una cita.
     */
    public function show(Appointment $appointment)
    {
        if ($appointment->client_id !== Auth::id()) {
            abort(403);
        }

        $appointment->load(['service', 'stylist.user']);

        return view('client.appointments.show', compact('appointment'));
    }

    // =====================================================
    // ENDPOINTS AJAX — Selección dinámica
    // =====================================================

    /**
     * Obtener estilistas disponibles para un servicio.
     * GET /client/appointments/stylists-by-service/{service}
     */
    public function stylistsByService(Service $service)
    {
        // Estilistas activos que ofrecen este servicio
        $stylists = $service->stylists()
            ->where('status', 'available')
            ->with('user')
            ->get()
            ->map(function ($stylist) {
                return [
                    'id'          => $stylist->id,
                    'name'        => $stylist->user->name ?? 'Estilista #' . $stylist->id,
                    'specialties' => $stylist->specialties,
                    'photo'       => $stylist->photo ? asset('storage/' . $stylist->photo) : null,
                ];
            });

        return response()->json(['stylists' => $stylists]);
    }

    /**
     * Obtener horarios disponibles para un estilista en una fecha.
     * GET /client/appointments/available-slots?stylist_id=X&date=Y&service_id=Z
     */
    public function availableSlots(Request $request)
    {
        $request->validate([
            'stylist_id' => 'required|exists:stylists,id',
            'date'       => 'required|date|after:today',
            'service_id' => 'required|exists:services,id',
        ]);

        $stylist = Stylist::findOrFail($request->stylist_id);
        $service = Service::findOrFail($request->service_id);
        $date    = $request->date;

        // Validar que no sea domingo
        if (Carbon::parse($date)->isSunday()) {
            return response()->json(['slots' => [], 'message' => 'No atendemos los domingos.']);
        }

        // Generar todos los slots posibles en intervalos de 30 min
        $slots = [];
        $openTime  = Carbon::createFromTime(self::OPEN_HOUR, 0);
        $closeTime = Carbon::createFromTime(self::CLOSE_HOUR, 0);

        // Obtener las citas existentes del CLIENTE en esa fecha
        // para evitar que agende dos citas al mismo tiempo
        $clientAppointments = Appointment::where('client_id', Auth::id())
            ->where('date', $date)
            ->where('status', '!=', 'cancelled')
            ->get(['start_time', 'end_time']);

        $current = $openTime->copy();
        while ($current->lt($closeTime)) {
            $slotEnd = $current->copy()->addMinutes($service->duration);

            // Solo si el servicio termina antes o exacto a las 17:00
            if ($slotEnd->lte($closeTime)) {
                // Verificar disponibilidad del ESTILISTA
                $stylistAvailable = $stylist->isAvailableAt(
                    $date,
                    $current->format('H:i'),
                    $slotEnd->format('H:i')
                );

                // Verificar que el CLIENTE no tenga otra cita que se traslape
                $clientAvailable = !$clientAppointments->contains(function ($appt) use ($current, $slotEnd) {
                    $apptStart = Carbon::parse($appt->start_time);
                    $apptEnd   = Carbon::parse($appt->end_time);
                    $slotStart = $current->copy();
                    $slotEndCopy = $slotEnd->copy();

                    // Hay traslape si: el slot empieza antes de que termine la cita
                    // Y el slot termina después de que empiece la cita
                    return $slotStart->lt($apptEnd) && $slotEndCopy->gt($apptStart);
                });

                $slots[] = [
                    'time'      => $current->format('H:i'),
                    'label'     => $current->format('g:i A') . ' - ' . $slotEnd->format('g:i A'),
                    'available' => $stylistAvailable && $clientAvailable,
                ];
            }

            $current->addMinutes(self::SLOT_INTERVAL);
        }

        return response()->json(['slots' => $slots]);
    }
}
