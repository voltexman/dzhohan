<?php

use App\Models\Product;
use App\Services\CartService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Session;
use Livewire\Component;

new class extends Component
{
    public string $mode = 'top'; // top | left

    public ?string $open = null; // search | cart | menu

    #[Session]
    public ?string $search = '';

    #[Computed]
    public function results()
    {
        if (strlen($this->search) < 2) {
            return collect();
        }

        return Product::where('name', 'like', '%'.$this->search.'%')
            ->limit(5)
            ->get();
    }

    #[On('cart:add')]
    public function addToCart($productId, CartService $cart)
    {
        $cart->add($productId);
        $this->open = 'cart';
        unset($this->cartItems);
    }

    #[Computed]
    public function cartItems()
    {
        return collect(session('cart', []))->map(fn ($item) => (object) $item);
    }

    public function increment($productId, CartService $cart)
    {
        $cart->increment($productId);
        unset($this->cartItems);
    }

    public function decrement($productId, CartService $cart)
    {
        $cart->decrement($productId);
        unset($this->cartItems);
    }

    public function remove($productId, CartService $cart)
    {
        $cart->remove($productId);
        unset($this->cartItems);
    }

    #[Computed]
    public function total()
    {
        return $this->cartItems->sum(fn ($item) => $item->price * $item->qty);
    }
};
