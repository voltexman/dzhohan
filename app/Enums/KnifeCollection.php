<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum KnifeCollection: string implements HasLabel
{
    case TACTICAL = 'tactical';
    case KITCHEN = 'kitchen';
    case HUNTING = 'hunting';
    case EDC = 'everyday';
    case OUTDOOR = 'outdoor';

    public function getLabel(): string
    {
        return match ($this) {
            self::TACTICAL => 'Тактичні ножі',
            self::KITCHEN => 'Кухонні ножі',
            self::HUNTING => 'Для полювання',
            self::EDC => 'На кожен день',
            self::OUTDOOR => 'Для походів',
        };
    }

    public function getShortLabel(): string
    {
        return match ($this) {
            self::TACTICAL => 'Тактичні',
            self::KITCHEN => 'Кухонні',
            self::HUNTING => 'Мисливські',
            self::EDC => 'Щоденні',
            self::OUTDOOR => 'Туристичні',
        };
    }

    public function images(): string
    {
        return match ($this) {
            self::TACTICAL => 'tactical-category-bg.png',
            self::KITCHEN => 'kitchen-category-bg.png',
            self::HUNTING => 'hunting-category-bg.png',
            self::EDC => 'everyday-category-bg.png',
            self::OUTDOOR => 'outdoor-category-bg.png',
        };
    }

    public function icons(): string
    {
        return match ($this) {
            self::TACTICAL => 'tactical-icon.png',
            self::KITCHEN => 'kitchen-icon.png',
            self::HUNTING => 'hunting-icon.png',
            self::EDC => 'everyday-icon.png',
            self::OUTDOOR => 'outdoor-icon.png',
        };
    }

    public function title(): string
    {
        return match ($this) {
            self::TACTICAL => 'Тактичні ножі — військові, бойові, тактичне спорядження',
            self::KITCHEN => 'Кухонні ножі — професійні та домашні набори',
            self::HUNTING => 'Мисливські ножі — надійні ножі для полювання',
            self::EDC => 'EDC ножі — ножі на кожен день',
            self::OUTDOOR => 'Туристичні ножі — для походів та кемпінгу',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::TACTICAL => 'Міцні тактичні ножі для військових, виживання та екстремальних умов.',
            self::KITCHEN => 'Кухонні ножі для шефів та дому. Висока якість сталі та ергономіка.',
            self::HUNTING => 'Ножі для полювання, обробки дичини та роботи в польових умовах.',
            self::EDC => 'Компактні складані ножі для щоденного носіння.',
            self::OUTDOOR => 'Надійні ножі для туризму, кемпінгу та активного відпочинку.',
        };
    }

    public function url(): string
    {
        return route('knives.collection', ['collection' => $this->value]);
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->getLabel()])
            ->toArray();
    }

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
