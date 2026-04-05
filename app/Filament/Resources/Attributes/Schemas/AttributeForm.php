<?php

namespace App\Filament\Resources\Attributes\Schemas;

use App\Enums\ProductCategory;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AttributeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Назва параметра')
                    ->required(),

                Select::make('group')
                    ->label('Група параметра')
                    ->required()
                    ->native(false)
                    ->selectablePlaceholder(false)
                    ->options(ProductCategory::class)
                    ->default(ProductCategory::KNIFE),

                Repeater::make('values')
                    ->relationship('values')
                    ->label('Список значень')
                    ->schema([
                        TextInput::make('value')
                            ->label('Значення')
                            ->required()
                            ->lazy(),
                    ])
                    ->defaultItems(0)
                    ->itemLabel(fn (array $state): ?string => $state['value'] ?? 'Нове значення')
                    ->addActionLabel('Додати нове значення')
                    ->columns(1)
                    ->grid(1)
                    ->collapsible()
                    ->collapsed()
                    ->columnSpanFull(),
            ]);
    }
}
