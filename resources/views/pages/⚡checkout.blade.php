<?php

use Livewire\Component;
use App\Services\CartService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use App\Enums\Order\DeliveryMethod;

new class extends Component {
    #[
        Validate(
            'required|string|min:2|max:50',
            message: [
                'required' => 'Вкажіть ваше імʼя.',
                'string' => 'Імʼя повинно бути текстом.',
                'min' => 'Імʼя повинно містити щонайменше 2 символи.',
                'max' => 'Імʼя не може перевищувати 50 символів.',
            ],
        ),
    ]
    public string $first_name = '';

    #[
        Validate(
            'required|string|min:2|max:50',
            message: [
                'required' => 'Вкажіть ваше прізвище.',
                'string' => 'Прізвище повинно бути текстом.',
                'min' => 'Прізвище повинно містити щонайменше 2 символи.',
                'max' => 'Прізвище не може перевищувати 50 символів.',
            ],
        ),
    ]
    public string $last_name = '';

    #[
        Validate(
            'required|regex:/^\+?[0-9\s\-\(\)]{10,18}$/',
            message: [
                'required' => 'Вкажіть номер телефону.',
                'regex' => 'Вкажіть коректний номер телефону.',
            ],
        ),
    ]
    public string $phone = '';

    #[
        Validate(
            'required|email:rfc,dns|max:100',
            message: [
                'required' => 'Вкажіть електронну пошту.',
                'email' => 'Вкажіть коректну адресу.',
                'max' => 'Занадто багато символів.',
            ],
        ),
    ]
    public string $email = '';

    #[Validate('required|in:nova_poshta,ukrposhta,courier')]
    public string $delivery_method = 'nova_poshta';

    #[
        Validate(
            'required|string|min:2|max:100',
            message: [
                'required' => 'Вкажіть населений пункт.',
                'min' => 'Назва міста занадто коротка.',
            ],
        ),
    ]
    public string $city = '';

    #[
        Validate(
            'required|string|min:5|max:255',
            message: [
                'required' => 'Вкажіть адресу або номер відділення доставки.',
                'min' => 'Адреса занадто коротка.',
            ],
        ),
    ]
    public string $address = '';

    #[
        Validate(
            'nullable|string|max:1500',
            message: [
                'string' => 'Коментар повинен бути текстом.',
            ],
        ),
    ]
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
        $this->validate();

        // 2. Створюємо замовлення
        $order = Order::create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'delivery_method' => $this->delivery_method,
            'city' => $this->city,
            'address' => $this->address,
            'comment' => $this->comment,
            'total_price' => $this->total(),
            'type' => $this->items->isEmpty() ? OrderType::Manufacturing : OrderType::Purchase,
            'custom_options' => $this->custom_options,
        ]);

        // 3. Переносимо товари з кошика в базу
        foreach ($this->items as $item) {
            $order->products()->create([
                'product_id' => $item->id,
                'product_name' => $item->name,
                'qty' => $item->qty,
                'price' => $item->price,
            ]);
        }

        Notification::route('telegram', env('TELEGRAM_CHAT_ID'))->notify(new OrderSubmitted($order));

        $this->cartService()->clear();

        session()->flash('success-order', $order->number);
    }
};
?>

@section('header')
    <x-header :image="Vite::asset('resources/images/header.png')">
        <x-slot:title>
            Замовлення
        </x-slot:title>
    </x-header>
@endsection

