<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum CurrencyType: string implements HasLabel
{
    case UAH = 'uah';
    case USD = 'usd';
    case EUR = 'eur';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::UAH => 'Гривня (₴)',
            self::USD => 'Долар ($)',
            self::EUR => 'Євро (€)',
        };
    }

    public function getSymbol(): string
    {
        return match ($this) {
            self::UAH => '₴',
            self::USD => '$',
            self::EUR => '€',
        };
    }

    public function format(float|int $amount): string
    {
        $formatted = number_format($amount, 0, '.', ' ');

        return match ($this) {
            self::USD => '$'.$formatted,
            self::EUR => '€'.$formatted,
            self::UAH => $formatted.' ₴',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
