<?php
// app/Models/CartItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    // =====================================================
    // RELACIONES
    // =====================================================

    /**
     * El carrito al que pertenece.
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * El producto.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // =====================================================
    // ACCESSORS
    // =====================================================

    /**
     * Subtotal de este item.
     */
    public function getSubtotalAttribute(): float
    {
        return $this->quantity * $this->product->price;
    }

    /**
     * Subtotal formateado.
     */
    public function getSubtotalFormattedAttribute(): string
    {
        return '$' . number_format($this->subtotal, 2);
    }
}
