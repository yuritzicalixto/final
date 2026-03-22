<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles; // ← Spatie
use NotificationChannels\WebPush\HasPushSubscriptions; // ← Notificaciones Push


class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles; // ← Spatie
    use HasPushSubscriptions; // ← Notificaciones Push


    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',  // ← Agregar este campo nuevo
        'status', // ← Agregar este campo nuevo
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    // =====================================================
    // RELACIONES
    // =====================================================

    /**
     * Relación 1:1 con Stylist
     * Un usuario (con rol estilista) tiene UN perfil de estilista.
     * La llave foránea 'user_id' está en la tabla stylists.
     */
    public function stylist()
    {
        return $this->hasOne(Stylist::class);
    }

    /**
     * Relación 1:N con Appointment (como cliente)
     * Un cliente puede tener MUCHAS citas.
     * Usamos 'client_id' porque así nombramos la FK en appointments.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'client_id');
    }

    /**
     * Relación 1:N con Reservation (como cliente)
     * Un cliente puede tener MUCHOS apartados.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'client_id');
    }

    /**
     * Relación 1:1 con Cart
     * Cada usuario tiene UN carrito (temporal).
     */
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    // =====================================================
    // MÉTODOS AUXILIARES
    // =====================================================

    /**
     * Verifica si el usuario es administrador.
     * Usa el sistema de roles de Spatie.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Verifica si el usuario es estilista.
     */
    public function isStylist(): bool
    {
        return $this->hasRole('stylist');
    }

    /**
     * Verifica si el usuario es cliente.
     */
    public function isClient(): bool
    {
        return $this->hasRole('client');
    }

    /**
     * Obtiene o crea el carrito del usuario.
     * Útil para asegurar que siempre exista un carrito.
     */
    public function getOrCreateCart(): Cart
    {
        return $this->cart ?? $this->cart()->create();
    }
}
