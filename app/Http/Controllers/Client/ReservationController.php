<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Reservation;
use App\Models\ReservationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Notifications\ReservationConfirmation;

class ReservationController extends Controller
{
    /**
     * Listado de apartados del cliente autenticado.
     */
    public function index()
    {
        // Apartados activos primero, luego el historial
        $activeReservations = Reservation::where('client_id', Auth::id())
            ->where('status', 'active')
            ->with('items.product')
            ->orderBy('expiration_date', 'asc')
            ->get();

        $historyReservations = Reservation::where('client_id', Auth::id())
            ->whereIn('status', ['completed', 'cancelled', 'expired'])
            ->with('items.product')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('client.reservations.index', compact('activeReservations', 'historyReservations'));
    }

    /**
     * Vista para confirmar el apartado.
     * Los productos vienen desde localStorage (el JS los lee y los muestra).
     */
    public function create()
    {
        return view('client.reservations.create');
    }

    /**
     * Guardar el apartado en la base de datos.
     * Usa lockForUpdate() para evitar condiciones de carrera en el stock.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items'                 => 'required|array|min:1|max:5',
            'items.*.product_id'    => 'required|exists:products,id',
            'items.*.quantity'      => 'required|integer|min:1|max:10',
            'preferred_pickup_date' => 'nullable|date|after:today',
        ], [
            'items.required'    => 'Debes agregar al menos un producto.',
            'items.max'         => 'Máximo 5 productos por apartado.',
        ]);

        // Validar horario de negocio si se eligió fecha de recolección
        if (!empty($validated['preferred_pickup_date'])) {
            $pickupDate = Carbon::parse($validated['preferred_pickup_date']);

            // Verificar que no sea domingo
            if ($pickupDate->dayOfWeekIso === 7) {
                return back()->withErrors(['preferred_pickup_date' => 'No abrimos los domingos. Elige otro día (Lunes a Sábado).'])->withInput();
            }

            // Verificar que esté dentro de los 7 días de vigencia
            $maxDate = now()->addDays(7);
            if ($pickupDate->gt($maxDate)) {
                return back()->withErrors(['preferred_pickup_date' => 'La fecha debe ser dentro de los próximos 7 días.'])->withInput();
            }
        }

        // Transacción con lock pesimista para proteger el stock
        $reservation = DB::transaction(function () use ($validated) {

            $reservation = Reservation::create([
                'client_id'             => Auth::id(),
                'total'                 => 0,
                'status'                => 'active',
                'preferred_pickup_date' => $validated['preferred_pickup_date'] ?? null,
            ]);

            $total = 0;

            foreach ($validated['items'] as $itemData) {
                // ← lockForUpdate() evita que dos clientes compren el mismo stock simultáneamente
                $product = Product::where('id', $itemData['product_id'])
                    ->where('status', 'active')
                    ->lockForUpdate()
                    ->firstOrFail();

                // Verificar stock suficiente
                if ($product->stock < $itemData['quantity']) {
                    throw new \Exception("Stock insuficiente para '{$product->name}'. Disponible: {$product->stock}.");
                }

                $subtotal = $product->price * $itemData['quantity'];

                ReservationItem::create([
                    'reservation_id' => $reservation->id,
                    'product_id'     => $product->id,
                    'quantity'       => $itemData['quantity'],
                    'unit_price'     => $product->price,
                    'subtotal'       => $subtotal,
                ]);

                // Descontar stock
                $product->decrement('stock', $itemData['quantity']);

                $total += $subtotal;
            }

            $reservation->update(['total' => $total]);

            return $reservation;
        });

        // Enviar notificación push de confirmación
        if (Auth::user()->pushSubscriptions()->exists()) {
            $reservation->load('items.product'); // Cargar items para el mensaje
            Auth::user()->notify(new ReservationConfirmation($reservation));
        }

        // Redirigir a la pantalla de éxito/confirmación del apartado
        return redirect()
            ->route('client.reservations.show', $reservation)
            ->with('success', "¡Apartado #{$reservation->reservation_number} creado exitosamente!");
    }

    /**
     * Ver detalle de un apartado (también sirve como pantalla post-confirmación).
     */
    public function show(Reservation $reservation)
    {
        // Asegurar que solo el dueño pueda verlo
        if ($reservation->client_id !== Auth::id()) {
            abort(403);
        }

        $reservation->load('items.product');

        // Generar los días disponibles para recolección (si está activo)
        $availablePickupDays = [];
        if ($reservation->status === 'active') {
            $availablePickupDays = $reservation->getAvailablePickupDays();
        }

        return view('client.reservations.show', compact('reservation', 'availablePickupDays'));
    }

    /**
     * Cancelar un apartado (el cliente puede cancelar si está activo).
     */
    public function cancel(Reservation $reservation)
    {
        if ($reservation->client_id !== Auth::id()) {
            abort(403);
        }

        if ($reservation->status !== 'active') {
            return back()->with('error', 'Este apartado ya no puede ser cancelado.');
        }

        // El método cancel() del modelo devuelve el stock automáticamente
        $reservation->cancel();

        return redirect()
            ->route('client.reservations.index')
            ->with('success', "Apartado #{$reservation->reservation_number} cancelado. El stock ha sido restaurado.");
    }
}
