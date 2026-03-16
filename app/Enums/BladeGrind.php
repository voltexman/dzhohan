<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum BladeGrind: string implements HasLabel
{
    case FLAT = 'flat';
    case FULL_FLAT = 'full_flat';
    case HOLLOW = 'hollow';
    case SABER = 'saber';
    case SCANDI = 'scandi';
    case CONVEX = 'convex';
    case CHISEL = 'chisel';
    case COMPOUND = 'compound';
    case DOUBLE = 'double';
    case V_GRIND = 'v_grind';

    public function getLabel(): string
    {
        return match ($this) {
            self::FLAT => 'Flat Grind',
            self::FULL_FLAT => 'Full Flat Grind',
            self::HOLLOW => 'Hollow Grind',
            self::SABER => 'Saber Grind',
            self::SCANDI => 'Scandi Grind',
            self::CONVEX => 'Convex Grind',
            self::CHISEL => 'Chisel Grind',
            self::COMPOUND => 'Compound Grind',
            self::DOUBLE => 'Double Edge Grind',
            self::V_GRIND => 'V-Grind',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
