<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum HandleMaterial: string implements HasLabel
{
    case G10 = 'g10';
    case MICARTA = 'micarta';
    case CARBON = 'carbon_fiber';
    case FRN = 'frn';
    case TITANIUM = 'titanium';
    case ALUMINUM = 'aluminum';
    case WOOD = 'wood';
    case STAB_WOOD = 'stab_wood';
    case BONE = 'bone';
    case PARACORD = 'paracord';
    case ELASTRON = 'elastron';

    public function getLabel(): string
    {
        return match ($this) {
            self::G10 => 'G10 (Склотекстоліт)',
            self::MICARTA => 'Мікарта',
            self::CARBON => 'Карбон (Carbon Fiber)',
            self::FRN => 'FRN (Термопластик)',
            self::TITANIUM => 'Титан',
            self::ALUMINUM => 'Авіаційний алюміній',
            self::WOOD => 'Натуральне дерево',
            self::STAB_WOOD => 'Стабілізована деревина',
            self::BONE => 'Кістка / Ріг',
            self::PARACORD => 'Обмотка паракордом',
            self::ELASTRON => 'Еластрон (Гумопластик)',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
