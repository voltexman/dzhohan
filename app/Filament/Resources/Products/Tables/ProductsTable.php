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
                    ->label(false),

                TextColumn::make('name')
                    ->searchable()
                    ->weight(FontWeight::Medium)
                    ->label('Назва'),

                TextColumn::make('sku')
                    ->label('SKU')
                    ->weight(FontWeight::Bold)
                    ->searchable(),

                TextColumn::make('price')
                    ->money()
                    ->sortable()
                    ->label('Ціна'),

                IconColumn::make('stock')
                    ->label('Наявність')
                    ->color(fn($state) => $state > 0 ? 'success' : 'gray')
                    ->icon(fn($state) => $state > 0 ? 'heroicon-o-check-circle' : 'heroicon-o-minus-circle')
                    ->tooltip(fn($state) => match (true) {
                        $state > 1 => "В наявності: {$state}",
                        $state === 1 => 'В наявності',
                        default => 'Проданий',
                    })
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Видимість')
                    ->icon(fn($state): string => $state ? 'heroicon-o-eye' : 'heroicon-o-eye-slash')
                    ->color(fn($state): string => $state ? 'success' : 'gray')
                    ->alignCenter(),

                TextColumn::make('category')
                    ->badge()
                    ->searchable()
                    ->label('Категорія'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
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
