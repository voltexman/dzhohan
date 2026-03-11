<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\Order\OrderStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('first_name')
                    ->label('Ім`я')
                    ->required(),

                TextInput::make('last_name')
                    ->label('Прізвище')
                    // Обов'язкове, якщо доставка — Нова або Укрпошта
                    ->required(fn($get) => in_array($get('delivery_method'), ['nova_poshta', 'ukr_poshta'])),

                TextInput::make('phone')
                    ->tel()
                    ->mask('+999 (99) 999-99-99')
                    ->telRegex('/^\+\d{3}\s\(\d{2}\)\s\d{3}-\d{2}-\d{2}$/')
                    ->label('Телефон')
                    ->required(),

                TextInput::make('email')
                    ->label('Поштова адреса')
                    ->email()
                    ->required(fn($get) => in_array($get('delivery_method'), ['nova_poshta', 'ukr_poshta'])),

                Grid::make(3)
                    ->schema([
                        TextInput::make('delivery_method')
                            ->label('Метод доставки')
                            ->live()
                            ->required(),

                        TextInput::make('city')
                            ->label('Місто')
                            // Обов'язкове для всіх, крім самовивозу (pickup)
                            ->required(fn($get) => $get('delivery_method') !== 'pickup'),

                        TextInput::make('address')
                            ->label('Адреса/відділення пошти')
                            // Обов'язкове для всіх, крім самовивозу (pickup)
                            ->required(fn($get) => $get('delivery_method') !== 'pickup'),
                    ])->columnSpanFull(),

                Textarea::make('comment')
                    ->rows(4)
                    ->label('Коментар')
                    ->columnSpanFull(),

                Select::make('status')
                    ->label('Статус')
                    ->options(OrderStatus::class)
                    ->default('pending')
                    ->native(false)
                    ->required(),
            ]);
    }
}
