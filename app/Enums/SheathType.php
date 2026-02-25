<?php

namespace App\Enums;

enum SheathType: string
{
    case LEATHER = 'leather';
    case KYDEX = 'kydex';
    case PLASTIC = 'plastic';
    case CORDURA = 'cordura';
    case WOOD = 'wood';
    case NONE = 'none';

    public function label(): string
    {
        return match ($this) {
            self::LEATHER => 'Натуральна шкіра',
            self::KYDEX => 'Kydex (Кайдекс)',
            self::PLASTIC => 'Ударостійкий пластик',
            self::CORDURA => 'Cordura (Нейлон)',
            self::WOOD => 'Дерев’яні піхви',
            self::NONE => 'Відсутні / Чохол не входить',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
