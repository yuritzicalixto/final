<?php
// app/Models/Cart.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    // =====================================================
    // RELACIONES
    // =====================================================

    /**
     * El usuario dueño del carrito.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Los items en el carrito.
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    // =====================================================
    // ACCESSORS
    // =====================================================

    /**
     * Total del carrito.
     */
    public function getTotalAttribute(): float
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });
    }

    /**
     * Total formateado.
     */
    public function getTotalFormattedAttribute(): string
    {
        return '$' . number_format($this->total, 2);
    }

    /**
     * Cantidad total de productos (sumando cantidades).
     */
    public function getItemsCountAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    /**
     * Cantidad de productos diferentes en el carrito.
     * Regla: máximo 5 productos diferentes.
     */
    public function getProductsCountAttribute(): int
    {
        return $this->items->count();
    }

    // =====================================================
    // MÉTODOS DE NEGOCIO
    // =====================================================

    /**
     * Agrega un producto al carrito.
     *
     * @param Product $product
     * @param int $quantity
     * @return CartItem|false False si no se puede agregar
     */
    public function addProduct(Product $product, int $quantity = 1)
    {
        // Verificar regla: máximo 5 productos diferentes
        $existingItem = $this->items()->where('product_id', $product->id)->first();

        if (!$existingItem && $this->products_count >= 5) {
            return false; // Ya tiene 5 productos diferentes
        }

        // Verificar stock disponible
        $currentQuantity = $existingItem ? $existingItem->quantity : 0;
        if ($product->stock < ($currentQuantity + $quantity)) {
            return false; // No hay suficiente stock
        }

        // Si ya existe, actualizar cantidad
        if ($existingItem) {
            $existingItem->increment('quantity', $quantity);
            return $existingItem;
        }

        // Si no existe, crear nuevo item
        return $this->items()->create([
            'product_id' => $product->id,
            'quantity' => $quantity,
        ]);
    }

    /**
     * Actualiza la cantidad de un producto.
     */
    public function updateQuantity(Product $product, int $quantity): bool
    {
        $item = $this->items()->where('product_id', $product->id)->first();

        if (!$item) {
            return false;
        }

        if ($quantity <= 0) {
            $item->delete();
            return true;
        }

        if ($product->stock < $quantity) {
            return false;
        }

        $item->update(['quantity' => $quantity]);
        return true;
    }

    /**
     * Elimina un producto del carrito.
     */
    public function removeProduct(Product $product): bool
    {
        return $this->items()->where('product_id', $product->id)->delete() > 0;
    }

    /**
     * Vacía el carrito completamente.
     */
    public function clear(): void
    {
        $this->items()->delete();
    }

    /**
     * Convierte el carrito en un apartado.
     * Este es el proceso principal cuando el cliente confirma.
     */
    public function convertToReservation(): ?Reservation
    {
        if ($this->items->isEmpty()) {
            return null;
        }

        // Crear el apartado
        $reservation = Reservation::create([
            'client_id' => $this->user_id,
            'total' => $this->total,
            'status' => 'active',
        ]);

        // Transferir items y reducir stock
        foreach ($this->items as $cartItem) {
            // Crear item del apartado
            $reservation->items()->create([
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'unit_price' => $cartItem->product->price,
                'subtotal' => $cartItem->quantity * $cartItem->product->price,
            ]);

            // Reducir stock del producto
            $cartItem->product->decreaseStock($cartItem->quantity);
        }

        // Vaciar el carrito
        $this->clear();

        return $reservation;
    }
}
