<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum BladeGrind: string implements HasLabel
{
    case FLAT = 'flat';
    case HOLLOW = 'hollow';
    case SCANDI = 'scandi';
    case CONVEX = 'convex';

    public function getLabel(): string
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
