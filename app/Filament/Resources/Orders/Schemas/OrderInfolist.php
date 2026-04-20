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
                            ->state(function ($record) {
                                if ($record->type !== OrderType::Purchase) {
                                    return null;
                                }

                                $totals = $record->products->groupBy('currency');

                                if ($totals->count() === 1) {
                                    $group = $totals->first();
                                    $currency = $group->first()->currency;
                                    $sum = $group->sum(fn ($i) => $i->qty * $i->price);

                                    return $currency->format($sum);
                                }

                                return 'Мультивалютна';
                            })
                            ->placeholder('Договірна')
                            ->color(fn ($state) => $state === 'Мультивалютна' ? 'warning' : 'success')
                            ->weight(FontWeight::Bold)
                            ->size(TextSize::Large),

                        TextEntry::make('created_at')
                            ->label('Дата замовлення')
                            ->dateTime('d.m.Y H:i')
                            ->weight(FontWeight::Bold),
                    ])
                    ->columns(5)
                    ->columnSpanFull(),

                // === НОВА СЕКЦІЯ ДЛЯ ІНДИВІДУАЛЬНОГО ВИГОТОВЛЕННЯ ===
                Section::make('Параметри виготовлення ножа')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->description('Деталі індивідуального замовлення')
                    ->visible(fn ($record) => $record->type->value === 'manufacturing')
                    ->schema([
                        TextEntry::make('manufacture.knife_type')
                            ->label('Тип ножа')
                            ->placeholder('не вказано')
                            ->columnSpanFull(),

                        Grid::make(3)->schema([
                            // Клинок
                            Group::make([
                                TextEntry::make('manufacture.blade_shape')
                                    ->label('Форма клинка')
                                    ->placeholder('не вказано'),

                                TextEntry::make('manufacture.blade_steel')
                                    ->label('Марка сталі')
                                    ->placeholder('не вказано'),

                                TextEntry::make('manufacture.blade_length')
                                    ->label('Довжина клинка (мм)')
                                    ->placeholder('не вказано'),

                                TextEntry::make('manufacture.blade_thickness')
                                    ->label('Товщина клинка (мм)')
                                    ->placeholder('не вказано'),

                                TextEntry::make('manufacture.blade_grind')
                                    ->label('Заточка')
                                    ->placeholder('не вказано'),

                                TextEntry::make('manufacture.blade_finish')
                                    ->label('Фініш')
                                    ->placeholder('не вказано'),
                            ])->columnSpan(1),

                            // Руків’я
                            Group::make([
                                TextEntry::make('manufacture.handle_material')
                                    ->label('Матеріал руків’я')
                                    ->placeholder('не вказано'),

                                TextEntry::make('manufacture.handle_color')
                                    ->label('Колір руків’я')
                                    ->placeholder('не вказано'),
                            ])->columnSpan(1),

                            // Піхви + Гравіювання
                            Group::make([
                                TextEntry::make('manufacture.sheath')
                                    ->formatStateUsing(fn (bool $state) => $state ? 'Так' : 'Ні')
                                    ->color(fn (bool $state) => $state ? 'success' : 'gray')
                                    ->label('Піхви')
                                    ->badge(),

                                TextEntry::make('manufacture.engraving')
                                    ->formatStateUsing(fn ($state) => $state ? 'Так' : 'Ні')
                                    ->color(fn ($state) => $state ? 'success' : 'gray')
                                    ->label('Гравіювання')
                                    ->badge(),

                                TextEntry::make('manufacture.engraving_text')
                                    ->label('Текст гравіювання')
                                    ->placeholder('не вказано')
                                    ->columnSpanFull(),
                            ])->columnSpan(1),
                        ]),

                        TextEntry::make('manufacture.notes')
                            ->label('Додаткові зауваження')
                            ->placeholder('немає')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull()
                    ->collapsible(),

                // Решта секцій залишаються без змін
                Group::make([
                    Section::make('Клієнт')
                        ->icon('heroicon-o-user')
                        ->schema([
                            TextEntry::make('first_name')
                                ->label('Замовник')
                                ->formatStateUsing(fn ($record) => "{$record->first_name} {$record?->last_name}"),

                            TextEntry::make('phone')
                                ->label('Телефон')
                                ->copyable(),

                            TextEntry::make('email')
                                ->label('Email')
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
                                ->hidden(fn ($state) => blank($state)),

                            TextEntry::make('address')
                                ->label('Адреса / Відділення')
                                ->hidden(fn ($state) => blank($state))
                                ->columnSpanFull(),
                        ])->columns(2),
                ])->columns(2)->columnSpanFull(),

                Section::make('Коментар клієнта')
                    ->icon('heroicon-o-chat-bubble-bottom-center-text')
                    ->schema([
                        TextEntry::make('comment')
                            ->hiddenLabel()
                            ->placeholder('не вказано'),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
