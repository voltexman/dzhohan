<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FeedbackFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->optional()->name(),
            'contact' => fake()->optional()->randomElement([fake()->phoneNumber(), fake()->safeEmail()]),
            'message' => fake()->text(rand(30, 950)),
            'is_active' => fake()->boolean(),
            'is_visible' => fake()->boolean(),
        ];
    }
}
