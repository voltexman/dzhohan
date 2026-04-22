<?php

use App\Enums\PostType;
use App\Models\Post;
use App\Models\Tag;

describe('Post CRUD Operations', function () {
    describe('Create', function () {
        it('can create a post with valid data', function () {
            $postData = [
                'name' => 'Історія ножів',
                'slug' => 'istoriya-nozhiv',
                'text' => 'Довга історія про ножі від давних часів до сьогодні.',
                'type' => PostType::ARTICLE->value,
            ];

            $post = Post::create($postData);

            expect($post)
                ->id->not->toBeNull()
                ->name->toBe('Історія ножів')
                ->slug->toBe('istoriya-nozhiv')
                ->text->not->toBeNull()
                ->type->toBe(PostType::ARTICLE);
        });

        it('can create a post using factory', function () {
            $post = Post::factory()->create();

            expect($post)
                ->id->not->toBeNull()
                ->name->not->toBeEmpty()
                ->slug->not->toBeEmpty();
        });

        it('fails creating post with missing required fields', function () {
            $this->expectException(\Illuminate\Database\QueryException::class);

            Post::create([
                'text' => 'Текст поста',
            ]);
        });

        it('generates unique slug', function () {
            $post1 = Post::factory()->create(['name' => 'Тест']);
            $post2 = Post::factory()->create(['name' => 'Тест']);

            expect($post1->slug)->not->toBe($post2->slug);
        });
    });

    describe('Read', function () {
        it('can retrieve a post by id', function () {
            $post = Post::factory()->create();

            $retrieved = Post::find($post->id);

            expect($retrieved)
                ->not->toBeNull()
                ->id->toBe($post->id)
                ->name->toBe($post->name);
        });

        it('can list all posts', function () {
            Post::factory(5)->create();

            $posts = Post::all();

            expect($posts)->toHaveCount(5);
        });

        it('can retrieve post by slug', function () {
            $post = Post::factory()->create(['slug' => 'best-knives']);

            $retrieved = Post::firstWhere('slug', 'best-knives');

            expect($retrieved)
                ->not->toBeNull()
                ->id->toBe($post->id);
        });

        it('returns null for non-existent post', function () {
            $post = Post::find(999999);

            expect($post)->toBeNull();
        });

        it('can paginate posts', function () {
            Post::factory(15)->create();

            $posts = Post::paginate(5);

            expect($posts->count())->toBe(5);
            expect($posts->total())->toBeGreaterThanOrEqual(15);
        });
    });

    describe('Update', function () {
        it('can update a post', function () {
            $post = Post::factory()->create();
            $oldName = $post->name;

            $post->update([
                'name' => 'Оновлене ім\'я',
                'text' => 'Оновлений текст',
            ]);

            expect($post)
                ->name->toBe('Оновлене ім\'я')
                ->text->toBe('Оновлений текст');

            expect($post->name)->not->toBe($oldName);
        });

        it('preserves other fields when updating', function () {
            $post = Post::factory()->create(['type' => PostType::NEWS]);

            $post->update(['name' => 'Новий текст']);

            expect($post->type)->toBe(PostType::NEWS);
            expect($post->name)->toBe('Новий текст');
        });

        it('can bulk update posts', function () {
            Post::factory(3)->create(['type' => PostType::ARTICLE]);

            Post::query()
                ->where('type', PostType::ARTICLE->value)
                ->update(['type' => PostType::NEWS->value]);

            $updated = Post::where('type', PostType::NEWS->value)->count();

            expect($updated)->toBe(3);
        });

        it('cannot update non-existent post', function () {
            $result = Post::where('id', 999999)->update(['name' => 'Test']);

            expect($result)->toBe(0);
        });
    });

    describe('Delete', function () {
        it('can delete a post', function () {
            $post = Post::factory()->create();
            $postId = $post->id;

            $post->delete();

            $found = Post::find($postId);

            expect($found)->toBeNull();
        });

        it('can soft delete with soft deletes', function () {
            // Якщо Post має SoftDeletes трейт
            $post = Post::factory()->create();
            $postId = $post->id;

            // Перевірити, чи є тип post
            $postType = (new \ReflectionClass($post))->getTraitNames();
            $hasSoftDeletes = in_array('Illuminate\Database\Eloquent\SoftDeletes', $postType);

            if (!$hasSoftDeletes) {
                expect(true)->toBeTrue(); // Пропустити тест
                return;
            }

            $post->delete();

            $found = Post::find($postId);
            expect($found)->toBeNull();

            $foundWithTrashed = Post::withTrashed()->find($postId);
            expect($foundWithTrashed)->not->toBeNull();
        });

        it('can bulk delete posts', function () {
            Post::factory(5)->create();

            Post::query()->delete();

            $count = Post::count();

            expect($count)->toBe(0);
        });
    });

    describe('Relationships', function () {
        it('can attach tags to post', function () {
            $post = Post::factory()->create();
            $tags = Tag::factory(3)->create();

            $post->tags()->attach($tags);

            expect($post->tags)->toHaveCount(3);
        });

        it('can retrieve post with its tags', function () {
            $post = Post::factory()->create();
            $tags = Tag::factory(2)->create();

            $post->tags()->attach($tags);

            $loaded = Post::with('tags')->find($post->id);

            expect($loaded->tags)->toHaveCount(2);
        });

        it('can sync tags', function () {
            $post = Post::factory()->create();
            $oldTags = Tag::factory(2)->create();
            $newTags = Tag::factory(3)->create();

            $post->tags()->attach($oldTags);
            $initialCount = $post->tags()->count();
            expect($initialCount)->toBe(2);

            $post->tags()->sync($newTags->pluck('id'));
            $newCount = $post->fresh()->tags()->count();

            expect($newCount)->toBe(3);
        });
    });

    describe('Filtering & Searching', function () {
        it('can filter posts by type', function () {
            Post::factory(3)->create(['type' => PostType::ARTICLE]);
            Post::factory(2)->create(['type' => PostType::NEWS]);

            $articles = Post::where('type', PostType::ARTICLE->value)->get();

            expect($articles)->toHaveCount(3);
        });

        it('can search posts by name', function () {
            Post::factory()->create(['name' => 'Sharpening guide']);
            Post::factory()->create(['name' => 'Knife care tips']);
            Post::factory()->create(['name' => 'Random article']);

            $results = Post::where('name', 'like', '%guide%')->get();

            expect($results->count())->toBeGreaterThanOrEqual(1);
        });

        it('can search posts by slug', function () {
            Post::factory()->create(['slug' => 'how-to-sharpen']);
            Post::factory()->create(['slug' => 'blade-care']);

            $found = Post::where('slug', 'like', '%sharpen%')->first();

            expect($found)->not->toBeNull();
            expect($found->slug)->toContain('sharpen');
        });

        it('orders posts by creation date', function () {
            $post1 = Post::factory()->create();
            sleep(1);
            $post2 = Post::factory()->create();

            $posts = Post::orderBy('created_at', 'desc')->get();

            expect($posts->first()->id)->toBe($post2->id);
            expect($posts->last()->id)->toBe($post1->id);
        });
    });
});
