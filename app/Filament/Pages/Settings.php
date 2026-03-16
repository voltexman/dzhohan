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
                        ]),
                    ]),

                Section::make('Соціальні мережі')
                    ->description('Увімкніть потрібні мережі та додайте посилання на профілі')
                    ->aside()
                    ->schema([
                        TextInput::make('fb_url')
                            ->label('Посилання на Facebook')
                            ->prefix('https://www.facebook.com/')
                            ->url(),

                        TextInput::make('ig_url')
                            ->label('Посилання на Instagram')
                            ->prefix('https://www.instagram.com/')
                            ->url(),

                        TextInput::make('ig_url')
                            ->label('Посилання на Pinterest')
                            ->prefix('https://www.pinterest.com/')
                            ->url(),
                    ]),

                Section::make('Додаткові налаштування')
                    ->aside()
                    ->schema([
                        TextInput::make('address')
                            ->label('Адреса майстерні (для самовивозу)'),

                        TextInput::make('admin_telegram_id')
                            ->label('Telegram Chat ID (для сповіщень)')
                            ->helperText('ID чату, куди приходитимуть нові замовлення'),

                        Toggle::make('online')
                            ->label('Сайт онлайн')
                            ->helperText('Якщо вимкнути, сайт показуватиме сторінку "Технічні роботи"')
                            ->reactive()
                            ->default(true),

                        DatePicker::make('maintenance_until')
                            ->label('Технічні роботи до')
                            ->native(false),
                    ]),
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
