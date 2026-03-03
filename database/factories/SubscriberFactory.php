<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriberFactory extends Factory
{
    public function definition(): array
    {
        return [
            'email' => fake()->safeEmail(),
            'name' => fake()->optional()->firstName(),
        ];
    }
}
