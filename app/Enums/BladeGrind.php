<?php

namespace App\Enums;

enum BladeGrind: string
{
    case FLAT = 'flat';
    case HOLLOW = 'hollow';
    case SCANDI = 'scandi';
    case CONVEX = 'convex';

    public function label(): string
    {
        return match ($this) {
            self::FLAT => 'Прямі спуски',
            self::HOLLOW => 'Увігнуті спуски',
            self::SCANDI => 'Скандинавські спуски',
            self::CONVEX => 'Лінзовидні спуски',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
