<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Cache;
use UnitEnum;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.settings';

    protected static ?int $navigationSort = 4;

    protected static string|UnitEnum|null $navigationGroup = 'Параметри';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Налаштування';

    protected static ?string $title = 'Налаштування';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(Setting::first()?->toArray() ?? []);
    }

    public function form($form)
    {
        return $form
            ->schema([
                Tabs::make('SettingsTabs')
                    ->tabs([
                        Tab::make('Основні налаштування')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Section::make('Контактна інформація')
                                    ->description('Основні дані для зв\'язку, що відображаються у футері та контактах')
                                    ->aside()
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('email')
                                                ->label('Email адреса')
                                                ->email()
                                                ->required(),

                                            TextInput::make('phone')
                                                ->label('Номер телефону')
                                                ->tel()
                                                ->regex('/^\+\d{3}\s\(\d{2}\)\s\d{3}-\d{2}-\d{2}$/')
                                                ->validationMessages([
                                                    'regex' => 'Номер має відповідати формату',
                                                ])
                                                ->mask('+999 (99) 999-99-99')
                                                ->required(),

                                            TextInput::make('contact')
                                                ->label('Контактне лице'),

                                            TextInput::make('location')
                                                ->label('Локація'),

                                            TextInput::make('address')
                                                ->label('Адреса майстерні (для самовивозу)'),
                                        ]),
                                    ]),

                                Section::make('Соціальні мережі та месенджери')
                                    ->description('Увімкніть потрібні мережі та додайте посилання або номери телефонів')
                                    ->aside()
                                    ->collapsible()
                                    ->persistCollapsed()
                                    ->schema([
                                        TextInput::make('socials.facebook')
                                            ->label('Facebook')
                                            ->placeholder('https://facebook.com')
                                            ->prefixIcon(Heroicon::OutlinedGlobeAlt)
                                            ->url(),

                                        TextInput::make('socials.instagram')
                                            ->label('Instagram')
                                            ->placeholder('https://instagram.com')
                                            ->prefixIcon(Heroicon::OutlinedGlobeAlt)
                                            ->url(),

                                        TextInput::make('socials.pinterest')
                                            ->label('Pinterest')
                                            ->placeholder('https://pinterest.com')
                                            ->prefixIcon(Heroicon::OutlinedGlobeAlt)
                                            ->url(),

                                        TextInput::make('socials.viber')
                                            ->label('Viber')
                                            ->placeholder('380991234567')
                                            ->prefixIcon(Heroicon::OutlinedPhone),

                                        TextInput::make('socials.telegram')
                                            ->label('Telegram')
                                            ->placeholder('@NickName')
                                            ->prefixIcon(Heroicon::OutlinedPhone),

                                        TextInput::make('socials.whatsapp')
                                            ->label('WhatsApp')
                                            ->placeholder('380991234567')
                                            ->prefixIcon(Heroicon::OutlinedPhone),
                                    ]),

                                Section::make('Статус доступності сайту')
                                    ->description('Керування режимом технічних робіт')
                                    ->aside()
                                    ->icon('heroicon-o-signal')
                                    ->compact()
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Toggle::make('online')
                                                    ->label('Сайт онлайн')
                                                    ->helperText('Показувати сайт користувачам')
                                                    ->onIcon('heroicon-m-check-circle')
                                                    ->offIcon('heroicon-m-x-circle')
                                                    ->onColor('success')
                                                    ->offColor('danger')
                                                    ->reactive()
                                                    ->default(true),

                                                DatePicker::make('maintenance_until')
                                                    ->label('Орієнтовне завершення робіт')
                                                    ->prefixIcon('heroicon-o-calendar-days')
                                                    ->native(false)
                                                    ->displayFormat('d.m.Y')
                                                    ->disabled(fn ($get) => $get('online')),
                                            ]),
                                    ]),
                            ]),

                        Tab::make('Часті запитання (FAQ)')
                            ->icon('heroicon-o-question-mark-circle')
                            ->schema([
                                Section::make('Список запитань та відповідей')
                                    ->description('Керуйте списком запитань та відповідей на сайті')
                                    ->schema([
                                        Repeater::make('faqs')
                                            ->label(false)
                                            ->addActionLabel('Додати запитання')
                                            ->reorderableWithButtons()
                                            ->collapsible()
                                            ->cloneable()
                                            ->itemLabel(fn (array $state): ?string => $state['question'] ?? 'Нове запитання')
                                            ->schema([
                                                TextInput::make('question')
                                                    ->label('Запитання')
                                                    ->placeholder('Наприклад: Як замовити ніж?')
                                                    ->required()
                                                    ->columnSpanFull(),

                                                Textarea::make('answer')
                                                    ->label('Відповідь')
                                                    ->placeholder('Напишіть детальну відповідь...')
                                                    ->rows(3)
                                                    ->required()
                                                    ->columnSpanFull(),
                                            ])
                                            ->grid(1)
                                            ->default([]),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Зберегти налаштування')
                ->action(fn () => $this->save()),
        ];
    }

    public function save(): void
    {
        Setting::updateOrCreate(['id' => 1], $this->form->getState());

        Cache::forget('settings');

        Notification::make()
            ->title('Налаштування збережено')
            ->success()
            ->send();
    }
}
