<?php

namespace App\Enums;

enum PostType: string
{
    case ARTICLE = 'article';
    case NEWS = 'news';

    public function label(): string
    {
        return match ($this) {
            self::ARTICLE => 'Стаття',
            self::NEWS => 'Новина',
        };
    }

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
