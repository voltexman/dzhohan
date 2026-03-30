<?php

namespace App\Filament\Resources\Materials\Schemas;

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
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class MaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('category')
                    ->default(ProductCategory::MATERIAL)
                    ->dehydrated(),
                Tabs::make('Product Details')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Основне')
                            ->icon('heroicon-m-information-circle')
                            ->schema([
                                Grid::make(2)
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
                                    ]),

                                Grid::make(3)
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

                                RichEditor::make('description')
                                    ->toolbarButtons([
                                        ['bold', 'italic', 'underline', 'strike'],
                                        ['alignStart', 'alignCenter', 'alignEnd'],
                                        ['blockquote', 'bulletList', 'orderedList'],
                                        ['undo', 'redo'],
                                    ])
                                    ->columnSpanFull(),

                                Select::make('tags')
                                    ->relationship('tags', 'name')
                                    ->multiple()
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->rule(['min:1', 'max:6'])
                                    ->label('Теги')
                                    // 🔥 Повідомлення, якщо пошук не дав результатів
                                    ->noSearchResultsMessage('Теги не знайдено. Спробуйте інший запит або створіть новий.')

                                    // 🔥 Повідомлення, якщо список значень взагалі порожній (наприклад, для нового атрибута)
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

                                Toggle::make('is_active')
                                    ->label('Публікація')
                                    ->helperText('Чи буде товар відображатися на сайті')
                                    ->default(true)
                                    ->required(),
                            ]),

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
                                    ->acceptedFileTypes([
                                        'image/*',
                                    ])
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
                                Repeater::make('materialAttributes')
                                    ->relationship('materialAttributes')
                                    ->reorderable('sort')
                                    ->orderColumn('sort')
                                    ->reorderableWithButtons()
                                    ->collapsible()
                                    ->schema([
                                        Select::make('attribute_id')
                                            ->label('Параметр')
                                            // Фільтруємо за групою
                                            ->options(Attribute::where('group', ProductCategory::MATERIAL)->pluck('name', 'id'))
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->live()
                                            ->distinct()
                                            ->noSearchResultsMessage('Параметрів не знайдено.')
                                            ->noOptionsMessage('Параметри для ножів відсутні.')
                                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                            ->afterStateUpdated(fn($set) => $set('attribute_value_id', null))
                                            ->createOptionForm([
                                                TextInput::make('name')
                                                    ->label('Назва параметра')
                                                    ->required(),
                                            ])
                                            ->createOptionUsing(function (array $data) {
                                                $name = $data['name'];
                                                $group = 'material'; // або ProductCategory::MATERIAL
                                                $slug = \Illuminate\Support\Str::slug($name);

                                                // Спершу шукаємо, чи є такий атрибут саме в цій групі
                                                // Це захистить від помилки Duplicate Entry
                                                $attribute = Attribute::where('group', $group)
                                                    ->where('name', $name)
                                                    ->first();

                                                if ($attribute) {
                                                    return $attribute->id;
                                                }

                                                // Якщо немає — створюємо з усіма потрібними полями
                                                return Attribute::create([
                                                    'name' => $name,
                                                    'slug' => $slug, // ОБОВ'ЯЗКОВО додаємо slug
                                                    'group' => $group,
                                                ])->id;
                                            })
                                            // Додаємо це, щоб відображалася назва, а не id
                                            ->getOptionLabelUsing(fn($value) => Attribute::find($value)?->name),

                                        Select::make('attribute_value_id')
                                            ->label('Значення')
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->noSearchResultsMessage('Значень не знайдено. Спробуйте інший запит або створіть нове.')
                                            ->noOptionsMessage('Для цього параметра ще не створено жодного значення.')
                                            ->options(fn($get) => AttributeValue::where('attribute_id', $get('attribute_id'))->pluck('value', 'id'))
                                            ->disabled(fn($get) => ! $get('attribute_id'))
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
                                            })
                                            // Додаємо це, щоб відображалося значення, а не id
                                            ->getOptionLabelUsing(fn($value) => AttributeValue::find($value)?->value),
                                    ])
                                    ->columns(2)
                                    ->defaultItems(0)
                                    ->addActionLabel('Додати характеристику')
                                    ->itemLabel(
                                        fn(array $state): ?string => Attribute::find($state['attribute_id'] ?? null)?->name ?? 'Нова характеристика'
                                    )->hiddenLabel(true),
                            ]),
                    ]),
            ]);
    }
}
