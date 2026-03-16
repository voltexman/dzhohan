<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum BladeShape: string implements HasLabel
{
    case DROPPONT = 'drop_point';
    case CLIPPOINT = 'clip_point';
    case TANTO = 'tanto';
    case SPEARPOINT = 'spear_point';
    case SHEEPSFOOT = 'sheepsfoot';
    case WHARNCLIFFE = 'wharncliffe';
    case TRAILINGPOINT = 'trailing_point';
    case HAWKBILL = 'hawkbill';
    case DAGGER = 'dagger';

    public function getLabel(): string
    {
        return match ($this) {
            self::DROPPONT => 'Drop Point',
            self::CLIPPOINT => 'Clip Point',
            self::TANTO => 'Tanto',
            self::SPEARPOINT => 'Spear Point',
            self::SHEEPSFOOT => 'Sheepsfoot',
            self::WHARNCLIFFE => 'Wharncliffe',
            self::TRAILINGPOINT => 'Trailing Point',
            self::HAWKBILL => 'Hawkbill',
            self::DAGGER => 'Dagger',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
