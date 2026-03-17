<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
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
                        TextInput::make('fb_url')
                            ->label('Facebook')
                            ->placeholder('https://facebook.com')
                            ->prefixIcon(Heroicon::OutlinedGlobeAlt)
                            ->url(),

                        TextInput::make('ig_url')
                            ->label('Instagram')
                            ->placeholder('https://instagram.com')
                            ->prefixIcon(Heroicon::OutlinedGlobeAlt)
                            ->url(),

                        TextInput::make('pin_url')
                            ->label('Pinterest')
                            ->placeholder('https://pinterest.com')
                            ->prefixIcon(Heroicon::OutlinedGlobeAlt)
                            ->url(),

                        TextInput::make('viber_link')
                            ->label('Viber')
                            ->placeholder('380991234567')
                            ->prefixIcon(Heroicon::OutlinedPhone),

                        TextInput::make('tg_url')
                            ->label('Telegram')
                            ->placeholder('@NickName')
                            ->prefixIcon(Heroicon::OutlinedPhone)
                            ->url(),

                        TextInput::make('whatsapp_url')
                            ->label('WhatsApp')
                            ->placeholder('380991234567')
                            ->prefixIcon(Heroicon::OutlinedPhone)
                            ->url(),
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
                                    ->hidden(fn($get) => $get('online')),
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
                ->action(fn() => $this->save()),
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
