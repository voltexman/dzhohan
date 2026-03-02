<?php

namespace Database\Factories;

use App\Enums\DeliveryMethod;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'number' => now()->format('dy') . '-' . fake()->unique()->numberBetween(1000, 9999),
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->optional()->safeEmail(),
            'delivery_method' => fake()->randomElement(DeliveryMethod::cases()),
            'city' => fake()->city(),
            'address' => 'Відділення №' . fake()->numberBetween(1, 50),
            'comment' => fake()->optional()->sentence(15),
            'total_price' => 0, // Буде перераховано після додавання товарів
            'type' => fake()->randomElement(OrderType::cases()),
            'status' => fake()->randomElement(OrderStatus::cases()),
            'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Order $order) {
            $products = Product::all();

            // Якщо товарів немає, нічого не додаємо
            if ($products->isEmpty()) return;

            $totalPrice = 0;
            $itemsCount = rand(1, 5);

            for ($i = 0; $i < $itemsCount; $i++) {
                $product = $products->random();
                $qty = rand(1, 3);
                $price = $product->price;

                $order->products()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'qty' => $qty,
                    'price' => $price,
                    'custom_options' => null, // Можна додати JSON, якщо потрібно
                ]);

                $totalPrice += ($price * $qty);
            }

            // Оновлюємо фінальну ціну замовлення
            $order->update(['total_price' => $totalPrice]);
        });
    }
}
