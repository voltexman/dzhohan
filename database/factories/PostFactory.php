<?php

namespace Database\Factories;

use App\Enums\PostType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->sentence();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'text' => fake()->optional()->paragraph(10),
            'type' => fake()->randomElement(PostType::values()),
        ];
    }
}
