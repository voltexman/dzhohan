<?php

namespace App\Enums;

enum DeliveryMethod: string
{
    case NovaPoshta = 'nova_poshta';
    case UkrPoshta = 'ukr_poshta';
    case Pickup = 'pickup';
    case Courier = 'courier';

    public function label(): string
    {
        return match ($this) {
            self::NovaPoshta => 'Нова Пошта',
            self::UkrPoshta => 'Укрпошта',
            self::Pickup => 'Самовивіз',
            self::Courier => 'Кур’єр',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
