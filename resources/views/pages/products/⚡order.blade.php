<?php

use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Computed;
use App\Services\CartService;
use Livewire\Component;

new class extends Component {
    #[Validate('required|min:3', message: 'Вкажіть ваше ім’я')]
    public string $name = '';

    #[Validate('required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10', message: 'Некоректний формат телефону')]
    public string $phone = '';

    #[Validate('email', message: 'Вкажіть електронну пошту')]
    public string $email = '';

    public bool $subscribe = false;

    #[Validate('required', message: 'Оберіть спосіб доставки')]
    public string $delivery = 'nova_poshta';

    #[Validate('required', message: 'Вкажіть місто та відділення')]
    public string $address = '';

    public string $comment = '';

    protected function cartService(): CartService
    {
        return app(CartService::class);
    }

    #[Computed]
    public function items()
    {
        return $this->cartService()->cart();
    }

    #[Computed]
    public function total()
    {
        return $this->items->sum(fn($item) => $item->price * $item->qty);
    }

    public function checkout()
    {
        dd($this);
        $this->validate();

        if ($this->items->isEmpty()) {
            return session()->flash('error', 'Ваш кошик порожній');
        }

        // Логіка створення замовлення в БД
        // $order = Order::create([...]);

        // Очищення кошика після успіху
        session()->forget('cart');

        return redirect()->route('home')->with('success', 'Дякуємо! Замовлення прийнято.');
    }
};
?>

@section('header')
    <header class="relative h-50 w-full bg-cover bg-center bg-no-repeat"
        style="background-image: url('{{ Vite::asset('resources/images/header.png') }}')">

        {{-- затемнення --}}
        <div class="absolute inset-0 bg-black/60"></div>

        {{-- контент поверх --}}
        <div class="relative z-10 flex flex-col items-center justify-center h-full">
            <h1 class="text-zinc-200 text-3xl md:text-6xl font-bold text-center max-w-lg font-[Russo_One]">
                Замовлення
            </h1>
        </div>
    </header>
@endsection

