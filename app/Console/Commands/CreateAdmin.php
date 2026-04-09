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
        ]);

        Cache::forget('settings');

        $this->info('Email та телефон додані у налаштування.');
    }
}
