<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum BladeFinish: string implements HasLabel
{
    case SATIN = 'satin';
    case STONEWASH = 'stonewash';
    case BLACK_STONEWASH = 'black_stonewash';
    case BEAD_BLAST = 'bead_blast';
    case MIRROR_POLISH = 'mirror_polish';
    case BLACK_OXIDE = 'black_oxide';
    case DLC = 'dlc_coating';
    case CERAKOTE = 'cerakote';

    public function getLabel(): string
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
