<?php

namespace App\Console\Commands;

use App\Models\PushNotificationLog;
use App\Models\Reservation;
use App\Notifications\ReservationExpiringReminder;
use Illuminate\Console\Command;

class SendReservationReminders extends Command
{
    protected $signature = 'notifications:reservation-reminders';
    protected $description = 'Envía recordatorios push para apartados que vencen en 2 días o menos';

    public function handle(): int
    {
        // Buscar apartados activos que vencen en 2 días o menos
        // y cuyo cliente tenga suscripción push activa
        $reservations = Reservation::query()
            ->with('client')
            ->where('status', 'active')
            ->whereDate('expiration_date', '<=', now()->addDays(2))
            ->whereDate('expiration_date', '>=', today())
            ->whereHas('client.pushSubscriptions')
            ->get();

        if ($reservations->isEmpty()) {
            $this->info('No hay apartados próximos a vencer.');
            return self::SUCCESS;
        }

        $count = 0;

        foreach ($reservations as $reservation) {
            try {
                $reservation->client->notify(new ReservationExpiringReminder($reservation));
                $count++;
            } catch (\Throwable $e) {
                $this->error("Error enviando recordatorio para apartado #{$reservation->id}: {$e->getMessage()}");
            }
        }

        if ($count > 0) {
            PushNotificationLog::create([
                'title'            => 'Recordatorio de Apartado',
                'body'             => "Recordatorios de apartados próximos a vencer",
                'type'             => 'reservation_reminder',
                'audience'         => 'selected',
                'recipients_count' => $count,
                'sent_by'          => null,
            ]);
        }

        $this->info("Se enviaron {$count} recordatorios de apartados.");
        return self::SUCCESS;
    }
}
