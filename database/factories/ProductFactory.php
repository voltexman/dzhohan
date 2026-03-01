<?php

namespace Database\Factories;

use App\Enums\BladeFinish;
use App\Enums\BladeGrind;
use App\Enums\BladeShape;
use App\Enums\HandleMaterial;
use App\Enums\ProductCategory;
use App\Enums\SheathType;
use App\Enums\SteelType;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name'            => fake()->words(2, true),
            'slug'            => fake()->unique()->slug(),
            'sku'             => fake()->unique()->bothify('??-####'),
            'description'     => fake()->paragraph(3),
            'price'           => fake()->randomFloat(2, 1500, 12000),
            'quantity'        => fake()->numberBetween(0, 20),
            'is_active'       => fake()->boolean(90),
            'category'        => fake()->randomElement(ProductCategory::cases()),

            // Характеристики (Enums)
            'steel'           => fake()->randomElement(SteelType::cases()),
            'blade_shape'     => fake()->randomElement(BladeShape::cases()),
            'blade_finish'    => fake()->randomElement(BladeFinish::cases()),
            'blade_grind'     => fake()->randomElement(BladeGrind::cases()),
            'handle_material' => fake()->randomElement(HandleMaterial::cases()),
            'sheath'          => fake()->randomElement(SheathType::cases()),

            // Розміри
            'total_length'    => fake()->randomFloat(1, 150, 350),
            'blade_length'    => fake()->randomFloat(1, 70, 220),
            'blade_thickness' => fake()->randomFloat(1, 1.5, 6.0),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            collect(range(1, rand(2, 6)))->each(function () use ($product) {
                $path = database_path('seeders/images/product-test-' . rand(1, 12) . '.jpg');

                if (file_exists($path)) {
                    $product->addMedia($path)
                        ->preservingOriginal()
                        ->toMediaCollection('images');
                }
            });
        });
    }
}
