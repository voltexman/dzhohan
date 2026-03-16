<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SteelType: string implements HasLabel
{
    case AEBL = 'aebl';
    case N690CO = 'n690co';
    case M390 = 'm390';
    case ELMAX = 'elmax';
    case MAGNACUT = 'magnacut';
    case M398 = 'm398';
    case S90V = 'cpm_s90v';
    case K390 = 'k390';
    case S390 = 's390';
    case SANMAI = 'sanmai';
    case DAMASCUS = 'damascus';
    case DAMASTEEL = 'damasteel';

    public function getLabel(): string
    {
        return match ($this) {
            self::AEBL => 'AEB-L',
            self::N690CO => 'N690Co',
            self::M390 => 'Böhler M390',
            self::ELMAX => 'Uddeholm Elmax',
            self::MAGNACUT => 'CPM MagnaCut',
            self::M398 => 'Böhler M398',
            self::S90V => 'CPM S90V',
            self::K390 => 'Böhler K390',
            self::S390 => 'Böhler S390',
            self::SANMAI => 'San Mai (ламінат)',
            self::DAMASCUS => 'Damascus (дамаська сталь)',
            self::DAMASTEEL => 'Damasteel',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
