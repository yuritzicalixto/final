<?php
// app/Models/ReservationItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // =====================================================
    // EVENTOS DEL MODELO
    // =====================================================

    protected static function boot()
    {
        parent::boot();

        // Calcular subtotal automáticamente antes de guardar
        static::saving(function ($item) {
            $item->subtotal = $item->quantity * $item->unit_price;
        });

        // Después de guardar, recalcular el total del apartado
        static::saved(function ($item) {
            $item->reservation->recalculateTotal();
        });

        // Después de eliminar, recalcular el total
        static::deleted(function ($item) {
            $item->reservation->recalculateTotal();
        });
    }

    // =====================================================
    // RELACIONES
    // =====================================================

    /**
     * El apartado al que pertenece este item.
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * El producto de este item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // =====================================================
    // ACCESSORS
    // =====================================================

    public function getSubtotalFormattedAttribute(): string
    {
        return '$' . number_format($this->subtotal, 2);
    }
}
