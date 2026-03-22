<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class AppointmentConfirmed extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private Appointment $appointment
    ) {}

    public function via(object $notifiable): array
    {
        return [WebPushChannel::class];
    }

    public function toWebPush(object $notifiable, Notification $notification): WebPushMessage
    {
        $serviceName = $this->appointment->service->name ?? 'tu servicio';
        $date = $this->appointment->date->format('d/m/Y');
        $time = \Carbon\Carbon::parse($this->appointment->start_time)->format('h:i A');
        $stylistName = $this->appointment->stylist->user->name ?? 'tu estilista';

        return (new WebPushMessage)
            ->title('Cita Confirmada')
            ->body("Tu cita de {$serviceName} el {$date} a las {$time} con {$stylistName} ha sido confirmada.")
            ->icon('/img/icon-192.png')
            ->badge('/img/badge-72.png')
            ->tag('appointment-confirmed-' . $this->appointment->id)
            ->data([
                'url' => '/client/appointments',
            ])
            ->options([
                'TTL'     => 86400,
                'urgency' => 'high',
            ]);
    }
}
