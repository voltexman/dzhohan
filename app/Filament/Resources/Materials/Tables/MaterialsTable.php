<?php

namespace App\Filament\Resources\Materials\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class MaterialsTable
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
                    ->width('1%')
                    ->label(false),

                TextColumn::make('name')
                    ->searchable()
                    ->weight(FontWeight::SemiBold)
                    ->description(fn($record) => $record->sku)
                    ->label('Товар'),

                TextColumn::make('price')
                    ->money()
                    ->sortable()
                    ->label('Ціна')
                    ->formatStateUsing(function ($record, $state) {
                        return $record->currency->format($state);
                    }),

                TextColumn::make('quantity')
                    ->label('Кількість')
                    ->alignCenter()
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label('Видимість')
                    ->alignCenter(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
