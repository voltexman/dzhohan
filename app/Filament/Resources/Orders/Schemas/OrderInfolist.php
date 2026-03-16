<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\Order\OrderType;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextEntry::make('number')
                            ->label('№ Замовлення')
                            ->weight(FontWeight::Bold)
                            ->size(TextSize::Large)
                            ->copyable()
                            ->color(Color::Amber),

                        TextEntry::make('status')
                            ->label('Статус')
                            ->badge(),

                        TextEntry::make('type')
                            ->label('Тип')
                            ->color('success')
                            ->weight(FontWeight::Medium),

                        TextEntry::make('total_price')
                            ->label('Сума до сплати')
                            // 1. Рахуємо суму лише для покупок
                            ->state(fn ($record) => $record->type === OrderType::Purchase
                                ? $record->products->sum(fn ($i) => $i->qty * $i->price)
                                : null)
                            // 2. Якщо повернувся null (виготовлення) — показуємо плейсхолдер
                            ->placeholder('Договірна')
                            // 3. Форматуємо як гроші (тільки якщо є число)
                            ->money('UAH')
                            // 4. Стилізація: зелений та великий (спрацює лише для суми)
                            ->color('success')
                            ->weight(FontWeight::Bold)
                            ->size(TextSize::Large),

                        TextEntry::make('created_at')
                            ->label('Дата замовлення')
                            ->dateTime('d.m.Y H:i')
                            ->weight(FontWeight::Bold),
                    ])
                    ->columns(5)
                    ->columnSpanFull(),

                Section::make('Параметри виготовлення')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->description('Деталі індивідуального замовлення')
                    ->visible(fn ($record) => $record->type->value === 'manufacturing')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Group::make([
                                    TextEntry::make('custom_options.blade.shape')->label('Форма клинка')->placeholder('не вказано'),
                                    TextEntry::make('custom_options.blade.steel')->label('Марка сталі')->placeholder('не вказано'),
                                    TextEntry::make('custom_options.blade.length')->label('Довжина (мм)')->placeholder('не вказано'),
                                ])->columnSpan(1),

                                Group::make([
                                    TextEntry::make('custom_options.handle.material')->label('Матеріал руків\'я')->placeholder('не вказано'),
                                    TextEntry::make('custom_options.handle.color')->label('Колір')->placeholder('не вказано'),
                                ])->columnSpan(1),

                                Group::make([
                                    TextEntry::make('custom_options.sheath.type')->label('Тип піхов')->placeholder('не вказано'),
                                    TextEntry::make('custom_options.sheath.carry')->label('Спосіб носіння')->placeholder('не вказано'),
                                ])->columnSpan(1),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->collapsible(),

                Group::make([
                    Section::make('Клієнт')
                        ->icon('heroicon-o-user')
                        ->schema([
                            TextEntry::make('first_name')
                                ->label('Замовник')
                                ->icon('heroicon-o-user')
                                ->formatStateUsing(fn ($record) => "{$record->first_name} {$record?->last_name}"),

                            TextEntry::make('phone')
                                ->label('Телефон')
                                ->icon('heroicon-o-phone')
                                ->copyable(),

                            TextEntry::make('email')
                                ->label('Email')
                                ->icon('heroicon-o-envelope')
                                ->hidden(fn ($state) => blank($state))
                                ->copyable()
                                ->columnSpanFull(),
                        ])->columns(2),

                    Section::make('Доставка')
                        ->icon('heroicon-o-truck')
                        ->schema([
                            TextEntry::make('delivery_method')
                                ->label('Спосіб доставки'),

                            TextEntry::make('city')
                                ->label('Місто')
                                ->icon('heroicon-o-map-pin')
                                ->hidden(fn ($state) => blank($state)),

                            TextEntry::make('address')
                                ->label('Адреса / Відділення')
                                ->hidden(fn ($state) => blank($state))
                                ->columnSpanFull(),
                        ])->columns(2),
                ])->columns(2)->columnSpanFull(),

                Group::make([
                    Section::make('Коментар клієнта')
                        ->icon('heroicon-o-chat-bubble-bottom-center-text')
                        ->schema([
                            TextEntry::make('comment')
                                ->hiddenLabel()
                                ->placeholder('не вказано'),
                        ]),
                ])->columnSpanFull(),
            ]);
    }
}