<x-section sidebar-position="right">
    <h1 class="text-xl font-[Russo_One] mb-10 uppercase tracking-tight">Оформлення замовлення</h1>

    <!-- Ліва частина: Форма -->
    <form wire:submit="checkout" class="lg:col-span-7 space-y-10">
        <div>
            <h2 class="text-lg font-semibold font-[SN_Pro] mb-2.5 flex items-center gap-1.5">
                <x-lucide-user class="size-5" /> Контактні дані
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <x-form.input wire:model="name" icon="user-round" placeholder="Прізвище та ім'я" />
                    <x-form.hint>
                        Також використовуватиметься для відправки поштою
                    </x-form.hint>
                </div>

                <div>
                    <x-form.input wire:model="phone" x-mask="+999 (99) 999-99-99" icon="phone"
                        placeholder="Номер телефону" />
                </div>

                <div class="space-y-2.5">
                    <x-form.input wire:model="email" icon="mail" placeholder="Електронна пошта" />
                    <x-form.checkbox wire:model="subscribe" label="Отримувати статті, новини та пропозиції" />
                </div>
            </div>
        </div>

        <div>
            <h2 class="text-lg font-semibold font-[SN_Pro] mb-2.5 flex items-center gap-1.5">
                <x-lucide-truck class="size-5" /> Доставка
            </h2>
            <div class="space-y-5">
                <div class="max-w-sm grid grid-cols-2 gap-5">
                    <!-- Нова Пошта -->
                    <button type="button" wire:click="$set('delivery', 'nova_poshta')"
                        :class="$wire.delivery === 'nova_poshta' ? 'border-zinc-600 ring-1 ring-zinc-600 bg-zinc-100' :
                            'border-zinc-200 bg-zinc-50'"
                        class="relative flex flex-col items-center justify-center p-5 border-2 rounded-lg transition-all duration-300 hover:border-zinc-400 group">

                        <div class="h-12 flex items-center justify-center mb-2.5">
                            <img src="https://poletehnika.com.ua/image/cache/webp/catalog/e-mag/landing/np-logomark-red.webp"
                                alt="Нова Пошта"
                                class="max-h-full object-contain grayscale group-hover:grayscale-0 transition-all"
                                :class="$wire.delivery === 'nova_poshta' ? 'grayscale-0' : ''">
                        </div>
                        <span class="text-sm font-bold">Нова Пошта</span>

                        <div x-show="$wire.delivery === 'nova_poshta'" class="absolute top-2 right-2">
                            <x-lucide-check-circle-2 class="size-5 text-zinc-800 fill-white" />
                        </div>
                    </button>

                    <!-- Укрпошта -->
                    <button type="button" wire:click="$set('delivery', 'ukr_poshta')"
                        :class="$wire.delivery === 'ukr_poshta' ? 'border-zinc-600 ring-1 ring-zinc-600 bg-zinc-100' :
                            'border-zinc-200 bg-zinc-50'"
                        class="relative flex flex-col items-center justify-center p-5 border-2 rounded-lg transition-all duration-300 hover:border-zinc-400 group">

                        <div class="h-12 flex items-center justify-center mb-2.5">
                            <img src="https://www.ukrposhta.ua/doc/for-media/logo_pin_ukrpost_2026.png" alt="Укрпошта"
                                class="max-h-full object-contain grayscale group-hover:grayscale-0 transition-all"
                                :class="$wire.delivery === 'ukr_poshta' ? 'grayscale-0' : ''">
                        </div>
                        <span class="text-sm font-bold">Укрпошта</span>

                        <div x-show="$wire.delivery === 'ukr_poshta'" class="absolute top-2 right-2">
                            <x-lucide-check-circle-2 class="size-5 text-zinc-800 fill-white" />
                        </div>
                    </button>
                </div>

                <div class="max-w-xl">
                    <x-form.textarea wire:model="address" rows="3"
                        placeholder="Місто, номер відділення або повна адреса" />
                    <x-form.hint>
                        Lorem ipsum dolor sit, amet consectetur adipisicing elit.
                    </x-form.hint>
                </div>
            </div>
        </div>

        <div>
            <h2 class="text-lg font-semibold font-[SN_Pro] mb-2.5 flex items-center gap-1.5">
                <x-lucide-message-square class="size-5" /> Коментар
                <span class="italic text-gray-700 font-normal">(опційно)</span>
            </h2>
            <x-form.textarea wire:model="comment" rows="5" placeholder="Ваші побажання до замовлення..." />
        </div>

        <x-button type="submit" color="dark" size="lg" class="w-full py-5 text-lg shadow-2xl shadow-black/10">
            <span wire:loading.remove wire:target="checkout">Підтвердити замовлення</span>
            <span wire:loading wire:target="checkout">Обробка...</span>
            <x-lucide-loader-circle wire:loading wire:target="checkout" class="size-5 animate-spin ms-1.5" />
        </x-button>
    </form>

    <x-slot:sidebar>
        <!-- Права частина: Ваше замовлення -->
        <div class="sticky top-24 pt-">
            <h2 class="text-xl font-bold mb-6">Ваше замовлення</h2>

            <div class="divide-y divide-zinc-200 mb-6">
                @foreach ($this->items as $item)
                    <div class="py-4 flex justify-between gap-4">
                        <div class="flex flex-col">
                            <span class="font-medium text-gray-900 leading-tight">{{ $item->name }}</span>
                            <span class="text-sm text-gray-500">{{ $item->qty }} шт. ×
                                {{ number_format($item->price, 0, '.', ' ') }} грн</span>
                        </div>
                        <span class="font-bold whitespace-nowrap">
                            {{ number_format($item->price * $item->qty, 0, '.', ' ') }} грн
                        </span>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-between items-center pt-6 border-t-2 border-zinc-200">
                <span class="text-lg text-gray-600">Разом до сплати:</span>
                <span class="text-3xl font-black text-black tracking-tighter">
                    {{ number_format($this->total, 0, '.', ' ') }} <small class="text-sm font-normal">грн</small>
                </span>
            </div>

            <div class="mt-8 p-4 bg-sky-50 rounded-xl flex gap-3 text-sm text-sky-800">
                <x-lucide-info class="size-5 shrink-0" />
                <span>Наш менеджер зателефонує вам для підтвердження замовлення протягом 15 хвилин.</span>
            </div>
        </div>
    </x-slot:sidebar>
</x-section>
