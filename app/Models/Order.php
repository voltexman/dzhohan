<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
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
        'type',
        'status',
    ];

    protected $casts = [
        'type' => OrderType::class,
        'status' => OrderStatus::class,
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
