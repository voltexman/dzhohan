<?php

namespace App\Filament\Resources\Subscribers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SubscribersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('email')
                    ->icon(Heroicon::OutlinedEnvelope)
                    ->label('Поштова адреса')
                    ->searchable(),

                TextColumn::make('name')
                    ->icon(Heroicon::OutlinedUser)
                    ->label('Ім`я')
                    ->placeholder('Відсутнє')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->icon(Heroicon::OutlinedCalendarDays)
                    ->label('Дата/час')
                    ->date('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->label(false),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
