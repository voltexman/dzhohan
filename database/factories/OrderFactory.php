<?php

namespace Database\Factories;

use App\Enums\DeliveryMethod;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'number' => 'ORD-'.fake()->unique()->numberBetween(10000, 99999),
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->optional()->safeEmail(),
            'delivery_method' => fake()->randomElement(DeliveryMethod::values()),
            'city' => fake()->city(),
            'address' => 'Відділення №'.fake()->numberBetween(1, 50),
            'total_price' => fake()->randomFloat(2, 500, 5000),
            'status' => fake()->randomElement(OrderStatus::values()),
            'created_at' => now(),
        ];
    }
}
