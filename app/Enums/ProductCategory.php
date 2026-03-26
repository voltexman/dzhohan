<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ProductCategory: string implements HasLabel
{
    case KNIFE = 'knife';
    case MATERIAL = 'material';

    public function getLabel(): string
    {
        return match ($this) {
            self::KNIFE => 'Ножі',
            self::MATERIAL => 'Матеріали',
        };
    }

    public function url(?string $collection = null): string
    {
        return match ($this) {
            self::KNIFE => $collection
                ? route('knives.collection', ['collection' => $collection])
                : route('knives'),
            self::MATERIAL => route('materials'),
        };
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
