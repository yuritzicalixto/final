<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Listado de todos los apartados para el admin.
     */
    public function index(Request $request)
    {
        $query = Reservation::with(['client', 'items.product']);

        // Filtrar por estado
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Búsqueda por número de apartado o nombre de cliente
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reservation_number', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Ordenamiento: activos primero, luego por fecha de vencimiento
        $query->orderByRaw("FIELD(status, 'active', 'completed', 'expired', 'cancelled')")
              ->orderBy('expiration_date', 'asc');

        $reservations = $query->paginate(15)->withQueryString();

        // Estadísticas para el dashboard
        $stats = [
            'active'     => Reservation::where('status', 'active')->count(),
            'expiring'   => Reservation::expiringSoon()->count(),
            'completed'  => Reservation::where('status', 'completed')->count(),
            'cancelled'  => Reservation::where('status', 'cancelled')->count(),
            'expired'    => Reservation::where('status', 'expired')->count(),
        ];

        return view('admin.reservations.index', compact('reservations', 'stats'));
    }

    /**
     * Ver detalle completo de un apartado.
     */
    public function show(Reservation $reservation)
    {
        $reservation->load(['client', 'items.product']);

        return view('admin.reservations.show', compact('reservation'));
    }

    /**
     * Marcar un apartado como completado (entregado al cliente).
     */
    public function markAsCompleted(Reservation $reservation)
    {
        if ($reservation->status !== 'active') {
            return back()->with('error', 'Solo se pueden completar apartados activos.');
        }

        $reservation->markAsCompleted();

        return back()->with('success', "Apartado #{$reservation->reservation_number} marcado como entregado.");
    }

    /**
     * Cancelar un apartado desde el panel admin.
     */
    public function cancel(Reservation $reservation)
    {
        if ($reservation->status !== 'active') {
            return back()->with('error', 'Solo se pueden cancelar apartados activos.');
        }

        $reservation->cancel();

        return back()->with('success', "Apartado #{$reservation->reservation_number} cancelado. Stock restaurado.");
    }

    /**
     * Expirar todos los apartados vencidos manualmente (botón de admin).
     * Complementa al comando artisan automático.
     */
    public function expireAll()
    {
        $expired = Reservation::expired()->with('items.product')->get();

        $count = 0;
        foreach ($expired as $reservation) {
            $reservation->expire();
            $count++;
        }

        return back()->with('success', "Se expiraron {$count} apartado(s) vencido(s). Stock restaurado.");
    }
}
