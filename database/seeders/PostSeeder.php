<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        Post::factory(10)->create()->each(function ($post) {
            Comment::factory(rand(2, 5))->for($post, 'commentable')->create();
            Like::factory(rand(5, 15))->for($post, 'likeable')->create();
            $post->tags()->attach(Tag::inRandomOrder()->take(rand(1, 3))->pluck('id'));
        });
    }
}
