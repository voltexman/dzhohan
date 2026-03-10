<?php

namespace App\Enums;

enum BladeShape: string
{
    case CLASSIC = 'classic';
    case PERSIAN = 'persian';
    case CLIP_POINT = 'clip_point';
    case DROP_POINT = 'drop_point';
    case SPEAR_POINT = 'spear_point';
    case DAGGER = 'dagger';
    case BOWIE = 'bowie';
    case WHARNCLIFFE = 'wharncliffe';
    case SHEEPFOOT = 'sheepfoot';
    case HAWKBILL = 'hawkbill';
    case TANTO = 'tanto';

    public function label(): string
    {
        return match ($this) {
            self::CLASSIC => 'Класичний',
            self::PERSIAN => 'Перський',
            self::CLIP_POINT => 'Clip Point',
            self::DROP_POINT => 'Drop Point',
            self::SPEAR_POINT => 'Spear Point',
            self::DAGGER => 'Кинджальний',
            self::BOWIE => 'Bowie',
            self::WHARNCLIFFE => 'Wharncliffe',
            self::SHEEPFOOT => 'Sheepsfoot',
            self::HAWKBILL => 'Hawkbill',
            self::TANTO => 'Tanto',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::CLASSIC => 'classic.png',
            self::PERSIAN => 'persian.png',
            self::CLIP_POINT => 'clip-point.png',
            self::DROP_POINT => 'drop-point.png',
            self::SPEAR_POINT => 'spear-point.png',
            self::DAGGER => 'dagger.png',
            self::BOWIE => 'bowie.png',
            self::WHARNCLIFFE => 'wharncliffe.png',
            self::SHEEPFOOT => 'sheepsfoot.png',
            self::HAWKBILL => 'hawkbill.png',
            self::TANTO => 'tanto.png',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
