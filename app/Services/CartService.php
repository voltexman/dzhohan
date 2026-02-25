<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    protected string $key = 'cart';

    /**
     * Отримати кошик як колекцію об'єктів (для Blade)
     */
    public function cart(): Collection
    {
        return collect(session($this->key, []))->map(fn ($item) => (object) $item);
    }

    /**
     * Внутрішній метод для отримання сирого масиву із сесії (для запису)
     */
    protected function getRawCart(): array
    {
        return session($this->key, []);
    }

    protected function store(array $cart): void
    {
        session([$this->key => $cart]);
    }

    public function add(int $productId): void
    {
        $cart = $this->getRawCart(); // Беремо масив

        if (! isset($cart[$productId])) {
            $product = Product::find($productId);
            if (! $product) {
                return;
            }

            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image_url, // не забудьте про фото
                'qty' => 0,
            ];
        }

        $cart[$productId]['qty']++;
        $this->store($cart);
    }

    public function increment(int $productId, int $amount = 1): void
    {
        $cart = $this->getRawCart();

        if (isset($cart[$productId])) {
            $cart[$productId]['qty'] += $amount;
            $this->store($cart);
        }
    }

    public function decrement(int $productId): void
    {
        $cart = $this->getRawCart();

        if (isset($cart[$productId])) {
            $cart[$productId]['qty']--;

            if ($cart[$productId]['qty'] <= 0) {
                unset($cart[$productId]);
            }
            $this->store($cart);
        }
    }

    public function remove(int $productId): void
    {
        $cart = $this->getRawCart();

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            $this->store($cart);
        }
    }

    public function totalPrice(): float
    {
        // Використовуємо вже існуючу колекцію для підрахунку
        return $this->cart()->sum(fn ($item) => $item->price * $item->qty);
    }

    public function totalQuantity(): int
    {
        return $this->cart()->sum('qty');
    }
}
