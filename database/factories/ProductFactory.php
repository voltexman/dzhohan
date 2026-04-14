<?php

namespace Database\Factories;

use App\Enums\CurrencyType;
use App\Enums\KnifeCollection;
use App\Enums\ProductCategory;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $category = fake()->randomElement(ProductCategory::cases());

        return [
            'name' => fake()->words(2, true),
            'slug' => fake()->unique()->slug(),
            'sku' => fake()->unique()->bothify('??-####'),
            'description' => fake()->paragraph(3),
            'price' => fake()->randomFloat(1500, 12000),
            'is_active' => fake()->boolean(90),
            'short_youtube_video_id' => fake()->optional()->passthrough(function () {
                $videos = ['k32Cc4koohY', 't2aHPnXP6Og', 'FfvZsRZ0_-E'];

                return fake()->randomElement($videos);
            }),

            'category' => $category,

            'currency' => $category === ProductCategory::MATERIAL
                ? CurrencyType::UAH
                : fake()->randomElement(CurrencyType::cases()),

            'quantity' => $category === ProductCategory::MATERIAL
                ? fake()->numberBetween(1, 10)
                : fake()->numberBetween(0, 1),

            'collection' => $category === ProductCategory::KNIFE
                ? fake()->randomElement(KnifeCollection::cases())
                : null,

            // Розміри тільки для ножів
            'total_length' => $category === ProductCategory::KNIFE
                ? fake()->randomFloat(1, 150, 350)
                : null,

            'blade_length' => $category === ProductCategory::KNIFE
                ? fake()->randomFloat(1, 70, 220)
                : null,

            'blade_thickness' => $category === ProductCategory::KNIFE
                ? fake()->randomFloat(1, 1.5, 6.0)
                : null,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            collect(range(1, rand(2, 6)))->each(function () use ($product) {
                $path = database_path('seeders/images/product-test-'.rand(1, 12).'.jpg');

                if (file_exists($path)) {
                    $product->addMedia($path)
                        ->preservingOriginal()
                        ->toMediaCollection('products');
                }
            });
        });
    }
}
