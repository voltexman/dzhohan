<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LikeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'visitor_token' => (string) Str::uuid(),
        ];
    }
}
