<?php

use App\Enums\CurrencyType;
use App\Services\CartService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Session;
use Livewire\Attributes\On;
use App\Models\Product;
use Livewire\Component;

new class extends Component {
    public string $position = '';

    #[On('cart:add')]
    public function addToCart($productId, CartService $cart)
    {
        $cart->add($productId);
        unset($this->cartItems);

        $this->dispatch('cart-added');

        $this->js('$dispatch("show-cart")');
    }

    #[Computed]
    public function cartItems()
    {
        return collect(session('cart', []))->map(fn($item) => (object) $item);
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
        return $this->cartItems->sum(fn($item) => $item->price * $item->qty);
    }

    #[On('cart:clear')]
    public function clear(CartService $cart)
    {
        $cart->clear();
        unset($this->cartItems);
    }

    public function getTotalsByCurrency(): array
    {
        $cart = session()->get('cart', []);
        $totals = [];

        foreach ($cart as $item) {
            $currencyCode = $item['currency'];
            $subtotal = $item['price'] * $item['qty'];

            if (!isset($totals[$currencyCode])) {
                $totals[$currencyCode] = 0;
            }

            $totals[$currencyCode] += $subtotal;
        }

        return $totals;
    }
};
?>

<x-offcanvas :position="$this->position" size="xl" x-on:show-cart.window="open = true">
    <x-slot:trigger>
        <x-lucide-shopping-cart class="size-6" />

        @if ($this->cartItems->isNotEmpty())
            <span
                class="absolute -top-1.5 -right-1.5 flex size-4 items-center justify-center rounded-full bg-orange-500 text-[10px] font-bold text-white shadow-sm">
                {{ $this->cartItems->sum('qty') }}
            </span>
        @endif
    </x-slot:trigger>

    <x-slot:header>Кошик</x-slot:header>

    @if ($this->cartItems->isEmpty())
        <!-- Стан: Порожньо -->
        <div class="flex flex-col justify-center items-center size-full text-center">
            <x-lucide-shopping-cart class="size-15 opacity-50" stroke-width="1.5" />
            <span class="font-semibold text-lg mt-5">Кошик порожній</span>
            <span class="text-zinc-500 text-sm max-w-2xs">
                Перегляньте товари та додайте їх до кошика, щоб зробити замовлення
            </span>
            <x-button x-on:click="open = false" color="dark" class="mt-8 px-10">
                До покупок
            </x-button>
        </div>
    @else
        <div class="flex flex-col">
            <div class="flex justify-between items-end">
                <span class="text-xs font-bold uppercase tracking-wider text-zinc-500">
                    Ваше замовлення
                </span>
                <span class="text-sm text-zinc-900 font-medium">
                    {{ $this->cartItems->count() }} тов.
                </span>
            </div>

            <div class="flex flex-col divide-y divide-zinc-100">
                @each('partials.cart.items', $this->cartItems, 'item')
            </div>
        </div>
    @endif

    @if ($this->cartItems->isNotEmpty())
        <x-slot:footer class="border-t border-zinc-100">
            <div class="flex justify-between items-center mb-5">
                <span class="text-zinc-500 font-semibold">Разом до сплати:</span>
                <span class="text-2xl font-bold text-zinc-900">
                    <div class="flex flex-col gap-0.5 text-right">
                        @foreach ($this->getTotalsByCurrency() as $currencyCode => $amount)
                            <div class="text-xl font-bold text-zinc-900">
                                {{ CurrencyType::tryFrom($currencyCode)?->format($amount) }}
                            </div>
                        @endforeach
                    </div>
                </span>
            </div>

            <div class="flex flex-col gap-2.5 items-center">
                <a href="{{ route('checkout') }}"
                    class="bg-black hover:bg-zinc-900 text-white py-3.5 px-10 text-sm inline-flex items-center justify-center rounded-md font-medium w-fit"
                    wire:navigate>
                    Оформити замовлення
                </a>
                <button x-on:click="open = null" class="w-fit text-sm text-zinc-500 font-medium py-2.5 cursor-pointer">
                    Продовжити покупки
                </button>
            </div>
        </x-slot:footer>
    @endif
</x-offcanvas>
