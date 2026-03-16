<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum HandleMaterial: string implements HasLabel
{
    case G10 = 'g10';
    case MICARTA = 'micarta';
    case CARBON_FIBER = 'carbon_fiber';
    case TITANIUM = 'titanium';
    case STAINLESS_STEEL = 'stainless_steel';
    case WOOD = 'wood';
    case RUBBER = 'rubber';
    case FRN = 'frn';
    case BONE = 'bone';
    case MAMMOTH_TUSK = 'mammoth_tusk';
    case MAMMOTH_TOOTH = 'mammoth_tooth';
    case WALRUS_TUSK = 'walrus_tusk';

    public function getLabel(): string
    {
        return match ($this) {
            self::G10 => 'G10',
            self::MICARTA => 'Micarta',
            self::CARBON_FIBER => 'Carbon Fiber',
            self::TITANIUM => 'Titanium',
            self::STAINLESS_STEEL => 'Stainless Steel',
            self::WOOD => 'Wood',
            self::RUBBER => 'Rubber',
            self::FRN => 'FRN (Fiberglass Reinforced Nylon)',
            self::BONE => 'Bone',
            self::MAMMOTH_TUSK => 'Mammoth Tusk (бивень мамонта)',
            self::MAMMOTH_TOOTH => 'Mammoth Tooth (зуб мамонта)',
            self::WALRUS_TUSK => 'Walrus Tusk (клик моржа)',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
