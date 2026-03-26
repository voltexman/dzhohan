<?php

namespace App\Filament\Resources\Attributes\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class AttributeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Назва параметра')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($set, $state) => $set('slug', Str::slug($state))),

                TextInput::make('slug')->required(),

                Repeater::make('values')
                    ->relationship('values')
                    ->label('Список значень')
                    ->schema([
                        TextInput::make('value')
                            ->label('Значення')
                            ->required()
                            ->placeholder('напр. M390 або Чорний'),
                    ])
                    ->defaultItems(0)
                    ->addActionLabel('Додати нове значення')
                    ->columns(1)
                    ->grid(1)
                    ->collapsible()
                    ->columnSpanFull(),
            ]);
    }
}
