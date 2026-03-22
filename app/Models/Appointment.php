<?php
// app/Models/Appointment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'stylist_id',
        'service_id',
        'reservation_id',
        'date',
        'start_time',
        'end_time',
        'status',
        'notes',
        'cancellation_reason',
        'cancelled_by',
        'cancelled_at',
    ];

    protected $casts = [
        'date' => 'date',
        'cancelled_at' => 'datetime',
    ];

    // =====================================================
    // RELACIONES
    // =====================================================

    /**
     * El cliente que agendó la cita.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * El estilista asignado.
     * Puede ser null si el cliente eligió "sin preferencia".
     */
    public function stylist(): BelongsTo
    {
        return $this->belongsTo(Stylist::class);
    }

    /**
     * El servicio solicitado.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * El apartado vinculado (opcional).
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    // =====================================================
    // SCOPES
    // =====================================================

    /**
     * Citas próximas (futuras y no canceladas).
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', today())
                     ->whereIn('status', ['pending', 'confirmed'])
                     ->orderBy('date')
                     ->orderBy('start_time');
    }

    /**
     * Citas de hoy.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }

    /**
     * Citas de esta semana.
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('date', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    /**
     * Citas pendientes de confirmar.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // =====================================================
    // ACCESSORS
    // =====================================================

    /**
     * ¿La cita se puede cancelar?
     * Regla de negocio: Solo con >= 4 horas de anticipación.
     */
    public function getCanBeCancelledAttribute(): bool
    {
        // No se puede cancelar si ya está cancelada o completada
        if (in_array($this->status, ['cancelled', 'completed', 'no_show'])) {
            return false;
        }

        // Calcular la fecha/hora de la cita
        $appointmentDateTime = Carbon::parse(
            $this->date->format('Y-m-d') . ' ' . $this->start_time
        );

        // Verificar si faltan al menos 4 horas
        return now()->diffInHours($appointmentDateTime, false) >= 4;
    }

    /**
     * Fecha y hora formateadas para mostrar.
     */
    public function getDateTimeFormattedAttribute(): string
    {
        return $this->date->format('d/m/Y') . ' ' .
               Carbon::parse($this->start_time)->format('H:i');
    }

    /**
     * Rango de horas: "10:00 - 11:30"
     */
    public function getTimeRangeAttribute(): string
    {
        return Carbon::parse($this->start_time)->format('H:i') . ' - ' .
               Carbon::parse($this->end_time)->format('H:i');
    }

    /**
     * Color según el estado (para calendarios).
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => '#FFA500',    // Naranja
            'confirmed' => '#3B82F6',  // Azul
            'completed' => '#10B981',  // Verde
            'cancelled' => '#EF4444',  // Rojo
            'no_show' => '#6B7280',    // Gris
            default => '#6B7280',
        };
    }

    // =====================================================
    // MÉTODOS DE NEGOCIO
    // =====================================================

    /**
     * Cancela la cita.
     *
     * @param string $reason Motivo de cancelación
     * @param string $cancelledBy 'client' o 'admin'
     */
    public function cancel(string $reason = null, string $cancelledBy = 'client'): bool
    {
        if (!$this->can_be_cancelled) {
            return false;
        }

        $this->update([
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
            'cancelled_by' => $cancelledBy,
            'cancelled_at' => now(),
        ]);

        return true;
    }

    /**
     * Marca la cita como completada.
     */
    public function markAsCompleted(): void
    {
        $this->update(['status' => 'completed']);
    }

    /**
     * Confirma la cita.
     */
    public function confirm(): void
    {
        $this->update(['status' => 'confirmed']);
    }

    /**
     * Marca como no-show (cliente no se presentó).
     */
    public function markAsNoShow(): void
    {
        $this->update(['status' => 'no_show']);
    }
}
