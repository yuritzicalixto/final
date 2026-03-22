<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PushNotificationLog extends Model
{
    protected $fillable = [
        'title',
        'body',
        'url',
        'type',
        'audience',
        'recipients_count',
        'sent_by',
        'recipient_ids',
    ];

    protected $casts = [
        'recipient_ids' => 'array',
    ];

    /**
     * El admin que envió la notificación.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    /**
     * Etiqueta legible del tipo de notificación.
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'promotional' => 'Promocional',
            'appointment_reminder' => 'Recordatorio de Cita',
            'reservation_reminder' => 'Recordatorio de Apartado',
            default => $this->type,
        };
    }

    /**
     * Color del badge según tipo.
     */
    public function getTypeColorAttribute(): string
    {
        return match ($this->type) {
            'promotional' => 'purple',
            'appointment_reminder' => 'blue',
            'reservation_reminder' => 'amber',
            default => 'gray',
        };
    }
}
