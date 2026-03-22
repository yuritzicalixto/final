<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class ReservationConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private Reservation $reservation
    ) {}

    public function via(object $notifiable): array
    {
        return [WebPushChannel::class];
    }

    public function toWebPush(object $notifiable, Notification $notification): WebPushMessage
    {
        $expDate = $this->reservation->expiration_date->format('d/m/Y');
        $total = $this->reservation->total_formatted;

        return (new WebPushMessage)
            ->title('Apartado Confirmado')
            ->body("Tu apartado #{$this->reservation->reservation_number} por {$total} fue registrado. Recógelo antes del {$expDate}.")
            ->icon('/img/icon-192.png')
            ->badge('/img/badge-72.png')
            ->tag('reservation-confirmed-' . $this->reservation->id)
            ->data([
                'url' => '/client/reservations/' . $this->reservation->id,
            ])
            ->options([
                'TTL'     => 86400,
                'urgency' => 'normal',
            ]);
    }
}
