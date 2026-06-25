<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RuntimeException;

#[Fillable([
    'product_id', 'size', 'color', 'color_hex', 'sku',
    'price_adjustment', 'stock', 'is_active', 'sort_order'
])]
class ProductVariant extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price_adjustment' => 'decimal:2',
            'stock'            => 'integer',
            'is_active'        => 'boolean',
        ];
    }

    // ──────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // ──────────────────────────────────────────────
    // Scopes
    // ──────────────────────────────────────────────

    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // ──────────────────────────────────────────────
    // Methods
    // ──────────────────────────────────────────────

    public function getFinalPrice(): float
    {
        return (float) ($this->product->current_price + $this->price_adjustment);
    }

    public function decrementStock(int $quantity): void
    {
        if ($this->stock < $quantity) {
            throw new RuntimeException(
                "Insufficient stock for variant [{$this->sku}]. Available: {$this->stock}, requested: {$quantity}."
            );
        }

        $this->decrement('stock', $quantity);
    }
}
