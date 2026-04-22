<?php

use App\Enums\CurrencyType;
use App\Enums\KnifeCollection;
use App\Enums\PostType;
use App\Enums\ProductCategory;
use App\Models\Post;
use App\Models\Product;

describe('Unit Tests - Model Properties (No Database)', function () {
    describe('Product Model Attributes', function () {
        it('product casts category to ProductCategory enum', function () {
            $product = Product::factory()
                ->make(['category' => ProductCategory::KNIFE]);

            expect($product->category)->toBe(ProductCategory::KNIFE);
            expect($product->category)->toBeInstanceOf(ProductCategory::class);
        });

        it('product casts collection to KnifeCollection enum', function () {
            $product = Product::factory()
                ->make(['collection' => KnifeCollection::TACTICAL]);

            expect($product->collection)->toBe(KnifeCollection::TACTICAL);
        });

        it('product casts currency to CurrencyType enum', function () {
            $product = Product::factory()
                ->make(['currency' => CurrencyType::USD]);

            expect($product->currency)->toBe(CurrencyType::USD);
        });

        it('product casts is_active to boolean', function () {
            $active = Product::factory()->make(['is_active' => true]);
            $inactive = Product::factory()->make(['is_active' => false]);

            expect($active->is_active)->toBeTrue();
            expect($inactive->is_active)->toBeFalse();
        });

        it('product casts additional_attributes as array', function () {
            $attributes = [
                'material' => 'stainless_steel',
                'weight' => '185g',
            ];

            $product = Product::factory()
                ->make(['additional_attributes' => $attributes]);

            expect($product->additional_attributes)->toBeArray();
            expect($product->additional_attributes['material'])->toBe('stainless_steel');
        });
    });

    describe('Post Model Attributes', function () {
        it('post casts type to PostType enum', function () {
            $post = Post::factory()->make();

            expect($post->type)->toBeInstanceOf(PostType::class);
        });
    });

    describe('Enum Validation', function () {
        it('ProductCategory has knife and material values', function () {
            $cases = ProductCategory::cases();

            expect($cases)->toHaveCount(2);
            expect(in_array(ProductCategory::KNIFE, $cases))->toBeTrue();
            expect(in_array(ProductCategory::MATERIAL, $cases))->toBeTrue();
        });

        it('KnifeCollection has all required values', function () {
            $cases = KnifeCollection::cases();

            expect($cases)->toHaveCount(5);
            expect(in_array(KnifeCollection::TACTICAL, $cases))->toBeTrue();
            expect(in_array(KnifeCollection::KITCHEN, $cases))->toBeTrue();
            expect(in_array(KnifeCollection::HUNTING, $cases))->toBeTrue();
            expect(in_array(KnifeCollection::EDC, $cases))->toBeTrue();
            expect(in_array(KnifeCollection::OUTDOOR, $cases))->toBeTrue();
        });

        it('CurrencyType has all required values', function () {
            $values = CurrencyType::cases();

            expect($values)->toHaveCount(3);
            expect(in_array(CurrencyType::UAH, $values))->toBeTrue();
            expect(in_array(CurrencyType::USD, $values))->toBeTrue();
            expect(in_array(CurrencyType::EUR, $values))->toBeTrue();
        });

        it('ProductCategory enum has labels', function () {
            expect(ProductCategory::KNIFE->getLabel())->not->toBeEmpty();
            expect(ProductCategory::MATERIAL->getLabel())->not->toBeEmpty();
        });

        it('KnifeCollection enum has labels', function () {
            foreach (KnifeCollection::cases() as $case) {
                expect($case->getLabel())->not->toBeEmpty();
            }
        });

        it('CurrencyType enum has labels', function () {
            foreach (CurrencyType::cases() as $case) {
                expect($case->getLabel())->not->toBeEmpty();
            }
        });
    });

    describe('Product Methods', function () {
        it('product can determine stock status without database', function () {
            $inStock = Product::factory()->make(['quantity' => 5]);
            $soldOut = Product::factory()->make(['quantity' => 0]);

            expect($inStock->isSold())->toBeFalse();
            expect($soldOut->isSold())->toBeTrue();
        });

        it('product can check if has stock without database', function () {
            $available = Product::factory()->make(['quantity' => 1]);
            $unavailable = Product::factory()->make(['quantity' => 0]);

            expect($available->hasStock())->toBeTrue();
            expect($unavailable->hasStock())->toBeFalse();
        });

        it('product stock attribute works', function () {
            $product = Product::factory()->make(['quantity' => 10]);

            expect($product->stock)->toBe(10);
        });
    });
});
