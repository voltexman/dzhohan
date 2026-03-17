<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Feedback;
use App\Models\Like;
use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use App\Models\Subscriber;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->call('create:admin');

        // 2. Теги (створюємо лише якщо база порожня, щоб не було помилок UNIQUE)
        if (Tag::count() === 0) {
            Tag::factory(15)->create();
        }
        $tags = Tag::all();

        Product::factory(20)->create()->each(function ($product) use ($tags) {
            Comment::factory(rand(0, 15))
                ->for($product, 'commentable')
                ->create()
                ->each(function ($comment) {
                    Like::factory(rand(0, 10))
                        ->for($comment, 'likeable')
                        ->create();
                });
            Like::factory(rand(0, 25))->for($product, 'likeable')->create();
            $product->tags()->attach($tags->random(rand(1, 3)));
        });

        // 4. Пости
        // Post::factory(10)->create()->each(function ($post) use ($tags) {
        //     Comment::factory(rand(2, 5))->for($post, 'commentable')->create();
        //     Like::factory(rand(5, 15))->for($post, 'likeable')->create();
        //     $post->tags()->attach($tags->random(rand(1, 3)));
        // });

        Feedback::factory()->count(50)->create();

        Order::factory()->count(50)->create();

        Subscriber::factory()->count(50)->create();
    }
}
