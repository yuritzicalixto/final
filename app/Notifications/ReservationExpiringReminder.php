<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class ReservationExpiringReminder extends Notification implements ShouldQueue
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
        $daysLeft = (int) now()->diffInDays($this->reservation->expiration_date, false);
        $dayWord = $daysLeft === 1 ? 'día' : 'días';

        return (new WebPushMessage)
            ->title('⏰ Tu apartado está por vencer')
            ->body("Tu apartado #{$this->reservation->id} vence en {$daysLeft} {$dayWord}. Recógelo antes de que expire.")
            ->icon('/img/icon-192.png')
            ->badge('/img/badge-72.png')
            ->tag('reservation-expiring-' . $this->reservation->id)
            ->requireInteraction(true)
            ->data([
                'url' => '/client/reservations/' . $this->reservation->id,
            ])
            ->options([
                'TTL'     => 86400,
                'urgency' => 'high',
            ]);
    }
}
