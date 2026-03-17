<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->optional()->name(),
            'contact' => fake()->optional()->randomElement([fake()->phoneNumber(), fake()->safeEmail()]),
            'rating' => fake()->numberBetween(1, 5),
            'text' => fake()->sentences(5, true),
            'is_selected' => fake()->boolean(),
        ];
    }
}
