<?php

use App\Models\Order;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Computed;
use App\Services\CartService;
use Livewire\Component;

new class extends Component {
    #[Validate('required|min:3', message: 'Вкажіть ваше призвіще')]
    public string $first_name = '';

    #[Validate('required|min:3', message: 'Вкажіть ваше ім’я')]
    public string $last_name = '';

    #[Validate('required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10', message: 'Некоректний формат телефону')]
    public string $phone = '';

    #[Validate('email', message: 'Вкажіть електронну пошту')]
    public string $email = '';

    #[Validate('required', message: 'Оберіть спосіб доставки')]
    public string $delivery = 'nova_poshta';

    #[Validate('required', message: 'Оберіть ваше місто')]
    public string $city = '';

    #[Validate('required', message: 'Вкажіть місто та відділення')]
    public string $address = '';

    public string $comment = '';

    public bool $subscribe = false;

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
        // $this->validate();

        // 2. Створюємо замовлення
        $order = Order::create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'delivery' => $this->delivery,
            'city' => $this->city,
            'address' => $this->address,
            'comment' => $this->comment,
            'total_price' => $this->total(),
        ]);

        // 3. Переносимо товари з кошика в базу
        foreach ($this->items() as $item) {
            $order->products()->create([
                'product_id' => $item->id,
                'product_name' => $item->name,
                'qty' => $item->qty,
                'price' => $item->price,
            ]);
        }

        $this->dispatch('cart:clear');

        session()->flash('success-order', $order->number);
    }
};
?>

@section('header')
    <x-header :image="Vite::asset('resources/images/header.png')">
        <x-slot:title>Замовлення</x-slot:title>
    </x-header>
@endsection

@session('success-order')
    <div class="min-h-screen">{{ session('success-order') }}</div>
@else
    <x-section sidebar-position="right">
        <h1 class="text-xl font-[Russo_One] mb-10 uppercase tracking-tight">Оформлення замовлення</h1>

        <!-- Ліва частина: Форма -->
        <form wire:submit="checkout" class="lg:col-span-7 space-y-10">
            <div>
                <h2 class="text-lg font-semibold font-[SN_Pro] mb-2.5 flex items-center gap-1.5">
                    <x-lucide-user class="size-5" /> Контактні дані
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-form.group>
                        <x-form.label>Електронна пошта</x-form.label>
                        <x-form.input wire:model.trim="email" placeholder="example@gmail.com" />
                        <x-form.checkbox wire:model="subscribe" class="mt-1"
                            label="Отримувати статті, новини та пропозиції" />
                    </x-form.group>
                </div>
            </div>

            <div>
                <h2 class="text-lg font-semibold font-[SN_Pro] mb-2.5 flex items-center gap-1.5">
                    <x-lucide-truck class="size-5" /> Доставка
                </h2>
                <div class="space-y-5">
                    <div x-data="{ delivery: @entangle('delivery') }" class="w-full lg:max-w-lg grid grid-cols-3 lg:grid-cols-3 gap-2.5">
                        @foreach (App\Enums\Order\DeliveryMethod::cases() as $method)
                            <button type="button" @click="delivery = '{{ $method->value }}'"
                                :class="delivery === '{{ $method->value }}' ?
                                    'border-orange-500/50 ring-1 ring-orange-500/50 bg-orange-50 text-orange-700' :
                                    'border-zinc-200 bg-stone-100 hover:border-zinc-400 text-zinc-600'"
                                class="relative flex flex-col items-center justify-center p-5 border rounded-md transition-all duration-300 group cursor-pointer">
                                <span class="text-sm font-semibold">{{ $method->getLabel() }}</span>
                                <div x-show="$wire.delivery === '{{ $method->value }}'" x-transition.scale
                                    class="absolute top-1.5 right-1.5">
                                    <x-lucide-check-circle-2 class="size-5 fill-orange-50 stroke-orange-600" />
                                </div>
                            </button>
                        @endforeach
                    </div>

                    <div wire:show="delivery === 'nova_poshta' || delivery === 'ukrposhta'" wire:transition
                        class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-form.group>
                            <x-form.label>Ім'я</x-form.label>
                            <x-form.input wire:model.trim="first_name" placeholder="Іван" />
                            @error('first_name')
                                <x-form.error>{{ $message }}</x-form.error>
                            @enderror
                            <x-form.hint wire:show="subscribe">
                                Також використовуватиметься для відправки поштою
                            </x-form.hint>
                        </x-form.group>

                        <x-form.group>
                            <x-form.label>Прізвище</x-form.label>
                            <x-form.input wire:model.trim="last_name" placeholder="Іванов" />
                            @error('last_name')
                                <x-form.error>{{ $message }}</x-form.error>
                            @enderror
                        </x-form.group>

                        <x-form.group>
                            <x-form.label>Номер телефону</x-form.label>
                            <x-form.input wire:model.trim="phone" x-mask="+999 (99) 999-99-99"
                                placeholder="+380 (63) 123-44-56" />
                        </x-form.group>

                        <x-form.group>
                            <x-form.label>Місто</x-form.label>
                            <x-form.input wire:model.trim="city" placeholder="Київ" />
                        </x-form.group>

                        <x-form.group wire:show="delivery === 'nova_poshta'">
                            <x-form.label>Адреса або відділення</x-form.label>
                            <x-form.input wire:model.trim="address" placeholder="Відділення/поштомат Нової Пошти" />
                        </x-form.group>
                        <x-form.group wire:show="delivery === 'ukrposhta'">
                            <x-form.label>Адреса або відділення</x-form.label>
                            <x-form.input wire:model.trim="address" placeholder="Відділення/поштомат Укрпошти" />
                        </x-form.group>
                    </div>

                    <div wire:show="delivery === 'pickup'" wire:transition class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <x-form.input wire:model.trim="first_name" placeholder="Ім'я" />
                            @error('first_name')
                                <x-form.error>{{ $message }}</x-form.error>
                            @enderror
                            <x-form.hint wire:show="subscribe">
                                Також використовуватиметься для відправки поштою
                            </x-form.hint>
                        </div>

                        <div>
                            <x-form.input wire:model.trim="phone" x-mask="+999 (99) 999-99-99"
                                placeholder="Номер телефону" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="max-w-xl">
                <h2 class="text-lg font-semibold font-[SN_Pro] flex items-center gap-1.5">
                    <x-lucide-message-square class="size-5" /> Коментар
                    <span class="italic text-gray-500 text-sm font-normal">(за бажанням)</span>
                </h2>
                <x-form.textarea wire:model.trim="comment" rows="5" placeholder="Ваші побажання до замовлення..." />
            </div>

            <x-button type="submit" color="dark" size="lg" wire:loading.attr="disabled" wire:trigger="checkout">
                <span wire:loading.remove wire:target="checkout">Підтвердити замовлення</span>
                <span wire:loading wire:target="checkout">Обробка...</span>
                <x-lucide-loader-circle wire:loading wire:target="checkout" class="size-5 animate-spin ms-1.5" />
            </x-button>
        </form>

        <x-slot:sidebar>
            <div class="sticky top-24">
                <h2 class="text-xl font-bold mb-5">Ваше замовлення</h2>
                <div class="divide-y divide-zinc-200 mb-5">
                    @foreach ($this->items as $item)
                        <div class="py-5 flex justify-between gap-5">
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

                <div class="mt-10 text-xs text-orange-700">
                    <span>Я зателефоную вам для підтвердження замовлення протягом 30 хвилин.</span>
                </div>
            </div>
        </x-slot:sidebar>
    </x-section>
@endsession
