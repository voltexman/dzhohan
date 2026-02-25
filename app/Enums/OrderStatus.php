<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';       // Очікує підтвердження
    case Processing = 'processing'; // В обробці
    case Manufacturing = 'manufacturing'; // Безпосередньо в роботі у майстра
    case Shipped = 'shipped';       // Відправлено
    case Completed = 'completed';   // Виконано
    case Cancelled = 'cancelled';   // Скасовано

    // Метод для отримання назви українською
    public function label(): string
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

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'bg-amber-100 text-amber-700',
            self::Processing => 'bg-blue-100 text-blue-700',
            self::Manufacturing => 'bg-yellow-100 text-blue-700',
            self::Shipped => 'bg-purple-100 text-purple-700',
            self::Completed => 'bg-emerald-100 text-emerald-700',
            self::Cancelled => 'bg-red-100 text-red-700',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
