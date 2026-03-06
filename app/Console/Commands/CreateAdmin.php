<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
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
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
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
    }
}
