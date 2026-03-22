<?php
// app/Models/Service.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'category',       // NUEVO — Categoría para agrupar en el frontend
        'description',
        'features',        // NUEVO — Características separadas por |
        'duration',
        'price',
        'image',
        'tag',             // NUEVO — Badge/etiqueta (ej: "Popular", "Signature")
        'is_highlighted',  // NUEVO — Si tiene borde dorado (paquetes especiales)
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration' => 'integer',
        'is_highlighted' => 'boolean',
    ];

    // =====================================================
    // RELACIONES
    // =====================================================

    public function stylists(): BelongsToMany
    {
        return $this->belongsToMany(Stylist::class, 'stylist_service')
                    ->withTimestamps();
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    // =====================================================
    // SCOPES
    // =====================================================

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // =====================================================
    // ACCESSORS
    // =====================================================

    /**
     * Devuelve la duración en formato legible.
     * Ejemplo: 90 → "1h 30min"
     */
    public function getDurationFormattedAttribute(): string
    {
        $hours = floor($this->duration / 60);
        $minutes = $this->duration % 60;

        if ($hours > 0 && $minutes > 0) {
            return "{$hours}h {$minutes}min";
        } elseif ($hours > 0) {
            return "{$hours}h";
        }
        return "{$minutes}min";
    }

    /**
     * Precio formateado como moneda.
     * Ejemplo: 150.00 → "$150.00"
     */
    public function getPriceFormattedAttribute(): string
    {
        return '$' . number_format($this->price, 2);
    }

    /**
     * Convierte el campo features (string con |) en un array.
     * Ejemplo: "Análisis profesional|Propuesta personalizada" → ['Análisis profesional', 'Propuesta personalizada']
     */
    public function getFeaturesArrayAttribute(): array
    {
        if (empty($this->features)) {
            return [];
        }
        return array_map('trim', explode('|', $this->features));
    }

    /**
     * URL de la imagen del servicio.
     * Si no tiene imagen, devuelve un placeholder.
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            // Si empieza con http, es una URL externa
            if (Str::startsWith($this->image, ['http://', 'https://'])) {
                return $this->image;
            }
            // Si no, es una imagen local en storage
            return asset('storage/' . $this->image);
        }
        // Placeholder si no hay imagen
        return 'https://images.unsplash.com/photo-1560066984-138dadb4c035?auto=format&fit=crop&w=800&q=80';
    }
}