<x-section sidebar-position="right">
    <form wire:submit="checkout" class="lg:col-span-7 space-y-10" novalidate>
        <div>
            <h2 class="text-lg font-semibold font-[SN_Pro] mb-2.5 flex items-center gap-1.5">
                <x-lucide-user class="size-5" /> Контактні дані
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <x-form.group>
                    <x-form.label>Електронна пошта</x-form.label>
                    <x-form.input wire:model.trim.live.blur="email" placeholder="example@gmail.com" required />
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
                <div x-data="{ delivery_method: @entangle('delivery_method') }" class="w-full lg:max-w-lg grid grid-cols-3 lg:grid-cols-3 gap-2.5">
                    @foreach (DeliveryMethod::cases() as $method)
                        <button type="button" @click="delivery_method = '{{ $method->value }}'"
                            :class="delivery_method === '{{ $method->value }}' ?
                                'border-orange-500/50 ring-1 ring-orange-500/50 bg-orange-50 text-orange-700' :
                                'border-zinc-200 bg-zinc-100 hover:border-zinc-300 text-zinc-600'"
                            class="relative flex flex-col items-center justify-center p-5 border rounded-md transition-all duration-300 group cursor-pointer">
                            <span class="text-sm font-semibold">{{ $method->getLabel() }}</span>
                            <div x-show="$wire.delivery_method === '{{ $method->value }}'" x-transition.scale
                                class="absolute top-1.5 right-1.5">
                                <x-lucide-check-circle-2 class="size-5 fill-orange-50 stroke-orange-600" />
                            </div>
                        </button>
                    @endforeach
                </div>

                <div wire:show="delivery_method === '{{ DeliveryMethod::NovaPoshta }}' || delivery_method === '{{ DeliveryMethod::UkrPoshta }}'"
                    class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-form.group>
                        <x-form.label>Ім'я</x-form.label>
                        <x-form.input wire:model.trim.live.blur="first_name" placeholder="Іван" required />
                        <x-form.hint wire:show="subscribe">
                            Також використовуватиметься для відправки поштою
                        </x-form.hint>
                    </x-form.group>

                    <x-form.group>
                        <x-form.label>Прізвище</x-form.label>
                        <x-form.input wire:model.trim.live.blur="last_name" placeholder="Іванов" required />
                    </x-form.group>

                    <x-form.group>
                        <x-form.label>Номер телефону</x-form.label>
                        <x-form.input wire:model.trim.live.blur="phone" x-mask="+999 (99) 999-99-99"
                            placeholder="+380 (63) 123-44-56" required />
                    </x-form.group>

                    <x-form.group>
                        <x-form.label>Місто</x-form.label>
                        <x-form.input wire:model.trim.live.blur="city" placeholder="Київ" required />
                    </x-form.group>

                    <x-form.group wire:show="delivery_method === '{{ DeliveryMethod::NovaPoshta }}'">
                        <x-form.label>Адреса або відділення</x-form.label>
                        <x-form.input wire:model.trim.live.blur="address" placeholder="Відділення/поштомат Нової Пошти"
                            required />
                    </x-form.group>
                    <x-form.group wire:show="delivery_method === '{{ DeliveryMethod::UkrPoshta }}'">
                        <x-form.label>Адреса або відділення</x-form.label>
                        <x-form.input wire:model.trim.live.blur="address" placeholder="Відділення/поштомат Укрпошти"
                            required />
                    </x-form.group>
                </div>

                <div wire:show="delivery_method === 'pickup'" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-form.group>
                        <x-form.label>Номер телефону</x-form.label>
                        <x-form.input wire:model.trim.live.blur="first_name" placeholder="Ім'я" />
                        <x-form.hint wire:show="subscribe">
                            Також використовуватиметься для відправки поштою
                        </x-form.hint>
                    </x-form.group>

                    <x-form.group>
                        <x-form.label>Номер телефону</x-form.label>
                        <x-form.input wire:model.trim.live.blur="phone" x-mask="+999 (99) 999-99-99"
                            placeholder="Номер телефону" />
                    </x-form.group>
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
            <h2 class="text-xl font-[SN_Pro] font-bold mb-5">Ваше замовлення</h2>

            <div class="divide-y divide-zinc-200 mb-5">
                @each('partials.order.order-items', $this->items, 'item')
            </div>

            <div class="flex justify-between items-center pt-5 border-t-2 border-zinc-200">
                <span class="text-lg text-gray-600">Разом до сплати:</span>
                <span class="text-3xl font-black text-black tracking-tighter">
                    {{ number_format($this->total, 0, '.', ' ') }} <small class="text-sm font-normal">грн</small>
                </span>
            </div>
    </x-slot:sidebar>
</x-section>
