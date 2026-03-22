<?php
// app/Models/Reservation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'reservation_number',
        'reservation_date',
        'expiration_date',
        'preferred_pickup_date', // ← NUEVO
        'total',
        'status',
        'notes',
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'expiration_date' => 'date',
        'preferred_pickup_date' => 'datetime', // ← NUEVO
        'total' => 'decimal:2',
    ];

    // =====================================================
    // CONSTANTES DE HORARIO DEL NEGOCIO
    // =====================================================

    /** Hora de apertura (24h) */
    const BUSINESS_OPEN = 10;

    /** Hora de cierre (24h) */
    const BUSINESS_CLOSE = 17;

    /** Días de operación (1=Lunes ... 6=Sábado). Domingo (0) no se incluye. */
    const BUSINESS_DAYS = [1, 2, 3, 4, 5, 6]; // Lunes a Sábado

    // =====================================================
    // EVENTOS DEL MODELO (Boot)
    // =====================================================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($reservation) {
            // Generar número de apartado automáticamente si no existe
            if (!$reservation->reservation_number) {
                $year = now()->year;
                $lastId = static::whereYear('created_at', $year)->max('id') ?? 0;
                $reservation->reservation_number = sprintf('APT-%d-%05d', $year, $lastId + 1);
            }

            // Establecer fecha de expiración: +7 días
            if (!$reservation->expiration_date) {
                $reservation->expiration_date = now()->addDays(7);
            }

            // Establecer fecha de apartado si no existe
            if (!$reservation->reservation_date) {
                $reservation->reservation_date = now();
            }
        });
    }

    // =====================================================
    // RELACIONES
    // =====================================================

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ReservationItem::class);
    }

    public function appointment(): HasOne
    {
        return $this->hasOne(Appointment::class);
    }

    // =====================================================
    // SCOPES
    // =====================================================

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeExpiringSoon($query)
    {
        return $query->active()
                     ->whereDate('expiration_date', '<=', now()->addDays(2));
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'active')
                     ->whereDate('expiration_date', '<', today());
    }

    // =====================================================
    // ACCESSORS
    // =====================================================

    public function getDaysRemainingAttribute(): int
    {
        if ($this->status !== 'active') {
            return 0;
        }
        return max(0, now()->startOfDay()->diffInDays($this->expiration_date, false));
    }

    public function getIsExpiringAttribute(): bool
    {
        return $this->status === 'active' && $this->days_remaining <= 2;
    }

    public function getTotalFormattedAttribute(): string
    {
        return '$' . number_format($this->total, 2);
    }

    /**
     * Texto legible del estado.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active'    => 'Activo',
            'completed' => 'Completado',
            'expired'   => 'Expirado',
            'cancelled' => 'Cancelado',
            default     => $this->status,
        };
    }

    /**
     * Color CSS para el badge del estado.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'active'    => 'yellow',
            'completed' => 'green',
            'expired'   => 'gray',
            'cancelled' => 'red',
            default     => 'gray',
        };
    }

    // =====================================================
    // MÉTODOS ESTÁTICOS DE HORARIO
    // =====================================================

    /**
     * Verifica si una fecha/hora cae dentro del horario del negocio.
     * Lunes a Sábado, 10:00 - 17:00. Domingos cerrado.
     */
    public static function isWithinBusinessHours(Carbon $dateTime): bool
    {
        // dayOfWeekIso: 1=Lunes, 7=Domingo
        $dayOfWeek = $dateTime->dayOfWeekIso;

        // Verificar que no sea domingo (7)
        if ($dayOfWeek === 7) {
            return false;
        }

        // Verificar que sea entre 10:00 y 17:00
        $hour = $dateTime->hour;
        return $hour >= self::BUSINESS_OPEN && $hour < self::BUSINESS_CLOSE;
    }

    /**
     * Genera los días hábiles disponibles para recoger el apartado.
     * Retorna un array de fechas (Carbon) de L-S dentro de los 7 días de vigencia.
     */
    public function getAvailablePickupDays(): array
    {
        $days = [];
        $start = Carbon::today()->addDay(); // Desde mañana
        $end = $this->expiration_date;

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            // dayOfWeekIso: 7 = Domingo → excluir
            if ($date->dayOfWeekIso !== 7) {
                $days[] = $date->copy();
            }
        }

        return $days;
    }

    // =====================================================
    // MÉTODOS DE NEGOCIO
    // =====================================================

    public function recalculateTotal(): void
    {
        $this->total = $this->items->sum('subtotal');
        $this->save();
    }

    public function markAsCompleted(): void
    {
        $this->update(['status' => 'completed']);
    }

    public function cancel(): void
    {
        foreach ($this->items as $item) {
            $item->product->increaseStock($item->quantity);
        }
        $this->update(['status' => 'cancelled']);
    }

    public function expire(): void
    {
        foreach ($this->items as $item) {
            $item->product->increaseStock($item->quantity);
        }
        $this->update(['status' => 'expired']);
    }
}
