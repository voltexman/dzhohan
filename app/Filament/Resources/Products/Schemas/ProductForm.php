<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Enums\CurrencyType;
use App\Enums\KnifeCollection;
use App\Enums\ProductCategory;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
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
                Hidden::make('category')
                    ->default(ProductCategory::KNIFE)
                    ->dehydrated(),
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
                                    ->afterStateUpdated(fn ($set, $state) => $set('slug', Str::slug($state)))
                                    ->validationMessages([
                                        'required' => 'Будь ласка, введіть назву ножа',
                                        'unique' => 'Ніж з такою назвою вже існує',
                                    ]),

                                TextInput::make('slug')
                                    ->label('URL адреса (slug)')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->disabled(fn ($get) => ! $get('is_slug_editable'))
                                    ->dehydrated()
                                    ->suffixAction(
                                        Action::make('toggleSlugEditable')
                                            ->icon('heroicon-m-lock-closed')
                                            ->color('gray')
                                            ->action(fn ($set, $get) => $set('is_slug_editable', ! $get('is_slug_editable')))
                                    )
                                    ->validationMessages([
                                        'required' => 'Будь ласка, вкажіть url адресу',
                                        'unique' => 'Url адреса має бути унікальною',
                                    ]),

                                Grid::make([
                                    'default' => 2,
                                    'sm' => 4,
                                    'lg' => 4,
                                ])
                                    ->schema([
                                        TextInput::make('price')
                                            ->label('Ціна')
                                            ->numeric()
                                            ->minValue(1)
                                            ->maxValue(500000)
                                            ->step(1)
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

                                        Select::make('currency')
                                            ->label('Валюта')
                                            ->options(CurrencyType::class)
                                            ->default(CurrencyType::UAH->value)
                                            ->native(false)
                                            ->selectablePlaceholder(false)
                                            ->prefixIcon('heroicon-m-banknotes')
                                            ->columnSpan([
                                                'sm' => 1,
                                                'lg' => 1,
                                            ]),

                                        TextInput::make('sku')
                                            ->label('Артикул (SKU)')
                                            ->default(fn () => 'KN-'.strtoupper(Str::random(6)))
                                            ->unique(ignoreRecord: true)
                                            ->required()
                                            ->readOnly()
                                            ->suffixAction(
                                                Action::make('generateSku')
                                                    ->icon('heroicon-m-arrow-path')
                                                    ->action(fn ($set) => $set('sku', 'KN-'.strtoupper(Str::random(6))))
                                            )
                                            ->columnSpan([
                                                'sm' => 1,
                                                'lg' => 1,
                                            ]),

                                    ])->columnSpanFull(),

                                RichEditor::make('description')
                                    ->toolbarButtons([
                                        ['bold', 'italic', 'underline', 'strike'],
                                        ['alignStart', 'alignCenter', 'alignEnd'],
                                        ['blockquote', 'bulletList', 'orderedList'],
                                        ['undo', 'redo'],
                                    ])
                                    ->label('Опис ножа')
                                    ->columnSpanFull(),

                                Select::make('collection')
                                    ->options(KnifeCollection::class)
                                    ->native(false)
                                    ->label('Колекція')
                                    ->required()
                                    ->selectablePlaceholder(false)
                                    ->rule(['required'])
                                    ->validationMessages(['required' => 'Вкажіть колекцію до якої належить ніж']),

                                Select::make('tags')
                                    ->relationship('tags', 'name')
                                    ->multiple()
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->rule(['min:1', 'max:6'])
                                    ->label('Теги')
                                    ->noSearchResultsMessage('Теги не знайдено. Спробуйте інший запит або створіть новий.')
                                    ->noOptionsMessage('Теги відсутні. Створіть новий')
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->required()
                                            ->label('Тег')
                                            ->maxLength(255),
                                    ])
                                    ->validationMessages([
                                        'required' => 'Вкажіть теги',
                                        'min' => 'Необхідно мінімум 1 теги',
                                        'max' => 'Не більше 6 тегів',
                                    ]),

                                TextInput::make('short_youtube_video_id')
                                    ->label('YouTube Shorts')
                                    ->placeholder('Посилання на Shorts або ID відео')
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if (! $state) {
                                            return;
                                        }
                                        $regex = "/youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/";
                                        if (preg_match($regex, $state, $matches)) {
                                            $set('short_youtube_video_id', $matches[1]);
                                        }
                                    })
                                    ->prefixIcon('heroicon-m-play')
                                    ->suffixAction(
                                        Action::make('clear')
                                            ->icon('heroicon-m-x-mark')
                                            ->color('gray')
                                            ->tooltip('Очистити поле')
                                            ->action(fn (callable $set) => $set('short_youtube_video_id', null))
                                            ->visible(fn ($state) => filled($state))
                                    )
                                    ->lazy(),

                                TextInput::make('full_youtube_video_id')
                                    ->label('YouTube повне відео')
                                    ->placeholder('Посилання або ID відео')
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if (! $state) {
                                            return;
                                        }
                                        $regex = "/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/";
                                        if (preg_match($regex, $state, $matches)) {
                                            $set('full_youtube_video_id', $matches[1]);
                                        }
                                    })
                                    ->prefixIcon('heroicon-m-play')
                                    ->suffixAction(
                                        Action::make('clear')
                                            ->icon('heroicon-m-x-mark')
                                            ->color('gray')
                                            ->tooltip('Очистити поле')
                                            ->action(fn (callable $set) => $set('full_youtube_video_id', null))
                                            ->visible(fn ($state) => filled($state))
                                    )
                                    ->lazy(),

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
                                    ->minFiles(1)
                                    ->maxFiles(20)
                                    ->acceptedFileTypes(['image/*'])
                                    ->validationMessages([
                                        'required' => 'Зображення обов`язкові',
                                        'min' => 'Не має бути менше 1 зображення',
                                        'max' => 'Може бути не більше 20 зображень',
                                        'image' => 'Може бути тільки зображення',
                                    ])
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('Характеристики')
                            ->icon('heroicon-m-list-bullet')
                            ->schema([
                                Section::make('Технічні параметри')
                                    ->description('Розміри ножа')
                                    ->schema([
                                        TextInput::make('total_length')
                                            ->label('Загальна довжина')
                                            ->numeric()
                                            ->required()
                                            ->minValue(0)
                                            ->step(0.1)
                                            ->suffix('мм')
                                            ->placeholder('напр. 250')
                                            ->validationMessages([
                                                'required' => 'Загальна довжина ножа є обов’язковою.',
                                                'numeric' => 'Будь ласка, введіть число.',
                                                'min' => 'Довжина не може бути меншою за 0.',
                                            ]),

                                        TextInput::make('blade_length')
                                            ->label('Довжина леза')
                                            ->required()
                                            ->numeric()
                                            ->minValue(0)
                                            ->step(0.1)
                                            ->suffix('мм')
                                            ->validationMessages([
                                                'required' => 'Будь ласка, вкажіть довжину леза.',
                                                'numeric' => 'Тут має бути число.',
                                                'min' => 'Довжина не може бути меншою за 0.',
                                            ]),

                                        TextInput::make('blade_thickness')
                                            ->label('Товщина леза')
                                            ->numeric()
                                            ->required()
                                            ->minValue(0)
                                            ->step(0.1)
                                            ->placeholder('напр. 4.2')
                                            ->suffix('мм')
                                            ->validationMessages([
                                                'required' => 'Вкажіть товщину обуху.',
                                                'numeric' => 'Тут має бути число.',
                                                'min' => 'Товщина не може бути від’ємною.',
                                            ]),
                                    ])
                                    ->columns(3),

                                Repeater::make('knifeAttributes')
                                    ->relationship('knifeAttributes')
                                    ->reorderable('sort')
                                    ->orderColumn('sort')
                                    ->reorderableWithButtons()
                                    ->collapsible()
                                    ->schema([
                                        Select::make('attribute_id')
                                            ->label('Параметр')
                                            ->options(Attribute::where('group', ProductCategory::KNIFE)->pluck('name', 'id'))
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->live()
                                            ->distinct()
                                            ->noSearchResultsMessage('Параметрів не знайдено.')
                                            ->noOptionsMessage('Параметри для ножів відсутні.')
                                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                            ->afterStateUpdated(fn ($set) => $set('attribute_value_id', null))
                                            ->createOptionForm([
                                                TextInput::make('name')
                                                    ->label('Назва параметра')
                                                    ->required(),
                                            ])
                                            ->createOptionUsing(function (array $data) {
                                                $slug = Str::slug($data['name']);
                                                $group = ProductCategory::KNIFE;
                                                $existing = Attribute::where('group', $group)
                                                    ->where(function ($query) use ($data, $slug) {
                                                        $query->where('name', $data['name'])
                                                            ->orWhere('slug', $slug);
                                                    })
                                                    ->first();

                                                if ($existing) {
                                                    return $existing->id;
                                                }

                                                return Attribute::create([
                                                    'name' => $data['name'],
                                                    'slug' => $slug,
                                                    'group' => $group,
                                                ])->id;
                                            })
                                            ->getOptionLabelUsing(fn ($value) => Attribute::find($value)?->name),

                                        Select::make('attribute_value_id')
                                            ->label('Значення')
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->noSearchResultsMessage('Значень не знайдено. Спробуйте інший запит або створіть нове.')
                                            ->noOptionsMessage('Для цього параметра ще не створено жодного значення.')
                                            ->options(fn ($get) => AttributeValue::where('attribute_id', $get('attribute_id'))->pluck('value', 'id'))
                                            ->disabled(fn ($get) => ! $get('attribute_id'))
                                            ->createOptionForm([
                                                TextInput::make('value')
                                                    ->label('Нове значення')
                                                    ->required(),
                                            ])
                                            ->createOptionUsing(function (array $data, $get) {
                                                $attributeId = $get('attribute_id');

                                                ! $attributeId && throw new \Exception('Спочатку оберіть параметр');

                                                return AttributeValue::create([
                                                    'attribute_id' => $attributeId,
                                                    'value' => $data['value'],
                                                ])->id;
                                            }),
                                    ])
                                    ->columns(2)
                                    ->defaultItems(0)
                                    ->addActionLabel('Додати характеристику')
                                    ->itemLabel(
                                        fn (array $state): ?string => Attribute::find($state['attribute_id'] ?? null)?->name ?? 'Нова характеристика'
                                    )->label('Характеристики ножа'),
                            ]),
                    ]),
            ]);
    }
}
