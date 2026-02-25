<?php

namespace App\Enums;

enum SteelType: string
{
    // Бюджетні та середні
    case S440C = '440c';
    case AUS8 = 'aus8';
    case H8CR13 = '8cr13mov';
    case S14C28N = '14c28n';

    // Інструментальні та високовуглецеві
    case D2 = 'd2';
    case X12MF = 'x12mf';
    case H65G = '65g';
    case U8 = 'u8';

    // Високотехнологічні (Middle-High)
    case N690 = 'n690';
    case VG10 = 'vg10';
    case S154CM = '154cm';

    // Преміальні порошкові (Super Steels)
    case M390 = 'm390';
    case S30V = 's30v';
    case S35VN = 's35vn';
    case S45VN = 's45vn';
    case ELMAX = 'elmax';
    case MAGNACUT = 'magnacut'; // Хіт №1 у світі зараз
    case M398 = 'm398';
    case S90V = 's90v';

    public function label(): string
    {
        return match ($this) {
            self::S440C => '440C',
            self::AUS8 => 'AUS-8',
            self::H8CR13 => '8Cr13MoV',
            self::S14C28N => 'Sandvik 14C28N',
            self::D2 => 'D2 (Інструментальна)',
            self::X12MF => 'Х12МФ (Кована)',
            self::H65G => '65Г (Ресорна)',
            self::U8 => 'У8 (Інструментальна)',
            self::N690 => 'Bohler N690',
            self::VG10 => 'VG-10',
            self::S154CM => '154CM',
            self::M390 => 'Bohler M390',
            self::S30V => 'CPM S30V',
            self::S35VN => 'CPM S35VN',
            self::S45VN => 'CPM S45VN',
            self::ELMAX => 'Uddeholm Elmax',
            self::MAGNACUT => 'CPM MagnaCut',
            self::M398 => 'Bohler M398',
            self::S90V => 'CPM S90V',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
