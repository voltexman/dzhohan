<?php

use App\Services\CartService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Session;
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
};
?>

<div x-data="{
    open: false,
    mobileFullWidth: true,

    // 'start', 'end', 'top', 'bottom'
    position: '{{ $position }}',

    // 'xs', 'sm', 'md', 'lg', 'xl'
    size: 'xl',

    // Set transition classes based on position
    transitionClasses: {
        'x-transition:enter-start'() {
            if (this.position === 'start') {
                return '-translate-x-full rtl:translate-x-full';
            } else if (this.position === 'end') {
                return 'translate-x-full rtl:-translate-x-full';
            } else if (this.position === 'top') {
                return '-translate-y-full';
            } else if (this.position === 'bottom') {
                return 'translate-y-full';
            }
        },
        'x-transition:leave-end'() {
            if (this.position === 'start') {
                return '-translate-x-full rtl:translate-x-full';
            } else if (this.position === 'end') {
                return 'translate-x-full rtl:-translate-x-full';
            } else if (this.position === 'top') {
                return '-translate-y-full';
            } else if (this.position === 'bottom') {
                return 'translate-y-full';
            }
        },
    },
}" x-on:keydown.esc.prevent="open = false" x-on:show-cart.window="open = true">
    {{-- Trigger --}}
    <button x-on:click="open = true" type="button"
        class="relative rounded-md p-1.5 cursor-pointer transition-colors duration-500">
        <x-lucide-shopping-cart class="size-6" />

        @if ($this->cartItems->isNotEmpty())
            <span
                class="absolute -top-1.5 -right-1.5 flex size-4 items-center justify-center rounded-full bg-orange-500 text-[10px] font-bold text-white shadow-sm">
                {{ $this->cartItems->sum('qty') }}
            </span>
        @endif
    </button>
    {{-- End Trigger --}}

    <template x-teleport="body">
        <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" x-bind:aria-hidden="!open" tabindex="-1" role="dialog"
            aria-labelledby="pm-offcanvas-title"
            class="z-90 fixed inset-0 overflow-hidden bg-stone-900/60 backdrop-blur-sm"
            x-effect="document.body.style.overflow = open ? 'hidden' : 'auto'">
            <!-- Offcanvas Sidebar -->
            <div x-cloak x-show="open" x-on:click.away="open = false" x-bind="transitionClasses"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-end="translate-x-0 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="translate-x-0 translate-y-0" role="document"
                class="absolute flex w-full flex-col bg-white shadow-lg will-change-transform"
                x-bind:class="{
                    'h-dvh top-0 end-0': position === 'end',
                    'h-dvh top-0 start-0': position === 'start',
                    'bottom-0 start-0 end-0': position === 'top',
                    'bottom-0 start-0 end-0': position === 'bottom',
                    'h-64': position === 'top' || position === 'bottom',
                    'sm:max-w-xs': size === 'xs' && !(position === 'top' || position === 'bottom'),
                    'sm:max-w-sm': size === 'sm' && !(position === 'top' || position === 'bottom'),
                    'sm:max-w-md': size === 'md' && !(position === 'top' || position === 'bottom'),
                    'sm:max-w-lg': size === 'lg' && !(position === 'top' || position === 'bottom'),
                    'sm:max-w-xl': size === 'xl' && !(position === 'top' || position === 'bottom'),
                    'max-w-72': !mobileFullWidth && !(position === 'top' || position === 'bottom'),
                }">
                <!-- Header -->
                <div class="flex min-h-16 flex-none items-center justify-between border-b border-zinc-50 px-6 md:px-10">
                    <h3 id="offcanvas-title" class="py-5 font-medium">Кошик</h3>

                    <!-- Close Button -->
                    <button x-on:click="open = false" type="button"
                        class="absolute top-3 right-3 inline-flex items-center justify-center size-8 rounded-full bg-black text-zinc-50 hover:bg-zinc-800 hover:text-zinc-200 transition-colors duration-300 cursor-pointer">
                        <x-lucide-x class="-mx-1 inline-block size-4" />
                    </button>
                    <!-- END Close Button -->
                </div>
                <!-- END Header -->

                <!-- Content -->
                <div class="flex grow flex-col overflow-y-auto p-5 md:p-7">
                    <div x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-4"
                        class="flex flex-col size-full max-w-xl mx-auto">
                        <div class="flex flex-col grow overflow-y-auto">
                            @if ($this->cartItems->isEmpty())
                                <!-- Стан: Порожньо -->
                                <div class="flex flex-col justify-center items-center size-full text-center">
                                    <x-lucide-shopping-cart class="size-15 opacity-50" stroke-width="1.5" />
                                    <span class="font-semibold text-lg mt-5">Кошик порожній</span>
                                    <span class="text-gray-500 text-sm max-w-2xs">
                                        Перегляньте товари та додайте їх до кошика, щоб зробити замовлення
                                    </span>
                                    <x-button x-on:click="open = false" color="dark" class="mt-8 px-10">
                                        До покупок
                                    </x-button>
                                </div>
                            @else
                                <!-- Список товарів у кошику -->
                                <div class="flex flex-col">
                                    <div class="flex justify-between items-end">
                                        <span class="text-xs font-bold uppercase tracking-wider text-gray-500">
                                            Ваше замовлення
                                        </span>
                                        <span class="text-sm text-gray-900 font-medium">
                                            {{ $this->cartItems->count() }} тов.
                                        </span>
                                    </div>

                                    <div class="flex flex-col divide-y divide-zinc-100">
                                        @foreach ($this->cartItems as $item)
                                            <div class="py-5 flex gap-5 items-center">
                                                <div class="size-20 bg-zinc-50 rounded-md overflow-hidden shrink-0">
                                                    <img src="{{ Vite::asset('resources/images/header.png') }}"
                                                        class="size-full object-cover" alt="">
                                                </div>

                                                <div class="flex flex-col grow">
                                                    <span class="font-medium text-gray-900 leading-tight line-clamp-1">
                                                        {{ $item->name }}
                                                    </span>
                                                    <span class="text-sm text-gray-500 mt-1">
                                                        {{ number_format($item->price, 0, '.', ' ') }} грн
                                                    </span>

                                                    <!-- Керування кількісqтю -->
                                                    <div class="flex items-center gap-2.5 mt-1.5">
                                                        <x-button color="light" size="xs" icon
                                                            class="rounded-full!"
                                                            wire:click="decrement({{ $item->id }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="decrement({{ $item->id }})">

                                                            <x-lucide-plus wire:loading.remove
                                                                wire:target="decrement({{ $item->id }})"
                                                                class="size-3" />

                                                            <x-lucide-loader-circle wire:loading
                                                                wire:target="decrement({{ $item->id }})"
                                                                class="size-3 animate-spin" />
                                                        </x-button>

                                                        <span class="text-sm font-semibold">{{ $item->qty }}</span>

                                                        <x-button color="light" size="xs" icon
                                                            class="rounded-full!"
                                                            wire:click="increment({{ $item->id }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="increment({{ $item->id }})">

                                                            <x-lucide-plus wire:loading.remove
                                                                wire:target="increment({{ $item->id }})"
                                                                class="size-3" />

                                                            <x-lucide-loader-circle wire:loading
                                                                wire:target="increment({{ $item->id }})"
                                                                class="size-3 animate-spin" />
                                                        </x-button>
                                                    </div>
                                                </div>

                                                <x-button variant="ghost" color="light" size="sm" icon
                                                    wire:click="remove({{ $item->id }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="remove({{ $item->id }})">

                                                    <x-lucide-trash wire:loading.remove
                                                        wire:target="remove({{ $item->id }})"
                                                        class="size-5 stroke-red-500" stroke-width="1.5" />

                                                    <x-lucide-loader-circle wire:loading
                                                        wire:target="remove({{ $item->id }})"
                                                        class="size-5 animate-spin stroke-red-500" />
                                                </x-button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Нижня частина з підсумком -->
                            @if ($this->cartItems->isNotEmpty())
                                <div class="pt-5 border-t border-zinc-100 mt-auto">
                                    <div class="flex justify-between items-center mb-6">
                                        <span class="text-gray-500 font-semibold">Разом до сплати:</span>
                                        <span class="text-2xl font-bold text-gray-900">
                                            {{ number_format($this->total, 0, '.', ' ') }} грн
                                        </span>
                                    </div>

                                    <div class="flex flex-col gap-2.5 items-center">
                                        <a href="{{ route('order') }}"
                                            class="bg-black hover:bg-gray-900 text-white py-3.5 px-10 text-sm inline-flex items-center justify-center rounded-full font-medium w-fit shadow-lg"
                                            wire:navigate>
                                            Оформити замовлення
                                        </a>
                                        <button wire:click="open = null"
                                            class="w-fit text-sm text-gray-500 font-medium py-2.5 cursor-pointer">
                                            Продовжити покупки
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- END Content -->
            </div>
            <!-- END Offcanvas Sidebar -->
        </div>
    </template>
</div>
