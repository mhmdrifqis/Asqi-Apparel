<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

#[Fillable([
    'order_number', 'user_id', 'status', 'subtotal', 'shipping_cost',
    'discount', 'tax', 'total', 'shipping_address', 'billing_address',
    'shipping_method', 'tracking_number', 'courier', 'payment_method',
    'payment_status', 'payment_token', 'payment_url', 'payment_expired_at',
    'voucher_code', 'notes', 'ordered_at',
])]
class Order extends Model
{
    use SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'subtotal'           => 'decimal:2',
            'shipping_cost'      => 'decimal:2',
            'discount'           => 'decimal:2',
            'tax'                => 'decimal:2',
            'total'              => 'decimal:2',
            'shipping_address'   => 'array',
            'billing_address'    => 'array',
            'ordered_at'         => 'datetime',
            'payment_expired_at' => 'datetime',
        ];
    }

    // ──────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // ──────────────────────────────────────────────
    // Methods
    // ──────────────────────────────────────────────

    public static function generateOrderNumber(): string
    {
        return 'ASQI-' . date('Ymd') . '-' . strtoupper(Str::random(6));
    }

    public function getStatusLabel(): string
    {
        return ucfirst($this->status);
    }

    public function getStatusColor(): string
    {
        return match ($this->status) {
            'pending'    => 'warning',
            'processing' => 'info',
            'shipped'    => 'primary',
            'delivered'  => 'success',
            'cancelled'  => 'danger',
            'refunded'   => 'secondary',
            default      => 'secondary',
        };
    }
}
