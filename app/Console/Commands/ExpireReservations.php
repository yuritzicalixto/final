<?php

namespace App\Console\Commands;

use App\Models\Reservation;
use Illuminate\Console\Command;

class ExpireReservations extends Command
{
    /**
     * Nombre y descripción del comando.
     * Se ejecuta con: php artisan reservations:expire
     */
    protected $signature = 'reservations:expire';
    protected $description = 'Expira apartados activos que hayan superado su fecha de vencimiento y devuelve el stock.';

    public function handle()
    {
        // Buscar apartados activos cuya fecha de expiración ya pasó
        $expiredReservations = Reservation::expired()
            ->with('items.product')
            ->get();

        if ($expiredReservations->isEmpty()) {
            $this->info('No hay apartados vencidos para procesar.');
            return 0;
        }

        $count = 0;
        foreach ($expiredReservations as $reservation) {
            $reservation->expire(); // Devuelve stock y cambia estado a 'expired'
            $count++;
            $this->line("  → Expirado: {$reservation->reservation_number}");
        }

        $this->info("✓ Se expiraron {$count} apartado(s). Stock restaurado.");
        return 0;
    }
}
