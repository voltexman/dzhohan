<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;

class CreateAdmin extends Command
{
    protected $signature = 'create:admin';

    public function handle()
    {
        $role = Role::firstOrCreate(['name' => 'admin']);

        if ($role->wasRecentlyCreated) {
            $this->info('Роль admin створена.');
        } else {
            $this->warn('Роль admin вже існує.');
        }

        $admin = User::firstOrCreate(
            ['email' => 'dzhogun@gmail.com'],
            [
                'name' => 'Джоган Костянтин',
                'password' => bcrypt('password'),
            ]
        );

        if ($admin->wasRecentlyCreated) {
            $this->info('Адміністратор створений.');
        } else {
            $this->warn('Користувач admin вже існує.');
        }

        if (! $admin->hasRole('admin')) {
            $admin->assignRole($role);
            $this->info('Роль admin призначена користувачу.');
        } else {
            $this->warn('Користувач вже має роль admin.');
        }

        Setting::firstOrCreate(['id' => 1], [
            'email' => env('ADMIN_EMAIL'),
            'phone' => env('ADMIN_PHONE'),
            'contact' => 'Джоган Костянтин',
            'location' => 'м. Вінниця',

            'socials' => [
                'instagram' => 'https://www.instagram.com/dzhohan_knives',
                'facebook' => 'https://www.facebook.com/KostyantynDzhohun',
                'viber' => '+380 (63) 951-88-42',
                'telegram' => '+380 (63) 951-88-42',
                'whatsapp' => '+380 (63) 951-88-42',
            ],

            'faqs' => [
                [
                    'question' => 'Чи є ваші ножі холодною зброєю?',
                    'answer' => 'Мої ножі розробляються та виготовляються як господарсько-побутові інструменти. При проектуванні я дотримуюсь вимог чинного законодавства (довжина клинка, товщина обуха, наявність упору тощо), щоб вони не підпадали під категорію холодної зброї.',
                ],
                [
                    'question' => 'Чи можна замовити ніж за моїм власним ескізом?',
                    'answer' => 'Так, я працюю з індивідуальними замовленнями. Ми можемо обговорити ваші побажання щодо форми клинка, матеріалу руків\'я та оздоблення. Процес починається з узгодження ескізу та вибору матеріалів.',
                ],
                [
                    'question' => 'Який термін виготовлення ножа під замовлення?',
                    'answer' => 'Термін залежить від складності проекту та поточної черги. Зазвичай виготовлення одного ножа займає від 2 до 4 тижнів. Я завжди тримаю замовника в курсі етапів роботи.',
                ],
                [
                    'question' => 'Чи йдуть у комплекті піхви (чохол)?',
                    'answer' => 'Так, кожен ніж комплектується індивідуально виготовленими піхвами. Зазвичай це високоякісна натуральна шкіра з ручним швом, або ударостійкий кайдекс (Kydex) для тактичних чи туристичних моделей.',
                ],
                [
                    'question' => 'Чи надаєте ви гарантію на свої вироби?',
                    'answer' => 'Я надаю довічну гарантію на виробничі дефекти. Гарантія не поширюється на випадки нецільового використання (наприклад, використання ножа як лому чи зубила) та природний знос матеріалів.',
                ],
                [
                    'question' => 'Як відбувається оплата та доставка?',
                    'answer' => 'Доставка здійснюється Новою Поштою по всій Україні. Оплата можлива як післяплатою (після огляду ножа), так і повним переказом на карту. Для індивідуальних замовлень передбачена передоплата на закупівлю матеріалів.',
                ],
            ],
        ]);

        Cache::forget('settings');

        $this->info('Email та телефон додані у налаштування.');
    }
}
