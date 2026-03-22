<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class AppointmentReminder extends Notification implements ShouldQueue
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
        $serviceName = $this->appointment->service->name ?? 'tu cita';
        $time = \Carbon\Carbon::parse($this->appointment->start_time)->format('h:i A');
        $date = $this->appointment->date->format('d/m/Y');

        return (new WebPushMessage)
            ->title('📅 Recordatorio de Cita')
            ->body("Tienes cita de {$serviceName} mañana {$date} a las {$time}. ¡Te esperamos!")
            ->icon('/img/icon-192.png')
            ->badge('/img/badge-72.png')
            ->tag('appointment-' . $this->appointment->id)
            ->requireInteraction(true)
            ->data([
                'url' => '/client/appointments',
            ])
            ->options([
                'TTL'     => 86400,    // 24 horas de vida en el servidor push
                'urgency' => 'high',
            ]);
    }
}
