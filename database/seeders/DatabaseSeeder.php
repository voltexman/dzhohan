<?php

namespace Database\Seeders;

use App\Models\{Comment, Feedback, Like, Post, Product, Tag, User};
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Адмін та Роль (Spatie)
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            ['name' => 'Admin', 'password' => bcrypt('password')]
        );

        if (!$admin->hasRole('admin')) $admin->assignRole($adminRole);

        // 2. Теги (створюємо лише якщо база порожня, щоб не було помилок UNIQUE)
        if (Tag::count() === 0) {
            Tag::factory(15)->create();
        }
        $tags = Tag::all();

        // 3. Продукти
        Product::factory(10)->create()->each(function ($product) use ($tags) {
            Comment::factory(rand(0, 15))->for($product, 'commentable')->create();
            Like::factory(rand(0, 25))->for($product, 'likeable')->create();
            $product->tags()->attach($tags->random(rand(1, 3)));
        });

        // 4. Пости
        Post::factory(10)->create()->each(function ($post) use ($tags) {
            Comment::factory(rand(2, 5))->for($post, 'commentable')->create();
            Like::factory(rand(5, 15))->for($post, 'likeable')->create();
            $post->tags()->attach($tags->random(rand(1, 3)));
        });

        Feedback::factory(50)->create();
    }
}
