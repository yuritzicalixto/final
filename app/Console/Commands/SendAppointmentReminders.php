<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Models\PushNotificationLog;
use App\Notifications\AppointmentReminder;
use Illuminate\Console\Command;

class SendAppointmentReminders extends Command
{
    protected $signature = 'notifications:appointment-reminders';
    protected $description = 'Envía recordatorios push para citas de mañana';

    public function handle(): int
    {
        // Buscar citas de MAÑANA que:
        // - Estén pendientes o confirmadas
        // - No se les haya enviado recordatorio
        // - El cliente tenga al menos una suscripción push activa
        $appointments = Appointment::query()
            ->with(['client', 'service'])
            ->whereDate('date', now()->addDay()->toDateString())
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('reminder_sent', false)
            ->whereHas('client.pushSubscriptions')
            ->get();

        if ($appointments->isEmpty()) {
            $this->info('No hay recordatorios de citas pendientes.');
            return self::SUCCESS;
        }

        $count = 0;

        foreach ($appointments as $appointment) {
            try {
                $appointment->client->notify(new AppointmentReminder($appointment));
                $appointment->update(['reminder_sent' => true]);
                $count++;
            } catch (\Throwable $e) {
                $this->error("Error enviando recordatorio para cita #{$appointment->id}: {$e->getMessage()}");
            }
        }

        // Registrar en el log
        if ($count > 0) {
            PushNotificationLog::create([
                'title'            => 'Recordatorio de Cita',
                'body'             => "Recordatorios enviados para citas del " . now()->addDay()->format('d/m/Y'),
                'type'             => 'appointment_reminder',
                'audience'         => 'selected',
                'recipients_count' => $count,
                'sent_by'          => null, // automático
            ]);
        }

        $this->info("Se enviaron {$count} recordatorios de citas.");
        return self::SUCCESS;
    }
}
