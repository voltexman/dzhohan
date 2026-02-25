<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // User::factory(10)->create();

        // Product::factory(25)->create();
        // Order::factory(50)->create();

        $tags = Tag::factory(15)->create();

        Product::factory(100)->create()->each(function ($product) use ($tags) {
            Comment::factory(rand(0, 10))->for($product, 'commentable')->create();
            Like::factory(rand(0, 25))->for($product, 'likeable')->create();

            $product->tags()->attach(
                $tags->random(rand(1, 3))->pluck('id')
            );
        });

        Post::factory(25)->create()->each(function ($post) use ($tags) {
            Comment::factory(rand(0, 15))->for($post, 'commentable')->create();
            Like::factory(rand(0, 30))->for($post, 'likeable')->create();

            $post->tags()->attach(
                $tags->random(rand(1, 3))->pluck('id')
            );
        });

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
