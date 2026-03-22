<?php
// app/Models/Promotion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'start_date',
        'end_date',
        'segment',
        'send_type',
        'scheduled_at',
        'sent_count',
        'opened_count',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'scheduled_at' => 'datetime',
        'sent_count' => 'integer',
        'opened_count' => 'integer',
    ];

    // =====================================================
    // SCOPES
    // =====================================================

    /**
     * Promociones activas (dentro del rango de fechas).
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'sent')
                     ->where('start_date', '<=', today())
                     ->where('end_date', '>=', today());
    }

    /**
     * Promociones programadas pendientes de envío.
     */
    public function scopeScheduledAndPending($query)
    {
        return $query->where('status', 'scheduled')
                     ->where('scheduled_at', '<=', now());
    }

    // =====================================================
    // ACCESSORS
    // =====================================================

    /**
     * Tasa de apertura en porcentaje.
     */
    public function getOpenRateAttribute(): float
    {
        if ($this->sent_count === 0) {
            return 0;
        }
        return round(($this->opened_count / $this->sent_count) * 100, 2);
    }

    /**
     * ¿Está activa actualmente?
     */
    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'sent' &&
               $this->start_date <= today() &&
               $this->end_date >= today();
    }

    // =====================================================
    // MÉTODOS DE NEGOCIO
    // =====================================================

    /**
     * Obtiene los usuarios objetivo según el segmento.
     */
    public function getTargetUsers()
    {
        $query = User::role('client');

        return match($this->segment) {
            'frequent' => $query->whereHas('appointments', function ($q) {
                $q->where('status', 'completed')
                  ->where('date', '>=', now()->subMonths(3));
            }, '>=', 3)->get(),

            'inactive' => $query->whereDoesntHave('appointments', function ($q) {
                $q->where('date', '>=', now()->subMonths(2));
            })->get(),

            default => $query->get(), // 'all'
        };
    }

    /**
     * Incrementa el contador de envíos.
     */
    public function incrementSentCount(int $count = 1): void
    {
        $this->increment('sent_count', $count);
    }

    /**
     * Incrementa el contador de aperturas.
     */
    public function incrementOpenedCount(): void
    {
        $this->increment('opened_count');
    }
}
