<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable =
        [
            'status', 'total_amount', 'notes', 'delivered_at'
        ];
    protected $casts =
        [
            'total_amount' => 'decimal:2',
            'delivered_at' => 'datetime'
        ];

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function hasBeenDelivered(): bool
    {
        return $this->status=='delivered';
    }
}
