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
                    ->required(),

                TextInput::make('phone')
                    ->tel()
                    ->label('Телефон')
                    ->required(),

                TextInput::make('email')
                    ->label('Поштова адреса')
                    ->email()
                    ->required(),

                Grid::make(3)
                    ->schema([
                        TextInput::make('delivery_method')
                            ->label('Метод доставки')
                            ->required(),

                        TextInput::make('city')
                            ->label('Місто')
                            ->required(),

                        TextInput::make('address')
                            ->label('Адреса/відділення пошти')
                            ->required(),
                    ])->columnSpanFull(),

                Textarea::make('comment')
                    ->rows(4)
                    ->label('Коментар')
                    ->columnSpanFull(),

                Select::make('status')
                    ->label('Статус')
                    ->options(OrderStatus::class)
                    ->default('pending')
                    ->required(),
            ]);
    }
}
