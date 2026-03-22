<?php

namespace App\Http\Controllers\Stylist;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Stylist;
use App\Notifications\AppointmentConfirmed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Panel principal de citas del estilista.
     * Muestra citas filtradas por día, semana o mes.
     */
    public function index(Request $request)
    {
        $stylist = Stylist::where('user_id', Auth::id())->first();

        if (!$stylist) {
            return view('stylist.appointments.index', [
                'appointments'  => collect(),
                'appointmentsByDate' => collect(),
                'weekDays'      => [],
                'currentView'   => 'week',
                'referenceDate' => today(),
                'periodLabel'   => '',
                'stats'         => ['total' => 0, 'pending' => 0, 'confirmed' => 0, 'completed' => 0],
            ]);
        }

        $currentView   = $request->get('view', 'week');
        $referenceDate = $request->get('date') ? Carbon::parse($request->get('date')) : today();

        switch ($currentView) {
            case 'day':
                $startDate = $referenceDate->copy()->startOfDay();
                $endDate   = $referenceDate->copy()->endOfDay();
                $periodLabel = $referenceDate->translatedFormat('l, d \d\e F Y');
                break;
            case 'month':
                $startDate = $referenceDate->copy()->startOfMonth();
                $endDate   = $referenceDate->copy()->endOfMonth();
                $periodLabel = $referenceDate->translatedFormat('F Y');
                break;
            case 'week':
            default:
                $currentView = 'week';
                $startDate = $referenceDate->copy()->startOfWeek(Carbon::MONDAY);
                $endDate   = $referenceDate->copy()->endOfWeek(Carbon::SATURDAY);
                $periodLabel = $startDate->format('d M') . ' — ' . $endDate->format('d M, Y');
                break;
        }

        $appointments = Appointment::where('stylist_id', $stylist->id)
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->with(['service', 'client'])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $stats = [
            'total'     => $appointments->count(),
            'pending'   => $appointments->where('status', 'pending')->count(),
            'confirmed' => $appointments->where('status', 'confirmed')->count(),
            'completed' => $appointments->where('status', 'completed')->count(),
        ];

        $appointmentsByDate = $appointments->groupBy(fn ($a) => $a->date->format('Y-m-d'));

        $weekDays = [];
        if ($currentView === 'week') {
            $day = $startDate->copy();
            while ($day->lte($endDate)) {
                $weekDays[] = $day->copy();
                $day->addDay();
            }
        }

        return view('stylist.appointments.index', compact(
            'appointments', 'appointmentsByDate', 'weekDays',
            'currentView', 'referenceDate', 'periodLabel', 'stats'
        ));
    }

    // =====================================================
    // ACCIONES DE CAMBIO DE ESTADO
    // =====================================================

    /**
     * Confirmar una cita pendiente.
     * Transición: pending → confirmed
     */
    public function confirm(Appointment $appointment)
    {
        // Verificar que la cita pertenece al estilista autenticado
        $this->authorizeStylistOwnership($appointment);

        if ($appointment->status !== 'pending') {
            return back()->with('swal', [
                'icon'  => 'error',
                'title' => 'No se puede confirmar',
                'text'  => 'Solo las citas pendientes se pueden confirmar.',
            ]);
        }

        $appointment->update(['status' => 'confirmed']);

        // Enviar notificación push al cliente
        $client = $appointment->client;
        if ($client->pushSubscriptions()->exists()) {
            $appointment->load(['service', 'stylist.user']);
            $client->notify(new AppointmentConfirmed($appointment));
        }

        return back()->with('swal', [
            'icon'  => 'success',
            'title' => '¡Cita confirmada!',
            'text'  => 'El cliente recibirá una notificación.',
        ]);
    }

    /**
     * Marcar una cita como completada.
     * Transición: confirmed → completed
     */
    public function complete(Appointment $appointment)
    {
        $this->authorizeStylistOwnership($appointment);

        if ($appointment->status !== 'confirmed') {
            return back()->with('swal', [
                'icon'  => 'error',
                'title' => 'No se puede completar',
                'text'  => 'Solo las citas confirmadas se pueden marcar como completadas.',
            ]);
        }

        $appointment->update(['status' => 'completed']);

        return back()->with('swal', [
            'icon'  => 'success',
            'title' => '¡Cita completada!',
            'text'  => 'La cita ha sido marcada como terminada.',
        ]);
    }

    /**
     * Marcar como no asistió.
     * Transición: pending|confirmed → no_show
     */
    public function noShow(Appointment $appointment)
    {
        $this->authorizeStylistOwnership($appointment);

        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return back()->with('swal', [
                'icon'  => 'error',
                'title' => 'Acción no permitida',
                'text'  => 'Esta cita ya no se puede modificar.',
            ]);
        }

        $appointment->update(['status' => 'no_show']);

        return back()->with('swal', [
            'icon'  => 'info',
            'title' => 'Cita marcada',
            'text'  => 'Se registró que el cliente no asistió.',
        ]);
    }

    /**
     * Verificar que la cita pertenece al estilista autenticado.
     */
    private function authorizeStylistOwnership(Appointment $appointment): void
    {
        $stylist = Stylist::where('user_id', Auth::id())->firstOrFail();

        if ($appointment->stylist_id !== $stylist->id) {
            abort(403, 'Esta cita no te pertenece.');
        }
    }
}
