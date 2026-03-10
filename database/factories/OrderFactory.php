<?php

namespace Database\Factories;

use App\Enums\Order\DeliveryMethod;
use App\Enums\Order\OrderStatus;
use App\Enums\Order\OrderType;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'number' => str_replace('.', '', microtime(true)) . rand(10, 99),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'delivery_method' => fake()->randomElement(DeliveryMethod::cases()),
            'city' => fake()->city(),
            'address' => 'Відділення №' . fake()->numberBetween(1, 50),
            'comment' => fake()->optional()->sentence(15),
            'type' => fake()->randomElement(OrderType::cases()),
            'status' => fake()->randomElement(OrderStatus::cases()),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Order $order) {
            $products = Product::all();

            // Якщо товарів немає, нічого не додаємо
            if ($products->isEmpty()) {
                return;
            }

            $itemsCount = rand(1, 5);

            for ($i = 0; $i < $itemsCount; $i++) {
                $product = $products->random();
                $qty = rand(1, 3);
                $price = $product->price;

                $order->products()->create([
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'qty' => $qty,
                    'price' => $price,
                ]);
            }
        });
    }
}
