<?php

namespace App\Enums;

use BackedEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum OrderStatus: string implements HasColor, HasLabel, HasIcon
{
    case Pending = 'pending';       // Очікує підтвердження
    case Processing = 'processing'; // В обробці
    case Manufacturing = 'manufacturing'; // Безпосередньо в роботі у майстра
    case Shipped = 'shipped';       // Відправлено
    case Completed = 'completed';   // Виконано
    case Cancelled = 'cancelled';   // Скасовано

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Pending => 'Нове',
            self::Processing => 'В обробці',
            self::Manufacturing => 'У виробництві',
            self::Shipped => 'Відправлено',
            self::Completed => 'Виконано',
            self::Cancelled => 'Скасовано',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            // Очікує підтвердження — сірий або нейтральний
            self::Pending => 'danger',

            // В обробці — синій (інформаційний)
            self::Processing => 'info',

            // Виготовляється — помаранчевий або жовтий (триває робота)
            self::Manufacturing => 'warning',

            // Відправлено — фіолетовий або блакитний
            self::Shipped => 'primary',

            // Виконано — зелений (успіх)
            self::Completed => 'success',

            // Скасовано — червоний (помилка/відмова)
            self::Cancelled => 'gray',
        };
    }

    public function getIcon(): string | BackedEnum | Htmlable | null
    {
        return match ($this) {
            // Очікує — годинник (пауза)
            self::Pending => 'heroicon-m-clock',

            // В обробці — іконка оновлення або шестерня
            self::Processing => 'heroicon-m-arrow-path',

            // Виготовляється — молоток або інструменти
            self::Manufacturing => 'heroicon-m-wrench-screwdriver',

            // Відправлено — вантажівка або літак
            self::Shipped => 'heroicon-m-truck',

            // Виконано — галочка (успіх)
            self::Completed => 'heroicon-m-check-badge',

            // Скасовано — хрестик (скасування)
            self::Cancelled => 'heroicon-m-x-circle',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
