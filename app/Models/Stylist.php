<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stylist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address',       // Dirección del estilista (no bio)
        'specialties',
        'photo',
        'phone',
        'status',
    ];

    // =====================================================
    // RELACIONES
    // =====================================================

    /**
     * Relación inversa 1:1 con User
     * Este perfil de estilista PERTENECE A un usuario.
     *
     * Uso: $stylist->user->name
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación N:N con Service
     * Un estilista puede realizar MUCHOS servicios.
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'stylist_service')
                ->withTimestamps();
    }

    /**
     * Relación 1:N con Appointment
     * Un estilista tiene MUCHAS citas asignadas.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    // =====================================================
    // SCOPES
    // =====================================================

    /**
     * Solo estilistas disponibles.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    // =====================================================
    // MÉTODOS DE NEGOCIO
    // =====================================================

    /**
     * Obtiene las citas de hoy ordenadas por hora.
     */
    public function todayAppointments()
    {
        return $this->appointments()
                    ->whereDate('date', today())
                    ->where('status', '!=', 'cancelled')
                    ->orderBy('start_time')
                    ->get();
    }

    /**
     * Verifica si el estilista está disponible en un horario específico.
     *
     * Esto es CRUCIAL para evitar dobles reservas.
     *
     * @param string $date Fecha en formato Y-m-d
     * @param string $startTime Hora inicio en formato H:i
     * @param string $endTime Hora fin en formato H:i
     * @return bool
     */
    public function isAvailableAt(string $date, string $startTime, string $endTime): bool
    {
        // Busca si hay alguna cita que se traslape con el horario solicitado
        $hasConflict = $this->appointments()
            ->where('date', $date)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startTime, $endTime) {
                // Caso 1: La cita existente empieza dentro del rango solicitado
                $query->whereBetween('start_time', [$startTime, $endTime])
                    // Caso 2: La cita existente termina dentro del rango solicitado
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    // Caso 3: La cita existente envuelve completamente el rango
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                          ->where('end_time', '>=', $endTime);
                    });
            })
            ->exists();

        // Si NO hay conflicto, está disponible
        return !$hasConflict;
    }

    /**
     * Obtiene el nombre del usuario asociado.
     * Útil para no tener que hacer $stylist->user->name cada vez.
     */
    public function getNameAttribute(): string
    {
        return $this->user->name ?? 'Sin nombre';
    }
}
