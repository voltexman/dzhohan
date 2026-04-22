<?php

use App\Enums\CurrencyType;
use App\Enums\KnifeCollection;
use App\Enums\ProductCategory;
use App\Models\Product;

describe('Product CRUD Operations - Knives', function () {
    describe('Create', function () {
        it('can create a knife product with valid data', function () {
            $knifeData = [
                'name' => 'Тактичний ніж X-Pro',
                'slug' => 'tactical-knife-x-pro',
                'sku' => 'KNIFE-001',
                'description' => 'Професійний тактичний ніж для екстремальних умов',
                'price' => 2500.00,
                'currency' => CurrencyType::UAH,
                'quantity' => 1,
                'is_active' => true,
                'category' => ProductCategory::KNIFE,
                'collection' => KnifeCollection::TACTICAL,
                'total_length' => 250.5,
                'blade_length' => 120.0,
                'blade_thickness' => 3.5,
            ];

            $product = Product::create($knifeData);

            expect($product)
                ->id->not->toBeNull()
                ->name->toBe('Тактичний ніж X-Pro')
                ->category->toBe(ProductCategory::KNIFE)
                ->collection->toBe(KnifeCollection::TACTICAL)
                ->total_length->toEqual('250.50')
                ->blade_length->toEqual('120.00')
                ->is_active->toBeTrue();
        });

        it('can create a knife using factory', function () {
            // Ensure knife has collection value
            $product = Product::factory()
                ->state(['category' => ProductCategory::KNIFE, 'collection' => KnifeCollection::TACTICAL])
                ->create();

            expect($product)
                ->category->toBe(ProductCategory::KNIFE)
                ->collection->not->toBeNull();
        });

        it('auto-generates SKU for knife', function () {
            $product = Product::factory()->create();

            expect($product->sku)->not->toBeEmpty();
        });

        it('knife requires category', function () {
            $this->expectException(\Illuminate\Database\QueryException::class);

            Product::create([
                'name' => 'Test Knife',
                'slug' => 'test-knife',
                'price' => 1000,
                'quantity' => 1,
            ]);
        });

        it('can create knife with different collections', function () {
            foreach (KnifeCollection::cases() as $collection) {
                $product = Product::factory()->create([
                    'category' => ProductCategory::KNIFE,
                    'collection' => $collection,
                ]);

                expect($product->collection)->toBe($collection);
            }
        });
    });

    describe('Read', function () {
        it('can retrieve a knife by id', function () {
            $knife = Product::factory()
                ->create(['category' => ProductCategory::KNIFE]);

            $retrieved = Product::find($knife->id);

            expect($retrieved)
                ->not->toBeNull()
                ->id->toBe($knife->id)
                ->category->toBe(ProductCategory::KNIFE);
        });

        it('can list all knives', function () {
            Product::factory(5)->create(['category' => ProductCategory::KNIFE]);
            Product::factory(3)->create(['category' => ProductCategory::MATERIAL]);

            $knives = Product::where('category', ProductCategory::KNIFE->value)->get();

            expect($knives)->toHaveCount(5);
        });

        it('can retrieve knife by slug', function () {
            $knife = Product::factory()
                ->create([
                    'slug' => 'damask-knife-premium',
                    'category' => ProductCategory::KNIFE,
                ]);

            $retrieved = Product::firstWhere('slug', 'damask-knife-premium');

            expect($retrieved)
                ->not->toBeNull()
                ->id->toBe($knife->id);
        });

        it('can retrieve knife by SKU', function () {
            $knife = Product::factory()
                ->create(['sku' => 'KNIFE-2025-001']);

            $retrieved = Product::firstWhere('sku', 'KNIFE-2025-001');

            expect($retrieved)->not->toBeNull();
        });

        it('can load knife with all relations', function () {
            $knife = Product::factory()
                ->create(['category' => ProductCategory::KNIFE]);

            $loaded = Product::with('comments', 'likes')->find($knife->id);

            expect($loaded)->not->toBeNull();
        });

        it('shows only active knives in listing', function () {
            Product::factory(3)->create([
                'category' => ProductCategory::KNIFE,
                'is_active' => true,
            ]);
            Product::factory(2)->create([
                'category' => ProductCategory::KNIFE,
                'is_active' => false,
            ]);

            $active = Product::where([
                ['category', ProductCategory::KNIFE->value],
                ['is_active', true],
            ])->get();

            expect($active)->toHaveCount(3);
        });
    });

    describe('Update', function () {
        it('can update a knife', function () {
            $knife = Product::factory()
                ->create(['category' => ProductCategory::KNIFE]);

            $knife->update([
                'name' => 'Updated Knife Name',
                'price' => 3500.00,
            ]);

            expect($knife)
                ->name->toBe('Updated Knife Name')
                ->price->toEqual('3500.00');
        });

        it('can update knife specifications', function () {
            $knife = Product::factory()
                ->create(['category' => ProductCategory::KNIFE]);

            $knife->update([
                'total_length' => 280.5,
                'blade_length' => 140.0,
                'blade_thickness' => 4.0,
            ]);

            expect($knife->total_length)->toEqual('280.50');
            expect($knife->blade_length)->toEqual('140.00');
            expect($knife->blade_thickness)->toEqual('4.00');
        });

        it('can toggle knife availability', function () {
            $knife = Product::factory()
                ->create(['category' => ProductCategory::KNIFE, 'is_active' => true]);

            $knife->update(['is_active' => false]);

            expect($knife->is_active)->toBeFalse();
        });

        it('can update knife collection', function () {
            $knife = Product::factory()
                ->create([
                    'category' => ProductCategory::KNIFE,
                    'collection' => KnifeCollection::TACTICAL,
                ]);

            $knife->update(['collection' => KnifeCollection::HUNTING]);

            expect($knife->collection)->toBe(KnifeCollection::HUNTING);
        });

        it('can update quantity when new stock arrives', function () {
            $knife = Product::factory()
                ->create(['quantity' => 1]);

            $knife->increment('quantity', 2);

            expect($knife->quantity)->toBe(3);
        });

        it('can add additional attributes as JSON', function () {
            $knife = Product::factory()
                ->create(['category' => ProductCategory::KNIFE]);

            $knife->update([
                'additional_attributes' => [
                    'grip_type' => 'textured',
                    'weight' => '185g',
                    'edge_type' => 'tanto',
                ],
            ]);

            expect($knife->additional_attributes)
                ->toHaveKey('grip_type')
                ->toHaveKey('weight');
        });
    });

    describe('Delete', function () {
        it('can soft delete a knife', function () {
            $knife = Product::factory()
                ->create(['category' => ProductCategory::KNIFE]);
            $knifeId = $knife->id;

            $knife->delete();

            $found = Product::find($knifeId);
            expect($found)->toBeNull();

            $foundWithTrashed = Product::withTrashed()->find($knifeId);
            expect($foundWithTrashed)->not->toBeNull();
        });

        it('can restore soft deleted knife', function () {
            $knife = Product::factory()
                ->create(['category' => ProductCategory::KNIFE]);

            $knife->delete();
            expect(Product::find($knife->id))->toBeNull();

            $knife->restore();
            expect(Product::find($knife->id))->not->toBeNull();
        });

        it('can permanently delete knife', function () {
            $knife = Product::factory()
                ->create(['category' => ProductCategory::KNIFE]);

            $knife->forceDelete();

            $found = Product::withTrashed()->find($knife->id);
            expect($found)->toBeNull();
        });
    });

    describe('Knife Stock Management', function () {
        it('tracks knife availability', function () {
            $inStock = Product::factory()
                ->create(['category' => ProductCategory::KNIFE, 'quantity' => 5]);
            $sold = Product::factory()
                ->create(['category' => ProductCategory::KNIFE, 'quantity' => 0]);

            expect($inStock->hasStock())->toBeTrue();
            expect($sold->isSold())->toBeTrue();
            expect($inStock->getStockAttribute())->toBe(5);
        });

        it('can filter knives by stock status', function () {
            Product::factory(3)->create([
                'category' => ProductCategory::KNIFE,
                'quantity' => 5,
            ]);
            Product::factory(2)->create([
                'category' => ProductCategory::KNIFE,
                'quantity' => 0,
            ]);

            $inStock = Product::where([
                ['category', ProductCategory::KNIFE->value],
                ['quantity', '>', 0],
            ])->count();

            expect($inStock)->toBe(3);
        });

        it('deducts stock on order', function () {
            $knife = Product::factory()
                ->create(['category' => ProductCategory::KNIFE, 'quantity' => 10]);

            $knife->decrement('quantity');

            expect($knife->quantity)->toBe(9);
        });
    });

    describe('Knife Filtering & Search', function () {
        it('can filter knives by collection', function () {
            Product::factory(5)->create([
                'category' => ProductCategory::KNIFE,
                'collection' => KnifeCollection::TACTICAL,
            ]);
            Product::factory(3)->create([
                'category' => ProductCategory::KNIFE,
                'collection' => KnifeCollection::KITCHEN,
            ]);

            $tactical = Product::where([
                ['category', ProductCategory::KNIFE->value],
                ['collection', KnifeCollection::TACTICAL->value],
            ])->get();

            expect($tactical)->toHaveCount(5);
        });

        it('can search knives by name', function () {
            Product::factory()->create([
                'name' => 'Тактичний Damask ніж',
                'category' => ProductCategory::KNIFE,
            ]);
            Product::factory()->create([
                'name' => 'Кухонний ніж шефа',
                'category' => ProductCategory::KNIFE,
            ]);

            $found = Product::where('name', 'like', '%Damask%')
                ->where('category', ProductCategory::KNIFE->value)
                ->first();

            expect($found)->not->toBeNull();
            expect($found->name)->toContain('Damask');
        });

        it('can filter knives by price range', function () {
            Product::factory()->create([
                'category' => ProductCategory::KNIFE,
                'price' => 1000,
            ]);
            Product::factory()->create([
                'category' => ProductCategory::KNIFE,
                'price' => 5000,
            ]);
            Product::factory()->create([
                'category' => ProductCategory::KNIFE,
                'price' => 10000,
            ]);

            $midRange = Product::whereBetween('price', [2000, 8000])
                ->where('category', ProductCategory::KNIFE->value)
                ->get();

            expect($midRange)->toHaveCount(1);
        });

        it('can filter knives by blade length', function () {
            Product::factory()->create([
                'category' => ProductCategory::KNIFE,
                'blade_length' => 80,
            ]);
            Product::factory()->create([
                'category' => ProductCategory::KNIFE,
                'blade_length' => 150,
            ]);
            Product::factory()->create([
                'category' => ProductCategory::KNIFE,
                'blade_length' => 200,
            ]);

            $longBlade = Product::where('blade_length', '>', 120)
                ->where('category', ProductCategory::KNIFE->value)
                ->get();

            expect($longBlade->count())->toBe(2);
        });

        it('can use filter scope for complex queries', function () {
            Product::factory(10)->create([
                'category' => ProductCategory::KNIFE,
                'is_active' => true,
            ]);
            Product::factory(5)->create([
                'category' => ProductCategory::KNIFE,
                'is_active' => false,
            ]);

            $filtered = Product::filter([
                'search' => null,
                'status' => null,
            ])->count();

            expect($filtered)->toBeGreaterThan(0);
        });
    });
});
