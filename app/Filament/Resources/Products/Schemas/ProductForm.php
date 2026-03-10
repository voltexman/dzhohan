<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Enums\BladeGrind;
use App\Enums\BladeShape;
use App\Enums\ProductCategory;
use App\Enums\SteelType;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Tabs::make('Product Details')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Основне')
                            ->icon('heroicon-m-information-circle')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Назва товару')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn($set, $state) => $set('slug', Str::slug($state))),

                                TextInput::make('slug')
                                    ->label('URL адреса (slug)')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->disabled(fn($get) => ! $get('is_slug_editable'))
                                    ->dehydrated()
                                    // ->helperText('Натисніть на замок, щоб змінити вручну')
                                    ->suffixAction(
                                        Action::make('toggleSlugEditable')
                                            ->icon('heroicon-m-lock-closed')
                                            ->color('gray')
                                            ->action(fn($set, $get) => $set('is_slug_editable', ! $get('is_slug_editable')))
                                    ),

                                Grid::make(3)
                                    ->schema([
                                        TextInput::make('price')
                                            ->label('Ціна')
                                            ->numeric()
                                            ->prefix('₴')
                                            ->required(),

                                        TextInput::make('quantity')
                                            ->label('Кількість')
                                            ->numeric()
                                            ->default(1)
                                            ->minValue(0)
                                            ->required(),

                                        TextInput::make('sku')
                                            ->label('Артикул (SKU)')
                                            ->default(fn() => 'KN-' . strtoupper(Str::random(6)))
                                            ->unique(ignoreRecord: true)
                                            ->required()
                                            ->readOnly()
                                            ->suffixAction(
                                                Action::make('generateSku')
                                                    ->icon('heroicon-m-arrow-path')
                                                    ->action(fn($set) => $set('sku', 'KN-' . strtoupper(Str::random(6))))
                                            ),

                                    ])->columnSpanFull(),

                                Textarea::make('description')
                                    ->rows(6)
                                    ->extraAttributes(['class' => 'resize-none'])
                                    ->label('Опис та особливості')
                                    ->columnSpanFull(),

                                Select::make('collection')
                                    ->options(ProductCategory::class)
                                    ->native(false)
                                    ->label('Колекція')
                                    ->required(),

                                Select::make('tags')
                                    ->relationship('tags', 'name')
                                    ->multiple()
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->required()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn($set, $state) => $set('slug', Str::slug($state))),
                                        TextInput::make('slug')
                                            ->required()
                                            ->unique('tags', 'slug'),
                                    ]),

                                Toggle::make('is_active')
                                    ->required(),
                            ])->columns(2),

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

                        Tab::make('Характеристики')
                            ->icon('heroicon-m-list-bullet')
                            ->schema([
                                Section::make('Клинок')
                                    ->icon('heroicon-m-scissors')
                                    ->columns(3)
                                    ->schema([
                                        Select::make('steel')
                                            ->label('Марка сталі')
                                            ->options(SteelType::class)
                                            ->searchable()
                                            ->native(false),

                                        Select::make('blade_shape')
                                            ->label('Профіль клинка')
                                            ->options(BladeShape::class)
                                            ->native(false),

                                        Select::make('blade_grind')
                                            ->label('Тип спусків')
                                            ->options(BladeGrind::class)
                                            ->native(false),

                                        TextInput::make('blade_length')
                                            ->label('Довжина леза')
                                            ->numeric()
                                            ->suffix('мм'),

                                        TextInput::make('blade_thickness')
                                            ->label('Товщина леза')
                                            ->numeric()
                                            ->suffix('мм'),

                                        Select::make('blade_finish')
                                            ->label('Покриття')
                                            ->options(\App\Enums\BladeFinish::class)
                                            ->native(false),
                                    ]),

                                Grid::make(3)
                                    ->schema([
                                        TextInput::make('total_length')
                                            ->label('Загальна довжина')
                                            ->numeric()
                                            ->suffix('мм'),

                                        Select::make('handle_material')
                                            ->label('Матеріал руків’я')
                                            ->options(\App\Enums\HandleMaterial::class)
                                            ->native(false),

                                        Select::make('sheath')
                                            ->label('Піхви / Чохол')
                                            ->options(\App\Enums\SheathType::class)
                                            ->native(false),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
