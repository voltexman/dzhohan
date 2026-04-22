<?php

use App\Enums\CurrencyType;
use App\Enums\ProductCategory;
use App\Models\Product;

describe('Product CRUD Operations - Materials', function () {
    describe('Create', function () {
        it('can create a material product with valid data', function () {
            $materialData = [
                'name' => 'Дамаск сталь (32 шари)',
                'slug' => 'damask-steel-32-layers',
                'sku' => 'MTRLS-DAMASK-01',
                'description' => 'Преміальна дамаська сталь для виготовлення ножів',
                'price' => 850.00,
                'currency' => CurrencyType::UAH,
                'quantity' => 15,
                'is_active' => true,
                'category' => ProductCategory::MATERIAL,
            ];

            $product = Product::create($materialData);

            expect($product)
                ->id->not->toBeNull()
                ->name->toBe('Дамаск сталь (32 шари)')
                ->category->toBe(ProductCategory::MATERIAL)
                ->currency->toBe(CurrencyType::UAH)
                ->quantity->toBe(15)
                ->is_active->toBeTrue();
        });

        it('can create a material using factory', function () {
            $product = Product::factory()
                ->create([
                    'category' => ProductCategory::MATERIAL,
                    'collection' => null,
                    'quantity' => 5,
                    'total_length' => null,
                    'blade_length' => null,
                    'blade_thickness' => null,
                ]);

            expect($product)
                ->category->toBe(ProductCategory::MATERIAL)
                ->quantity->toBeGreaterThan(0)
                ->currency->toBe(CurrencyType::UAH);
        });

        it('material does not require collection', function () {
            $product = Product::factory()
                ->create([
                    'category' => ProductCategory::MATERIAL,
                    'collection' => null,
                ]);

            expect($product->collection)->toBeNull();
        });

        it('material does not require knife specifications', function () {
            $product = Product::factory()
                ->create([
                    'category' => ProductCategory::MATERIAL,
                    'total_length' => null,
                    'blade_length' => null,
                    'blade_thickness' => null,
                ]);

            expect($product->total_length)->toBeNull();
            expect($product->blade_length)->toBeNull();
            expect($product->blade_thickness)->toBeNull();
        });

        it('material defaults to UAH currency', function () {
            $product = Product::factory()
                ->create([
                    'category' => ProductCategory::MATERIAL,
                    'currency' => CurrencyType::UAH,
                ]);

            expect($product->currency)->toBe(CurrencyType::UAH);
        });

        it('can create multiple materials', function () {
            $materials = [
                'Дамаск сталь',
                'Шкіра для рукояток',
                'Дерево червоне',
                'Латунь для гарди',
                'Масло для консервації',
            ];

            foreach ($materials as $name) {
                Product::factory()->create([
                    'name' => $name,
                    'category' => ProductCategory::MATERIAL,
                ]);
            }

            $created = Product::where('category', ProductCategory::MATERIAL->value)->count();

            expect($created)->toBe(5);
        });
    });

    describe('Read', function () {
        it('can retrieve a material by id', function () {
            $material = Product::factory()
                ->create(['category' => ProductCategory::MATERIAL]);

            $retrieved = Product::find($material->id);

            expect($retrieved)
                ->not->toBeNull()
                ->id->toBe($material->id)
                ->category->toBe(ProductCategory::MATERIAL);
        });

        it('can list all materials', function () {
            Product::factory(5)->create(['category' => ProductCategory::MATERIAL]);
            Product::factory(3)->create(['category' => ProductCategory::KNIFE]);

            $materials = Product::where('category', ProductCategory::MATERIAL->value)->get();

            expect($materials)->toHaveCount(5);
        });

        it('can retrieve material by slug', function () {
            $material = Product::factory()
                ->create([
                    'slug' => 'premium-leather-handle',
                    'category' => ProductCategory::MATERIAL,
                ]);

            $retrieved = Product::firstWhere('slug', 'premium-leather-handle');

            expect($retrieved)
                ->not->toBeNull()
                ->category->toBe(ProductCategory::MATERIAL);
        });

        it('can retrieve material by SKU', function () {
            $material = Product::factory()
                ->create([
                    'sku' => 'MAT-STEEL-001',
                    'category' => ProductCategory::MATERIAL,
                ]);

            $retrieved = Product::firstWhere('sku', 'MAT-STEEL-001');

            expect($retrieved)->not->toBeNull();
        });

        it('can paginate materials', function () {
            Product::factory(15)->create(['category' => ProductCategory::MATERIAL]);

            $materials = Product::where('category', ProductCategory::MATERIAL->value)
                ->paginate(5);

            expect($materials->count())->toBe(5);
            expect($materials->total())->toBeGreaterThanOrEqual(15);
        });

        it('shows only active materials in listing', function () {
            Product::factory(4)->create([
                'category' => ProductCategory::MATERIAL,
                'is_active' => true,
            ]);
            Product::factory(2)->create([
                'category' => ProductCategory::MATERIAL,
                'is_active' => false,
            ]);

            $active = Product::where([
                ['category', ProductCategory::MATERIAL->value],
                ['is_active', true],
            ])->get();

            expect($active)->toHaveCount(4);
        });
    });

    describe('Update', function () {
        it('can update a material', function () {
            $material = Product::factory()
                ->create(['category' => ProductCategory::MATERIAL]);

            $material->update([
                'name' => 'Updated Material Name',
                'price' => 1200.00,
            ]);

            expect($material)
                ->name->toBe('Updated Material Name')
                ->price->toEqual('1200.00');
        });

        it('can update material quantity', function () {
            $material = Product::factory()
                ->create(['category' => ProductCategory::MATERIAL, 'quantity' => 10]);

            $material->update(['quantity' => 25]);

            expect($material->quantity)->toBe(25);
        });

        it('can update material description', function () {
            $material = Product::factory()
                ->create(['category' => ProductCategory::MATERIAL]);

            $oldDescription = $material->description;

            $material->update(['description' => 'Нова якіснішої опис матеріалу']);

            expect($material->description)->not->toBe($oldDescription);
        });

        it('can toggle material availability', function () {
            $material = Product::factory()
                ->create([
                    'category' => ProductCategory::MATERIAL,
                    'is_active' => true,
                ]);

            $material->update(['is_active' => false]);

            expect($material->is_active)->toBeFalse();

            $material->update(['is_active' => true]);

            expect($material->is_active)->toBeTrue();
        });

        it('can update currency if needed', function () {
            $material = Product::factory()
                ->create([
                    'category' => ProductCategory::MATERIAL,
                    'currency' => CurrencyType::UAH,
                ]);

            $material->update(['currency' => CurrencyType::USD]);

            expect($material->currency)->toBe(CurrencyType::USD);
        });

        it('can add additional metadata as JSON', function () {
            $material = Product::factory()
                ->create(['category' => ProductCategory::MATERIAL]);

            $material->update([
                'additional_attributes' => [
                    'origin' => 'Japan',
                    'hardness' => 'HRC 62-64',
                    'thickness' => '5mm',
                    'width' => '30mm',
                ],
            ]);

            expect($material->additional_attributes)
                ->toHaveKey('origin')
                ->toHaveKey('hardness')
                ->toHaveKey('thickness');
        });

        it('preserves other fields when updating', function () {
            $material = Product::factory()
                ->create([
                    'category' => ProductCategory::MATERIAL,
                    'currency' => CurrencyType::UAH,
                ]);

            $material->update(['name' => 'New Name']);

            expect($material->currency)->toBe(CurrencyType::UAH);
            expect($material->category)->toBe(ProductCategory::MATERIAL);
        });
    });

    describe('Delete', function () {
        it('can soft delete a material', function () {
            $material = Product::factory()
                ->create(['category' => ProductCategory::MATERIAL]);
            $materialId = $material->id;

            $material->delete();

            $found = Product::find($materialId);
            expect($found)->toBeNull();

            $foundWithTrashed = Product::withTrashed()->find($materialId);
            expect($foundWithTrashed)->not->toBeNull();
        });

        it('can restore soft deleted material', function () {
            $material = Product::factory()
                ->create(['category' => ProductCategory::MATERIAL]);

            $material->delete();
            expect(Product::find($material->id))->toBeNull();

            $material->restore();
            expect(Product::find($material->id))->not->toBeNull();
        });

        it('can permanently delete material', function () {
            $material = Product::factory()
                ->create(['category' => ProductCategory::MATERIAL]);

            $material->forceDelete();

            $found = Product::withTrashed()->find($material->id);
            expect($found)->toBeNull();
        });

        it('can bulk delete materials', function () {
            Product::factory(5)->create(['category' => ProductCategory::MATERIAL]);

            Product::where('category', ProductCategory::MATERIAL->value)->delete();

            $count = Product::where('category', ProductCategory::MATERIAL->value)->count();

            expect($count)->toBe(0);
        });
    });

    describe('Material Stock Management', function () {
        it('tracks material availability', function () {
            $inStock = Product::factory()
                ->create(['category' => ProductCategory::MATERIAL, 'quantity' => 20]);
            $sold = Product::factory()
                ->create(['category' => ProductCategory::MATERIAL, 'quantity' => 0]);

            expect($inStock->hasStock())->toBeTrue();
            expect($sold->isSold())->toBeTrue();
        });

        it('can filter materials by stock status', function () {
            Product::factory(4)->create([
                'category' => ProductCategory::MATERIAL,
                'quantity' => 5,
            ]);
            Product::factory(3)->create([
                'category' => ProductCategory::MATERIAL,
                'quantity' => 0,
            ]);

            $inStock = Product::where([
                ['category', ProductCategory::MATERIAL->value],
                ['quantity', '>', 0],
            ])->count();

            expect($inStock)->toBe(4);
        });

        it('can reserve material stock', function () {
            $material = Product::factory()
                ->create(['category' => ProductCategory::MATERIAL, 'quantity' => 20]);

            $reserved = 5;
            $material->decrement('quantity', $reserved);

            expect($material->quantity)->toBe(15);
        });

        it('tracks low stock materials', function () {
            $lowStock = Product::factory()
                ->create(['category' => ProductCategory::MATERIAL, 'quantity' => 2]);

            expect($lowStock->quantity)->toBeLessThan(5);
        });
    });

    describe('Material Filtering & Search', function () {
        it('can search materials by name', function () {
            Product::factory()->create([
                'name' => 'Дамаск сталь 32 шари',
                'category' => ProductCategory::MATERIAL,
            ]);
            Product::factory()->create([
                'name' => 'Крок нержавіюча сталь',
                'category' => ProductCategory::MATERIAL,
            ]);
            Product::factory()->create([
                'name' => 'Шкіра для рукояток',
                'category' => ProductCategory::MATERIAL,
            ]);

            $steelMaterials = Product::where('name', 'like', '%сталь%')
                ->where('category', ProductCategory::MATERIAL->value)
                ->get();

            expect($steelMaterials->count())->toBe(2);
        });

        it('can search materials by SKU', function () {
            Product::factory()->create([
                'sku' => 'MAT-STEEL-DAMASK-001',
                'category' => ProductCategory::MATERIAL,
            ]);

            $found = Product::where('sku', 'like', '%DAMASK%')
                ->where('category', ProductCategory::MATERIAL->value)
                ->first();

            expect($found)->not->toBeNull();
        });

        it('can filter materials by price range', function () {
            Product::factory()->create([
                'category' => ProductCategory::MATERIAL,
                'price' => 500,
            ]);
            Product::factory()->create([
                'category' => ProductCategory::MATERIAL,
                'price' => 1200,
            ]);
            Product::factory()->create([
                'category' => ProductCategory::MATERIAL,
                'price' => 2500,
            ]);

            $premium = Product::whereBetween('price', [1000, 2000])
                ->where('category', ProductCategory::MATERIAL->value)
                ->get();

            expect($premium)->toHaveCount(1);
        });

        it('can order materials by price', function () {
            Product::factory()->create([
                'category' => ProductCategory::MATERIAL,
                'price' => 2000,
            ]);
            Product::factory()->create([
                'category' => ProductCategory::MATERIAL,
                'price' => 500,
            ]);
            Product::factory()->create([
                'category' => ProductCategory::MATERIAL,
                'price' => 1500,
            ]);

            $expensive = Product::where('category', ProductCategory::MATERIAL->value)
                ->orderBy('price', 'desc')
                ->first();

            expect($expensive->price)->toEqual('2000.00');
        });

        it('can order materials by creation date', function () {
            $old = Product::factory()->create(['category' => ProductCategory::MATERIAL]);
            sleep(1);
            $new = Product::factory()->create(['category' => ProductCategory::MATERIAL]);

            $latest = Product::where('category', ProductCategory::MATERIAL->value)
                ->orderBy('created_at', 'desc')
                ->first();

            expect($latest->id)->toBe($new->id);
        });

        it('can search by description', function () {
            Product::factory()->create([
                'description' => 'Преміальна дамаська сталь для елітних ножів',
                'category' => ProductCategory::MATERIAL,
            ]);
            Product::factory()->create([
                'description' => 'Звичайна нержавіюча сталь',
                'category' => ProductCategory::MATERIAL,
            ]);

            $found = Product::where('description', 'like', '%дамаська%')
                ->where('category', ProductCategory::MATERIAL->value)
                ->first();

            expect($found)->not->toBeNull();
        });
    });

    describe('Material vs Knife Separation', function () {
        it('does not confuse materials with knives', function () {
            Product::factory(5)->create(['category' => ProductCategory::KNIFE]);
            Product::factory(3)->create(['category' => ProductCategory::MATERIAL]);

            $materials = Product::where('category', ProductCategory::MATERIAL->value)->get();
            $knives = Product::where('category', ProductCategory::KNIFE->value)->get();

            expect($materials)->toHaveCount(3);
            expect($knives)->toHaveCount(5);
        });

        it('materials should not have knife dimensions', function () {
            $material = Product::factory()
                ->create([
                    'category' => ProductCategory::MATERIAL,
                    'total_length' => null,
                    'blade_length' => null,
                    'blade_thickness' => null,
                ]);

            expect($material->total_length)->toBeNull();
            expect($material->blade_length)->toBeNull();
            expect($material->blade_thickness)->toBeNull();
        });

        it('materials should not have knife collection', function () {
            $material = Product::factory()
                ->create([
                    'category' => ProductCategory::MATERIAL,
                    'collection' => null,
                ]);

            expect($material->collection)->toBeNull();
        });
    });
});
