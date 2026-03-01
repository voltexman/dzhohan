<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'author_name' => fake()->optional()->name(),
            'body' => fake()->sentence(),
            'ip_address' => fake()->ipv4(),
            'parent_id' => null,
        ];
    }
}
