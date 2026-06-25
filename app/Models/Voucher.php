<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'code', 'name', 'type', 'value', 'min_order', 'max_discount',
    'usage_limit', 'used_count', 'starts_at', 'expires_at', 'is_active',
])]
class Voucher extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'value'        => 'decimal:2',
            'min_order'    => 'decimal:2',
            'max_discount' => 'decimal:2',
            'usage_limit'  => 'integer',
            'used_count'   => 'integer',
            'starts_at'    => 'datetime',
            'expires_at'   => 'datetime',
            'is_active'    => 'boolean',
        ];
    }

    // ──────────────────────────────────────────────
    // Scopes
    // ──────────────────────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeValid(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(fn (Builder $q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn (Builder $q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()))
            ->where(fn (Builder $q) => $q->whereNull('usage_limit')->orWhereColumn('used_count', '<', 'usage_limit'));
    }

    // ──────────────────────────────────────────────
    // Methods
    // ──────────────────────────────────────────────

    public function isValid(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if (! $this->isValid()) {
            return 0.0;
        }

        if ($this->min_order && $subtotal < (float) $this->min_order) {
            return 0.0;
        }

        $discount = match ($this->type) {
            'percentage' => $subtotal * ((float) $this->value / 100),
            'fixed'      => (float) $this->value,
            default      => 0.0,
        };

        if ($this->max_discount) {
            $discount = min($discount, (float) $this->max_discount);
        }

        return round(min($discount, $subtotal), 2);
    }

    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }
}
