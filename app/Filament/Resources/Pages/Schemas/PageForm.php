<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Основний контент')
                    ->schema([
                        TextInput::make('title')
                            ->label('Заголовок сторінки')
                            ->required()
                            ->live(onBlur: true)
                            ->live(onBlur: true)
                            ->afterStateUpdated(
                                fn($set, $state, $get) =>
                                // Оновлюємо slug тільки якщо він заблокований (автоматичний режим)
                                !$get('is_slug_editable') ? $set('slug', Str::slug($state)) : null
                            ),

                        TextInput::make('slug')
                            ->label('URL адреса (slug)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            // Поле вимкнене, якщо is_slug_editable = false
                            ->disabled(fn($get) => ! $get('is_slug_editable'))
                            ->dehydrated() // Обов'язково, щоб значення відправлялося в базу
                            ->suffixAction(
                                Action::make('toggleSlugEditable')
                                    ->icon('heroicon-m-lock-closed')
                                    ->color('gray')
                                    // Перемикаємо стан редагування
                                    ->action(fn($set, $get) => $set('is_slug_editable', ! $get('is_slug_editable')))
                            )
                            ->validationMessages([
                                'required' => 'Будь ласка, вкажіть url адресу',
                                'unique' => 'Ця адреса вже зайнята',
                            ]),

                        RichEditor::make('content')
                            ->label('Текст сторінки')
                            ->required()
                            ->fileAttachmentsDirectory('pages-content')
                            ->columnSpanFull()
                            ->fileAttachmentsDirectory('pages-content'),
                    ])->columnSpan(2),

                Section::make('Налаштування')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Опубліковано')
                            ->default(true)
                            ->helperText('Чи буде сторінка доступна на сайті'),

                        TextInput::make('meta_title')
                            ->label('Мета заголовок'),

                        Textarea::make('meta_description')
                            ->label('Мета опис'),
                    ])->columnSpan(1),
            ])->columns(3);
    }
}
