<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TagFactory extends Factory
{
    public function definition(): array
    {
        // Набір тематичних тегів для ножів та блогу
        $tags = [
            'D2',
            'M390',
            'S35VN',
            'CPM-S90V',
            'Damascas', // Сталі
            'EDC',
            'Tactical',
            'Survival',
            'Bushcraft',
            'Hunting', // Призначення
            'G10',
            'Micarta',
            'Titanium',
            'Carbon Fiber',
            'Wood', // Матеріали
            'Folding',
            'Fixed Blade',
            'Flipper',
            'Frame Lock',
            'Axis Lock', // Конструкція
            'Review',
            'Maintenance',
            'Sharpening',
            'Collection', // Блог
        ];

        $name = $this->faker->unique()->randomElement($tags);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'type' => fake()->randomElement(['steel', 'material', 'usage', 'blog', null]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
