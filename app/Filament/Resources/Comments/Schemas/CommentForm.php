<?php

namespace App\Filament\Resources\Comments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CommentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('commentable_type')
                    ->required(),
                TextInput::make('commentable_id')
                    ->required()
                    ->numeric(),
                TextInput::make('author_name')
                    ->required(),
                TextInput::make('ip_address'),
                Select::make('parent_id')
                    ->relationship('parent', 'id'),
                Textarea::make('body')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('rating')
                    ->numeric(),
                Toggle::make('is_approved')
                    ->required(),
            ]);
    }
}
