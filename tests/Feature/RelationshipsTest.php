<?php

use App\Enums\ProductCategory;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\Product;
use App\Models\Review;
use App\Models\Tag;

describe('Model Relationships', function () {
    describe('Post Relationships', function () {
        it('post can have many comments', function () {
            $post = Post::factory()->create();
            $comments = Comment::factory(3)->create([
                'commentable_type' => Post::class,
                'commentable_id' => $post->id,
            ]);

            expect($post->comments)->toHaveCount(3);
        });

        it('post can have many likes', function () {
            $post = Post::factory()->create();
            Like::factory(5)->create([
                'likeable_type' => Post::class,
                'likeable_id' => $post->id,
            ]);

            expect($post->likes)->toHaveCount(5);
        });

        it('post can have many tags through morphToMany', function () {
            $post = Post::factory()->create();
            $tags = Tag::factory(3)->create();

            $post->tags()->attach($tags);

            expect($post->tags)->toHaveCount(3);
        });

        it('tag can be attached to multiple posts', function () {
            $tag = Tag::factory()->create();
            $posts = Post::factory(4)->create();

            foreach ($posts as $post) {
                $post->tags()->attach($tag);
            }

            expect($tag->posts)->toHaveCount(4);
        });

        it('can load post with all relationships', function () {
            $post = Post::factory()->create();
            $tags = Tag::factory(2)->create();
            Comment::factory(2)->create([
                'commentable_type' => Post::class,
                'commentable_id' => $post->id,
            ]);
            Like::factory(3)->create([
                'likeable_type' => Post::class,
                'likeable_id' => $post->id,
            ]);

            $post->tags()->attach($tags);

            $loaded = Post::with(['tags', 'comments', 'likes'])->find($post->id);

            expect($loaded->tags)->toHaveCount(2);
            expect($loaded->comments)->toHaveCount(2);
            expect($loaded->likes)->toHaveCount(3);
        });
    });

    describe('Product Relationships', function () {
        it('product can have many comments', function () {
            $product = Product::factory()->create();
            Comment::factory(3)->create([
                'commentable_type' => Product::class,
                'commentable_id' => $product->id,
            ]);

            expect($product->comments)->toHaveCount(3);
        });

        it('product can have many likes', function () {
            $product = Product::factory()->create();
            Like::factory(4)->create([
                'likeable_type' => Product::class,
                'likeable_id' => $product->id,
            ]);

            expect($product->likes)->toHaveCount(4);
        });

        it('product can have many reviews', function () {
            $product = Product::factory()->create();
            Review::factory(3)->create(['product_id' => $product->id]);

            expect($product->reviews)->toHaveCount(3);
        });

        it('can count likes on product', function () {
            $product = Product::factory()->create();
            Like::factory(5)->create([
                'likeable_type' => Product::class,
                'likeable_id' => $product->id,
            ]);

            expect($product->likes()->count())->toBe(5);
        });

        it('can count comments on product', function () {
            $product = Product::factory()->create();
            Comment::factory(4)->create([
                'commentable_type' => Product::class,
                'commentable_id' => $product->id,
            ]);

            expect($product->comments()->count())->toBe(4);
        });
    });

    describe('Knife vs Material Relationships', function () {
        it('knife and material products have same relationships', function () {
            $knife = Product::factory()->create(['category' => ProductCategory::KNIFE]);
            $material = Product::factory()->create(['category' => ProductCategory::MATERIAL]);

            Comment::factory()->create([
                'commentable_type' => Product::class,
                'commentable_id' => $knife->id,
            ]);
            Comment::factory()->create([
                'commentable_type' => Product::class,
                'commentable_id' => $material->id,
            ]);

            expect($knife->comments)->toHaveCount(1);
            expect($material->comments)->toHaveCount(1);
        });

        it('can compare likes between knives and materials', function () {
            $knife = Product::factory()->create(['category' => ProductCategory::KNIFE]);
            $material = Product::factory()->create(['category' => ProductCategory::MATERIAL]);

            Like::factory(5)->create([
                'likeable_type' => Product::class,
                'likeable_id' => $knife->id,
            ]);
            Like::factory(3)->create([
                'likeable_type' => Product::class,
                'likeable_id' => $material->id,
            ]);

            expect($knife->likes()->count())->toBe(5);
            expect($material->likes()->count())->toBe(3);
        });
    });

    describe('Polymorphic Relationships', function () {
        it('comment can belong to post or product', function () {
            $post = Post::factory()->create();
            $product = Product::factory()->create();

            $postComment = Comment::factory()->create([
                'commentable_type' => Post::class,
                'commentable_id' => $post->id,
            ]);
            $productComment = Comment::factory()->create([
                'commentable_type' => Product::class,
                'commentable_id' => $product->id,
            ]);

            expect($postComment->commentable)->toBeInstanceOf(Post::class);
            expect($productComment->commentable)->toBeInstanceOf(Product::class);
        });

        it('like can belong to post or product', function () {
            $post = Post::factory()->create();
            $product = Product::factory()->create();

            $postLike = Like::factory()->create([
                'likeable_type' => Post::class,
                'likeable_id' => $post->id,
            ]);
            $productLike = Like::factory()->create([
                'likeable_type' => Product::class,
                'likeable_id' => $product->id,
            ]);

            expect($postLike->likeable)->toBeInstanceOf(Post::class);
            expect($productLike->likeable)->toBeInstanceOf(Product::class);
        });

        it('can query all comments regardless of commentable type', function () {
            $post = Post::factory()->create();
            $product = Product::factory()->create();

            Comment::factory(2)->create([
                'commentable_type' => Post::class,
                'commentable_id' => $post->id,
            ]);
            Comment::factory(3)->create([
                'commentable_type' => Product::class,
                'commentable_id' => $product->id,
            ]);

            expect(Comment::count())->toBe(5);
        });

        it('can query all likes regardless of likeable type', function () {
            $post = Post::factory()->create();
            $product = Product::factory()->create();

            Like::factory(4)->create([
                'likeable_type' => Post::class,
                'likeable_id' => $post->id,
            ]);
            Like::factory(6)->create([
                'likeable_type' => Product::class,
                'likeable_id' => $product->id,
            ]);

            expect(Like::count())->toBe(10);
        });
    });

    describe('MorphToMany Relationships', function () {
        it('tag can belong to multiple posts', function () {
            $tag = Tag::factory()->create();
            $posts = Post::factory(3)->create();

            foreach ($posts as $post) {
                $post->tags()->attach($tag);
            }

            expect($tag->posts()->count())->toBe(3);
        });

        it('post can have multiple tags', function () {
            $post = Post::factory()->create();
            $tags = Tag::factory(5)->create();

            $post->tags()->attach($tags);

            expect($post->tags()->count())->toBe(5);
        });

        it('can detach tags from post', function () {
            $post = Post::factory()->create();
            $tags = Tag::factory(3)->create();

            $post->tags()->attach($tags);
            expect($post->tags()->count())->toBe(3);

            $post->tags()->detach($tags[0]);
            expect($post->tags()->count())->toBe(2);
        });

        it('can sync tags on post', function () {
            $post = Post::factory()->create();
            $oldTags = Tag::factory(3)->create();
            $newTags = Tag::factory(2)->create();

            $post->tags()->attach($oldTags);
            expect($post->tags()->count())->toBe(3);

            $post->tags()->sync($newTags);
            expect($post->tags()->count())->toBe(2);
        });
    });

    describe('Relationship Eager Loading', function () {
        it('can eager load comments on posts', function () {
            Post::factory(5)->create()->each(function ($post) {
                Comment::factory(3)->create([
                    'commentable_type' => Post::class,
                    'commentable_id' => $post->id,
                ]);
            });

            $posts = Post::with('comments')->get();

            expect($posts)->toHaveCount(5);
            expect($posts[0]->comments)->not->toBeNull();
        });

        it('can eager load likes on products', function () {
            Product::factory(5)->create()->each(function ($product) {
                Like::factory(2)->create([
                    'likeable_type' => Product::class,
                    'likeable_id' => $product->id,
                ]);
            });

            $products = Product::with('likes')->get();

            expect($products)->toHaveCount(5);
            expect($products[0]->likes)->not->toBeNull();
        });

        it('can eager load nested relationships', function () {
            $post = Post::factory()->create();
            $tags = Tag::factory(2)->create();
            $post->tags()->attach($tags);

            $loaded = Post::with('tags')->find($post->id);

            expect($loaded->tags)->toHaveCount(2);
        });
    });
});
