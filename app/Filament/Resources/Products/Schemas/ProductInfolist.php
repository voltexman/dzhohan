<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Product;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                SpatieMediaLibraryImageEntry::make('images')
                    ->collection('products')
                    ->label(false)
                    ->columnSpanFull(),

                TextEntry::make('name')->label('Назва'),

                TextEntry::make('slug'),

                TextEntry::make('sku')
                    ->label('SKU')
                    ->placeholder('-'),

                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull()
                    ->label('Опис'),

                TextEntry::make('price')
                    ->money()
                    ->label('Ціна'),

                TextEntry::make('stock')
                    ->numeric()
                    ->label('Наявність'),

                IconEntry::make('is_active')
                    ->boolean(),

                TextEntry::make('category')
                    ->badge()
                    ->label('Категорія'),

                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Product $record): bool => $record->trashed())
                    ->label('Видалено'),

                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-')
                    ->label('Створено'),

                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-')
                    ->label('Оновлено'),
            ]);
    }
}
