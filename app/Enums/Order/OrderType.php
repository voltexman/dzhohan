<?php

namespace App\Enums\Order;

use BackedEnum;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum OrderType: string implements HasIcon, HasLabel
{
    case Purchase = 'purchase';     // Купівля
    case Manufacturing = 'manufacturing'; // Виготовлення

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Purchase => 'Купівля',
            self::Manufacturing => 'Виготовлення',
        };
    }

    public function getIcon(): string|BackedEnum|Htmlable|null
    {
        return match ($this) {
            self::Purchase => 'heroicon-m-shopping-bag',
            self::Manufacturing => 'heroicon-m-wrench-screwdriver',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
