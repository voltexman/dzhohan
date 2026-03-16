<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Enums\Order\OrderStatus;
use App\Enums\Order\OrderType;
use App\Models\Order;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->label('Замовлення')
                    ->weight('bold')
                    ->color('warning')
                    ->copyable()
                    ->copyMessage('Номер скопійовано')
                    ->searchable(),

                TextColumn::make('name')
                    ->label('Замовник')
                    ->state(fn (Order $record) => "{$record->first_name} {$record->last_name}")
                    ->description(fn (Order $record) => $record->phone)
                    ->weight(FontWeight::SemiBold)
                    ->searchable(['first_name', 'last_name', 'phone']),

                TextColumn::make('total_price')
                    ->label('Ціна')
                    ->state(
                        fn ($record) => $record->type === OrderType::Purchase
                            ? $record->products->sum(fn ($i) => $i->qty * $i->price)
                            : null
                    )
                    ->placeholder('Договірна')
                    ->money('UAH')
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Тип')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Дата/час')
                    ->date('d.m.Y')
                    ->description(fn (Order $record) => $record->created_at->format('H:i'))
                    ->weight(FontWeight::Medium)
                    ->sortable()
                    ->toggleable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->poll('15s')
            ->striped()
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn ($query) => $query->with('products'))
            ->filters([
                SelectFilter::make('status')
                    ->options(OrderStatus::class)
                    ->native(false)
                    ->label('Статус'),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label(false)
                    ->tooltip('Переглянути'),

                EditAction::make()
                    ->label(false)
                    ->tooltip('Редагувати'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
