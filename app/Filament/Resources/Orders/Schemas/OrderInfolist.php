<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Основна інформація')
                    ->description('Деталі та поточний стан')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        TextEntry::make('number')
                            ->label('№ Замовлення')
                            ->icon('heroicon-o-hashtag')
                            ->copyable()
                            ->weight('bold'),

                        TextEntry::make('created_at')
                            ->label('Дата та час')
                            ->icon('heroicon-o-calendar')
                            ->dateTime('d.m.Y H:i'),

                        TextEntry::make('status')
                            ->label('Статус')
                            ->badge(),
                    ]),

                // БЛОК 3: Фінанси та коментарі
                Section::make()
                    ->schema([
                        TextEntry::make('total_price')
                            ->label('Загальна вартість до сплати')
                            ->money('UAH') // Вкажіть вашу валюту
                            // ->size(TextEntry\TextEntrySize::Large)
                            ->weight('bold')
                            ->color('success'),

                        TextEntry::make('comment')
                            ->label('Коментар клієнта')
                            ->icon('heroicon-o-chat-bubble-left-right')
                            ->placeholder('Без коментаря'),
                    ]),

                // БЛОК 2: Дані клієнта та Доставка
                Grid::make(2)
                    ->schema([
                        Section::make('Покупець')
                            ->icon('heroicon-o-user')
                            ->schema([
                                TextEntry::make('name')
                                    ->label('ПІБ замовника'),
                                TextEntry::make('phone')
                                    ->label('Номер телефону')
                                    ->icon('heroicon-o-phone')
                                    ->copyable(),
                                TextEntry::make('email')
                                    ->label('Електронна пошта')
                                    ->icon('heroicon-o-envelope')
                                    ->placeholder('-')
                                    ->copyable(),
                            ]),

                        Section::make('Доставка')
                            ->icon('heroicon-o-truck')
                            ->schema([
                                TextEntry::make('delivery_method')
                                    ->label('Спосіб'),
                                TextEntry::make('city')
                                    ->label('Місто'),
                                TextEntry::make('address')
                                    ->label('Адреса / Відділення')
                                    ->placeholder('-'),
                            ]),
                    ])->columnSpanFull(),

            ]);
    }
}
