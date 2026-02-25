<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'name',
        'phone',
        'email',
        'delivery_method',
        'city',
        'address',
        'comment',
        'total_price',
        'status',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
    ];

    // Автоматична генерація номера при створенні
    protected static function booted()
    {
        static::creating(function ($order) {
            $order->number = 'ORD-'.strtoupper(uniqid());
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
