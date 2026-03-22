<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Stylist;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Vista principal con estadísticas generales.
     * La tabla filtrable se delega al componente Livewire.
     */
    public function index()
    {
        $now   = now();
        $today = today();

        // ── Estadísticas generales ──
        // Se agrupan en una sola consulta eficiente por estado
        // para las citas de hoy, evitando múltiples queries.
        $todayByStatus = Appointment::whereDate('date', $today)
            ->selectRaw("status, COUNT(*) as total")
            ->groupBy('status')
            ->pluck('total', 'status');

        $todayTotal   = $todayByStatus->sum();
        $todayPending = $todayByStatus->get('pending', 0) + $todayByStatus->get('confirmed', 0);

        // Citas de la semana actual (lunes a sábado)
        $weekCount = Appointment::whereBetween('date', [
            $now->copy()->startOfWeek(Carbon::MONDAY),
            $now->copy()->endOfWeek(Carbon::SATURDAY),
        ])->count();

        // Estadísticas del mes: completadas vs canceladas
        $monthByStatus = Appointment::whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->selectRaw("status, COUNT(*) as total")
            ->groupBy('status')
            ->pluck('total', 'status');

        $monthCompleted = $monthByStatus->get('completed', 0);
        $monthCancelled = $monthByStatus->get('cancelled', 0);
        $monthNoShow    = $monthByStatus->get('no_show', 0);

        // Tasa de asistencia del mes (completadas / finalizadas)
        $monthFinished    = $monthCompleted + $monthCancelled + $monthNoShow;
        $attendanceRate   = $monthFinished > 0
            ? round(($monthCompleted / $monthFinished) * 100)
            : 0;

        // Total de pendientes globales (requieren atención)
        $pendingTotal = Appointment::where('status', 'pending')->count();

        $stats = [
            'today_total'     => $todayTotal,
            'today_pending'   => $todayPending,
            'week_count'      => $weekCount,
            'month_completed' => $monthCompleted,
            'month_cancelled' => $monthCancelled,
            'attendance_rate' => $attendanceRate,
            'pending_total'   => $pendingTotal,
        ];

        return view('admin.appointments.index', compact('stats'));
    }

    /**
     * Detalle completo de una cita.
     */
    public function show(Appointment $appointment)
    {
        $appointment->load(['client', 'stylist.user', 'service', 'reservation']);

        return view('admin.appointments.show', compact('appointment'));
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
        if ($appointment->status !== 'pending') {
            return back()->with('swal', [
                'icon'  => 'error',
                'title' => 'Acción no permitida',
                'text'  => 'Solo las citas pendientes se pueden confirmar.',
            ]);
        }

        $appointment->confirm();

        return back()->with('swal', [
            'icon'  => 'success',
            'title' => '¡Cita confirmada!',
            'text'  => 'La cita ha sido confirmada correctamente.',
        ]);
    }

    /**
     * Marcar una cita como completada.
     * Transición: confirmed → completed
     */
    public function complete(Appointment $appointment)
    {
        if ($appointment->status !== 'confirmed') {
            return back()->with('swal', [
                'icon'  => 'error',
                'title' => 'Acción no permitida',
                'text'  => 'Solo las citas confirmadas se pueden completar.',
            ]);
        }

        $appointment->markAsCompleted();

        return back()->with('swal', [
            'icon'  => 'success',
            'title' => '¡Cita completada!',
            'text'  => 'La cita se ha marcado como terminada.',
        ]);
    }

    /**
     * Cancelar una cita (como administrador).
     */
    public function cancel(Request $request, Appointment $appointment)
    {
        if (in_array($appointment->status, ['completed', 'cancelled', 'no_show'])) {
            return back()->with('swal', [
                'icon'  => 'error',
                'title' => 'Acción no permitida',
                'text'  => 'Esta cita ya no se puede cancelar.',
            ]);
        }

        $appointment->update([
            'status'              => 'cancelled',
            'cancellation_reason' => $request->input('reason', 'Cancelada por administrador'),
            'cancelled_by'        => 'admin',
            'cancelled_at'        => now(),
        ]);

        return back()->with('swal', [
            'icon'  => 'success',
            'title' => 'Cita cancelada',
            'text'  => 'La cita ha sido cancelada correctamente.',
        ]);
    }

    /**
     * Marcar como no asistió.
     * Transición: pending|confirmed → no_show
     */
    public function noShow(Appointment $appointment)
    {
        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return back()->with('swal', [
                'icon'  => 'error',
                'title' => 'Acción no permitida',
                'text'  => 'Esta cita ya no se puede modificar.',
            ]);
        }

        $appointment->markAsNoShow();

        return back()->with('swal', [
            'icon'  => 'info',
            'title' => 'Cita marcada',
            'text'  => 'Se registró que el cliente no asistió.',
        ]);
    }
}
