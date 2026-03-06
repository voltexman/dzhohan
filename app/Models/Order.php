<?php

namespace App\Models;

use App\Enums\Order\OrderStatus;
use App\Enums\Order\OrderType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'total_price',
        'custom_options',
        'type',
        'status',
    ];

    protected $casts = [
        'type' => OrderType::class,
        'status' => OrderStatus::class,
        'custom_options' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            do {
                $number = now()->format('ymd').'-'.random_int(10000, 99999);
            } while (static::where('number', $number)->exists());

            $order->number = $number;
        });
    }

    public function products(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }
}
