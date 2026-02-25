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
        // 1. Спочатку обираємо категорію, щоб підлаштувати під неї характеристики
        $category = fake()->randomElement(ProductCategory::cases());

        // 2. Логіка специфічних параметрів залежно від категорії
        $specs = match ($category) {
            ProductCategory::KITCHEN => [
                'steel' => fake()->randomElement([SteelType::VG10->value, SteelType::N690->value]),
                'blade_grind' => BladeGrind::FLAT->value,
                'blade_thickness' => fake()->randomFloat(1, 1.5, 2.5),
                'handle_material' => fake()->randomElement([HandleMaterial::WOOD->value, HandleMaterial::G10->value]),
                'blade_finish' => fake()->randomElement([BladeFinish::SATIN->value, BladeFinish::SATIN->value]),
                'sheath' => SheathType::NONE->value,
            ],
            ProductCategory::TACTICAL => [
                'steel' => fake()->randomElement([SteelType::D2->value, SteelType::S35VN->value]),
                'blade_grind' => fake()->randomElement([BladeGrind::FLAT->value, BladeGrind::HOLLOW->value]),
                'blade_thickness' => fake()->randomFloat(1, 4.0, 6.0),
                'handle_material' => fake()->randomElement([HandleMaterial::G10->value, HandleMaterial::ELASTRON->value]),
                'blade_finish' => fake()->randomElement([BladeFinish::BLACK_OXIDE->value, BladeFinish::DLC->value, BladeFinish::STONEWASH->value]),
                'sheath' => SheathType::KYDEX->value,
            ],
            default => [
                'steel' => fake()->randomElement(SteelType::values()),
                'blade_grind' => fake()->randomElement(BladeGrind::values()),
                'blade_thickness' => fake()->randomFloat(1, 2.4, 4.5),
                'handle_material' => fake()->randomElement(HandleMaterial::values()),
                'blade_finish' => fake()->randomElement(BladeFinish::values()),
                'sheath' => fake()->randomElement(SheathType::values()),
            ],
        };

        return array_merge([
            'name' => fake()->words(2, true),
            'slug' => fake()->unique()->slug(),
            'sku' => fake()->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'price' => fake()->randomFloat(2, 1200, 15000),
            'description' => fake()->paragraph(4),
            'quantity' => fake()->numberBetween(0, 15),
            'is_active' => fake()->boolean(95),
            'category' => $category->value,
            'total_length' => fake()->randomFloat(1, 180, 320),
            'blade_length' => fake()->randomFloat(1, 90, 200),
            'blade_shape' => fake()->randomElement(BladeShape::values()),
        ], $specs);
    }

    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            collect(range(1, rand(2, 6)))->each(function () use ($product) {
                $path = database_path("seeders/images/product-test-" . rand(1, 12) . ".jpg");

                if (file_exists($path)) {
                    $product->addMedia($path)
                        ->preservingOriginal()
                        ->toMediaCollection('images');
                }
            });
        });
    }
}
