<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Enums\ProductCategory;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([


                Tabs::make('Product Details')
                    ->columnSpanFull()
                    ->tabs([
                        // Перша вкладка: Основна інформація
                        Tab::make('Основне')
                            ->icon('heroicon-m-information-circle')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Назва товару')
                                    ->required(),

                                TextInput::make('slug')
                                    ->required(),

                                Textarea::make('description')
                                    ->rows(6)
                                    ->extraAttributes(['class' => 'resize-none'])
                                    ->label('Опис та особливості')
                                    ->columnSpanFull(),

                                Select::make('tags')
                                    ->relationship('tags', 'name') // 'tags' - назва методу в моделі, 'name' - поле з таблиці tags
                                    ->multiple()                   // Дозволяє обирати кілька тегів
                                    ->searchable()                 // Пошук по існуючих
                                    ->preload()                    // Завантажити список одразу (якщо тегів не тисячі)
                                    ->createOptionForm([           // Дозволяє створити новий тег прямо з форми товару
                                        \Filament\Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn($set, $state) => $set('slug', \Illuminate\Support\Str::slug($state))),
                                        \Filament\Forms\Components\TextInput::make('slug')
                                            ->required()
                                            ->unique('tags', 'slug'),
                                    ]),

                                TextInput::make('price')
                                    ->required()
                                    ->numeric()
                                    ->label('Ціна')
                                    ->prefix('$'),

                                TextInput::make('quantity')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->default(0)
                                    ->label('Кількість'),

                                Toggle::make('is_active')
                                    ->required(),

                                Select::make('collection')
                                    ->options(ProductCategory::class)
                                    ->native(false)
                                    ->label('Колекція')
                                    ->required(),
                            ]),

                        // Друга вкладка: Медіа
                        Tab::make('Зображення')
                            ->icon('heroicon-m-photo')
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('images')
                                    ->collection('products')
                                    ->imageEditor()
                                    ->reorderable()
                                    ->disk('public')
                                    ->visibility('public')
                                    ->panelLayout('grid')
                                    ->imagePreviewHeight('250')
                                    ->multiple()
                                    ->hiddenLabel()
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
