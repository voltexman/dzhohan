<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Enums\BladeFinish;
use App\Enums\BladeGrind;
use App\Enums\BladeShape;
use App\Enums\HandleMaterial;
use App\Enums\ProductCategory;
use App\Enums\SheathType;
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
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn($set, $state) => $set('slug', Str::slug($state)))
                                    ->validationMessages([
                                        'required' => 'Будь ласка, введіть назву ножа',
                                        'unique' => 'Ніж з такою назвою вже існує',
                                    ]),

                                TextInput::make('slug')
                                    ->label('URL адреса (slug)')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->disabled(fn($get) => ! $get('is_slug_editable'))
                                    ->dehydrated()
                                    ->suffixAction(
                                        Action::make('toggleSlugEditable')
                                            ->icon('heroicon-m-lock-closed')
                                            ->color('gray')
                                            ->action(fn($set, $get) => $set('is_slug_editable', ! $get('is_slug_editable')))
                                    )
                                    ->validationMessages([
                                        'required' => 'Будь ласка, вкажіть url адресу',
                                        'unique' => 'Url адреса має бути унікальною',
                                    ]),

                                Grid::make(3)
                                    ->schema([
                                        TextInput::make('price')
                                            ->label('Ціна')
                                            // ->numeric()
                                            ->minValue(1)
                                            ->maxValue(500000)
                                            ->step(1)
                                            ->prefix('₴')
                                            ->required()
                                            ->extraInputAttributes(['min' => 1])
                                            ->rule(['required', 'min:1', 'max:500000'])
                                            ->validationMessages([
                                                'required' => 'Вкажіть ціну ножа',
                                                'min' => 'Не має бути менше 1',
                                                'max' => 'Не має бути більше 500000',
                                            ]),

                                        TextInput::make('quantity')
                                            ->label('Кількість')
                                            ->numeric()
                                            ->default(1)
                                            ->minValue(0)
                                            ->required()
                                            ->extraInputAttributes(['min' => 0])
                                            ->rule(['required', 'min:0', 'max:100'])
                                            ->validationMessages([
                                                'required' => 'Вкажіть кількість ножів',
                                                'min' => 'Не має бути менше 0',
                                                'max' => 'Не має бути більше 100',
                                            ]),

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
                                    ->required()
                                    ->rule(['required'])
                                    ->validationMessages([
                                        'required' => 'Вкажіть колекцію до якої належить ніж',
                                    ]),

                                Select::make('tags')
                                    ->relationship('tags', 'name')
                                    ->multiple()
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->rule(['min:1', 'max:6'])
                                    ->validationMessages([
                                        'required' => 'Вкажіть теги',
                                        'min' => 'Необхідно мінімум 1 теги',
                                        'max' => 'Не більше 6 тегів',
                                    ])
                                    ->label('Теги')
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->required()
                                            ->live()
                                            ->afterStateUpdated(fn($set, $state) => $set('slug', Str::slug($state))),
                                        TextInput::make('slug')
                                            ->required()
                                            ->unique('tags', 'slug'),
                                    ]),

                                Toggle::make('is_active')
                                    ->label('Публікація')
                                    ->helperText('Чи буде товар відображатися на сайті')
                                    ->default(true)
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
                                    ->required()
                                    ->image()
                                    ->minFiles(2)
                                    ->maxFiles(20)
                                    ->acceptedFileTypes([
                                        'image/*',
                                    ])
                                    ->validationMessages([
                                        'required' => 'Додайте зображення',
                                        'min' => 'Не має бути менше 1',
                                        'max' => 'Може бути не більше 20 зображень',
                                        'image' => 'Може бути тільки зображення',
                                    ])
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
                                            ->minValue(0)
                                            ->step(0.1)
                                            ->suffix('мм'),

                                        TextInput::make('blade_thickness')
                                            ->label('Товщина леза')
                                            ->numeric()
                                            ->minValue(0)
                                            ->step(0.1)
                                            ->placeholder('напр. 4.2')
                                            ->suffix('мм'),

                                        Select::make('blade_finish')
                                            ->label('Покриття')
                                            ->options(BladeFinish::class)
                                            ->native(false),
                                    ]),

                                Grid::make(3)
                                    ->schema([
                                        TextInput::make('total_length')
                                            ->label('Загальна довжина')
                                            ->numeric()
                                            ->minValue(0)
                                            ->maxValue(1000)
                                            ->step(0.1)
                                            ->suffix('мм'),

                                        Select::make('handle_material')
                                            ->label('Матеріал руків’я')
                                            ->options(HandleMaterial::class)
                                            ->native(false),

                                        Select::make('sheath')
                                            ->label('Піхви / Чохол')
                                            ->options(SheathType::class)
                                            ->native(false),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
