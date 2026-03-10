<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('images')
                    ->collection('products')
                    ->conversion('preview')
                    ->square()
                    ->limit(1)
                    ->circular()
                    ->label(false),

                TextColumn::make('name')
                    ->searchable()
                    ->weight(FontWeight::SemiBold)
                    ->description(fn ($record) => $record->sku)
                    ->label('Товар'),

                TextColumn::make('price')
                    ->money()
                    ->sortable()
                    ->label('Ціна'),

                IconColumn::make('stock')
                    ->label('Наявність')
                    ->color(fn ($state) => $state > 0 ? 'success' : 'gray')
                    ->icon(fn ($state) => $state > 0 ? 'heroicon-o-check-circle' : 'heroicon-o-minus-circle')
                    ->tooltip(fn ($state) => match (true) {
                        $state > 1 => "В наявності: {$state}",
                        $state === 1 => 'В наявності',
                        default => 'Проданий',
                    })
                    ->alignCenter()
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label('Видимість')
                    ->alignCenter(),

                TextColumn::make('collection')
                    ->badge()
                    ->searchable()
                    ->label('Колекція'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->striped()
            ->filters([
                TrashedFilter::make(),
                TernaryFilter::make('stock')
                    ->label('В наявності')
                    ->queries(
                        true: fn ($q) => $q->where('stock', '>', 0),
                        false: fn ($q) => $q->where('stock', 0),
                    ),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
