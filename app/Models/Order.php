<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
            do {
                $number = (now()->getTimestamp() % 86400) . rand(100, 999);
            } while (static::where('number', $number)->exists());

            $order->number = $number;
        });
    }

    protected static function booted(): void
    {
        static::updated(function ($order) {
            if ($order->wasChanged('status') && $order->status === OrderStatus::Completed) {
                Product::whereIn('id', $order->products()->pluck('product_id'))->update(['quantity' => 0]);
            }
        });
    }

    protected function fullName(): Attribute
    {
        return Attribute::get(fn() => "{$this->first_name} {$this->last_name}");
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
