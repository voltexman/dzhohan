<?php

namespace App\Enums;

enum BladeShape: string
{
    case DROP_POINT = 'drop_point';
    case CLIP_POINT = 'clip_point';
    case TANTO = 'tanto';
    case WHARNCLIFFE = 'wharncliffe';
    case SHEEPSFOOT = 'sheepsfoot';
    case SKINNER = 'skinner';
    case SPEAR_POINT = 'spear_point';

    public function label(): string
    {
        return match ($this) {
            self::DROP_POINT => 'Drop Point (Універсальний)',
            self::CLIP_POINT => 'Clip Point (Bowie стиль)',
            self::TANTO => 'Tanto (Танто)',
            self::WHARNCLIFFE => 'Wharncliffe (Ворнкліфф)',
            self::SHEEPSFOOT => 'Sheepsfoot (Овече копито)',
            self::SKINNER => 'Skinner (Для зняття шкури)',
            self::SPEAR_POINT => 'Spear Point (Спис)',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
