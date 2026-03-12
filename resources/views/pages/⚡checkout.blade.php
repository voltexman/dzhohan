<?php

use Livewire\Component;
use App\Models\Order;
use App\Models\Subscriber;
use App\Enums\Order\OrderType;
use App\Services\CartService;
use App\Livewire\Forms\OrderForm;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use App\Enums\Order\DeliveryMethod;
use App\Notifications\OrderPurchaseSubmitted;

new class extends Component {
    public OrderForm $form;

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
        $validated = $this->validate();

        $order = Order::create($validated + ['type' => OrderType::Purchase]);

        $order->products()->createMany($this->cartService()->itemsForOrder());

        $this->subscribe && Subscriber::firstOrCreate(['email' => $this->form->email]);

        Notification::routes([
            'mail' => env('ADMIN_EMAIL'),
            'telegram' => env('TELEGRAM_CHAT_ID'),
        ])->notify(new OrderPurchaseSubmitted($order));

        $this->cartService()->clear();

        session()->flash('success-checkout', $order->number);
    }
};
?>

@section('header')
    <x-header :image="Vite::asset('resources/images/header.png')">
        <x-slot:title>
            Замовлення<br>товару
        </x-slot:title>
    </x-header>
@endsection

@session('success-checkout')
    <div class="min-h-[50vh] flex flex-col justify-center items-center py-20 px-5 lg:px-0">
        <x-lucide-badge-check class="size-40 shrink-0 fill-green-100 stroke-green-500" stroke-width="1" />
        <div class="text-orange-600 font-[Oswald] text-lg font-bold mt-10">#{{ session('success-checkout') }}</div>
        <div class="font-[Oswald] text-2xl text-gray-700">Замовлення прийнято</div>
        <div class="text-sm text-gray-600 text-center mt-2.5 max-w-xs">
            Найближчим часом я зв'яжусь з вами, щоб підтвердити деталі та узгодити доставку.
        </div>
        <div class="text-xs text-gray-500 text-center mt-5 max-w-sm text-balance">
            Якщо у вас виникли запитання, або ви не отримали зворотний дзвінок протягом довготривалого часу — будь ласка,
            зателефонуйте за номером <b>+380 (63) 951 88 42</b> або напишіть листа на пошту <b>dzhogun@gmail.com</b>.
        </div>
    </div>
@else
    @if ($this->items->isNotEmpty())
        <x-section sidebar-position="right">
            @include('partials.order.order-form')

            <x-button wire:click="checkout" color="dark" size="lg" wire:loading.attr="disabled" wire:trigger="checkout"
                class="mt-5">
                <span wire:loading.remove wire:target="checkout">Підтвердити замовлення</span>
                <span wire:loading wire:target="checkout">Обробка...</span>
                <x-lucide-loader-circle wire:loading wire:target="checkout" class="size-5 animate-spin ms-1.5" />
            </x-button>
            <div class="flex flex-col gap-8 py-10 px-6 border-2 border-dashed border-zinc-200 rounded-2xl text-center">
                <!-- Іконка та заклик -->
                <div>
                    <x-lucide-shopping-cart class="size-12 mx-auto text-zinc-300 mb-4" />
                    <h3 class="font-[Oswald] text-xl uppercase font-bold text-zinc-800">Кошик порожній</h3>
                    <p class="text-sm text-zinc-500 mt-2">
                        Схоже, ви ще не обрали свій ідеальний ніж. Час це виправити!
                    </p>
                </div>

                <!-- Переваги -->
                <div class="space-y-4 text-left border-y border-zinc-100 py-6">
                    <div class="flex items-start gap-3">
                        <x-lucide-shield-check class="size-5 text-orange-600 shrink-0 mt-0.5" />
                        <div>
                            <p class="text-sm font-bold text-zinc-800 uppercase tracking-tight">Довічна гарантія</p>
                            <p class="text-xs text-zinc-500">Я відповідаю за якість кожної деталі та збірки.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <x-lucide-award class="size-5 text-orange-600 shrink-0 mt-0.5" />
                        <div>
                            <p class="text-sm font-bold text-zinc-800 uppercase tracking-tight">Ручна робота</p>
                            <p class="text-xs text-zinc-500">Кожен ніж створюється в єдиному екземплярі під ваші
                                завдання.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <x-lucide-phone-call class="size-5 text-orange-600 shrink-0 mt-0.5" />
                        <div>
                            <p class="text-sm font-bold text-zinc-800 uppercase tracking-tight">Є питання?</p>
                            <p class="text-xs text-zinc-500">Зателефонуйте мені, і я допоможу з вибором сталі чи
                                форми.</p>
                        </div>
                    </div>
                </div>

                <!-- Кнопка повернення -->
                <a href="{{ route('products') }}"
                    class="inline-flex justify-center items-center px-6 py-3 bg-zinc-900 text-white text-xs font-bold uppercase tracking-widest hover:bg-orange-600 transition-colors duration-300">
                    Перейти до каталогу
                </a>
            </div>

            <x-slot:sidebar>
                <div class="sticky top-24 h-screen">
                    <h2 class="text-xl font-[Oswald] font-semibold mb-5">Ваше замовлення</h2>

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
    @else
        <section class="h-screen px-5 lg:px-0 py-20">
            <div class="max-w-md mx-auto flex flex-col gap-10 text-center">
                <!-- Іконка та заклик -->
                <div>
                    <x-lucide-shopping-cart class="size-12 mx-auto text-zinc-300 mb-4" />
                    <h3 class="font-[Oswald] text-xl uppercase font-bold text-zinc-800">Кошик порожній</h3>
                    <div class="max-w-xs text-sm text-zinc-500 text-balance mx-auto text-center mt-2.5">
                        Схоже, ви ще не обрали свій ідеальний ніж. Час це виправити!
                    </div>
                </div>

                <!-- Переваги -->
                <div class="space-y-4 text-left border-y border-zinc-100 py-6">
                    <div class="flex items-start gap-3">
                        <x-lucide-shield-check class="size-5 text-orange-600 shrink-0 mt-0.5" />
                        <div>
                            <p class="text-sm font-bold text-zinc-800 uppercase tracking-tight">Довічна гарантія</p>
                            <p class="text-xs text-zinc-500">Я відповідаю за якість кожної деталі та збірки.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <x-lucide-award class="size-5 text-orange-600 shrink-0 mt-0.5" />
                        <div>
                            <p class="text-sm font-bold text-zinc-800 uppercase tracking-tight">Ручна робота</p>
                            <p class="text-xs text-zinc-500">Кожен ніж створюється в єдиному екземплярі під ваші
                                завдання.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <x-lucide-phone-call class="size-5 text-orange-600 shrink-0 mt-0.5" />
                        <div>
                            <p class="text-sm font-bold text-zinc-800 uppercase tracking-tight">Є питання?</p>
                            <p class="text-xs text-zinc-500">Зателефонуйте мені, і я допоможу з вибором сталі чи
                                форми.</p>
                        </div>
                    </div>
                </div>

                <!-- Кнопка повернення -->
                <a href="{{ route('products') }}" wire:navigate
                    class="inline-flex justify-center items-center px-10 py-3.5 w-fit mx-auto rounded-md bg-zinc-900 text-white text-xs font-bold uppercase tracking-widest hover:bg-orange-600 transition-colors duration-300">
                    Перейти до колекцій
                </a>
            </div>
        </section>
    @endif
@endsession
