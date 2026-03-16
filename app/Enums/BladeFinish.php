<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum BladeFinish: string implements HasLabel
{
    case SATIN = 'satin';
    case STONEWASH = 'stonewash';
    case BLACK_OXIDE = 'black_oxide';
    case PVD = 'pvd';
    case CERAKOTE = 'cerakote';
    case BEAD_BLAST = 'bead_blast';
    case DAMASCUS = 'damascus';
    case LAMINATED = 'laminated';

    public function getLabel(): string
    {
        return match ($this) {
            self::SATIN => 'Satin',
            self::STONEWASH => 'Stonewash',
            self::BLACK_OXIDE => 'Black Oxide',
            self::PVD => 'PVD',
            self::CERAKOTE => 'Cerakote',
            self::BEAD_BLAST => 'Bead Blast',
            self::DAMASCUS => 'Damascus',
            self::LAMINATED => 'Laminated Steel',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
