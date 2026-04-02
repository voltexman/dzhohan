<?php

namespace App\Filament\Resources\Attributes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AttributesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->columns([
                TextColumn::make('name')
                    ->weight('bold')
                    ->label('Параметр'),

                TextColumn::make('values.value')
                    ->label('Доступні значення')
                    ->badge()
                    ->separator(',')
                    ->color('gray')
                    ->wrap()
                    ->expandableLimitedList()
                    ->placeholder('Значень поки немає')
                    ->extraAttributes([
                        'style' => 'max-width: 500px;',
                    ]),

            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->slideOver()
                    ->modalWidth('xl')
                    ->closeModalByClickingAway(false)
                    ->modalHeading(fn($record) => "Редагування: {$record->name}"),

                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
