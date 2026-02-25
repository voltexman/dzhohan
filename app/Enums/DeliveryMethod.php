<?php

namespace App\Enums;

enum DeliveryMethod: string
{
    case NovaPoshta = 'nova_poshta';
    case UkrPoshta = 'ukr_poshta';

    // Назва українською
    public function label(): string
    {
        return match ($this) {
            self::NovaPoshta => 'Нова Пошта',
            self::UkrPoshta => 'Укрпошта',
        };
    }

    // Логотипи (можна використовувати в <img> src)
    public function logo(): string
    {
        return match ($this) {
            self::NovaPoshta => 'https://upload.wikimedia.org',
            self::UkrPoshta => 'https://upload.wikimedia.org',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
