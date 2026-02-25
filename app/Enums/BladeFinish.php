<?php

namespace App\Enums;

enum BladeFinish: string
{
    // Візуальні фініші (Поверхня)
    case SATIN = 'satin';
    case STONEWASH = 'stonewash';
    case BLACK_STONEWASH = 'black_stonewash';
    case BEAD_BLAST = 'bead_blast';
    case MIRROR_POLISH = 'mirror_polish';
    case BLACK_OXIDE = 'black_oxide';
    case DLC = 'dlc_coating'; // Diamond Like Carbon (преміум покриття)
    case CERAKOTE = 'cerakote'; // Керамічне покриття

    public function label(): string
    {
        return match ($this) {
            self::SATIN => 'Сатин (Satin Finish)',
            self::STONEWASH => 'Стоунвош (Stonewash)',
            self::BLACK_STONEWASH => 'Чорний стоунвош (Black Stonewash)',
            self::BEAD_BLAST => 'Піскоструйна обробка (Bead Blast)',
            self::MIRROR_POLISH => 'Дзеркальне полірування',
            self::BLACK_OXIDE => 'Чорне оксидування (Вороніння)',
            self::DLC => 'DLC (Алмазоподібне)',
            self::CERAKOTE => 'Cerakote (Кераміка)',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
