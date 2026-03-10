<?php

namespace App\Models;

use App\Enums\Order\DeliveryMethod;
use App\Enums\Order\OrderStatus;
use App\Enums\Order\OrderType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'first_name',
        'last_name',
        'phone',
        'email',
        'delivery_method',
        'city',
        'address',
        'comment',
        'type',
        'status',
    ];

    protected $casts = [
        'type' => OrderType::class,
        'status' => OrderStatus::class,
        'delivery_method' => DeliveryMethod::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->number = (now()->getTimestamp() % 86400) . rand(100, 999);
        });
    }

    public function products(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function manufacture(): HasOne
    {
        return $this->hasOne(OrderManufacture::class);
    }
}
