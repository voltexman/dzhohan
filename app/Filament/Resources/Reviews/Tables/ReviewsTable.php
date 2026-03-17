<?php

namespace App\Filament\Resources\Reviews\Tables;

use App\Models\Review;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->label('Товар')
                    ->searchable()
                    ->weight('bold')
                    ->color('primary'),

                // 2. АВТОР ТА КОНТАКТ
                TextColumn::make('name')
                    ->label('Автор')
                    ->default('Анонім')
                    ->description(fn (Review $record): string => $record->contact ?? 'Контакт не вказано')
                    ->searchable(),

                // 3. РЕЙТИНГ (зірочки)
                TextColumn::make('rating')
                    ->label('Оцінка')
                    ->alignCenter()
                    ->formatStateUsing(fn (int $state): string => str_repeat('★', $state).str_repeat('☆', 5 - $state))
                    ->color('warning')
                    ->extraAttributes(['class' => 'text-lg font-serif'])
                    ->sortable(),

                // 4. ТЕКСТ ВІДГУКУ
                TextColumn::make('text')
                    ->label('Відгук')
                    ->wrap()
                    ->lineClamp(2)
                    ->tooltip(fn (Review $record): string => $record->text)
                    ->searchable(),

                // 5. МОДЕРАЦІЯ (Швидке перемикання)
                ToggleColumn::make('is_selected')
                    ->label('Обраний')
                    ->alignCenter(),

                // 6. ДАТА
                TextColumn::make('created_at')
                    ->label('Дата')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
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
