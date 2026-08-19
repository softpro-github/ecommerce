<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'coupon_id', 'tx_ref', 'flutterwave_tx_id', 'status',
        'subtotal', 'discount', 'shipping_fee', 'total', 'currency',
        'shipping_name', 'shipping_phone', 'shipping_address', 'customer_email',
        'customer_country', 'customer_state', 'customer_city', 'customer_street',
        'ships_to_customer_address', 'shipping_country', 'shipping_state', 'shipping_city',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'shipping_fee' => 'decimal:2',
            'total' => 'decimal:2',
            'ships_to_customer_address' => 'boolean',
        ];
    }

    public function fullShippingAddress(): string
    {
        return collect([$this->shipping_address, $this->shipping_city, $this->shipping_state, $this->shipping_country])
            ->filter()
            ->implode(', ');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
