<?php

namespace App\Enums;

enum PostType: string
{
    case REVIEW = 'review';
    case ARTICLE = 'article';

    public function label(): string
    {
        return match ($this) {
            self::REVIEW => 'Огляд',
            self::ARTICLE => 'Стаття',
        };
    }

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
