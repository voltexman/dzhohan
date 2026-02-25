<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'author_name' => fake()->name(),
            'body' => fake()->sentence(),
            'ip_address' => fake()->ipv4(),
            'is_approved' => true,
        ];
    }
}
