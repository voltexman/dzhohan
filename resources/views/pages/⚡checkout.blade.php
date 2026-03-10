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
use App\Notifications\OrderSubmitted;

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

        Notification::route('telegram', env('TELEGRAM_CHAT_ID'))->notify(new OrderSubmitted($order));

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
    <x-section sidebar-position="right">

        @include('partials.order.order-form')

        <x-button wire:click="checkout" color="dark" size="lg" wire:loading.attr="disabled" wire:trigger="checkout"
            class="mt-5">
            <span wire:loading.remove wire:target="checkout">Підтвердити замовлення</span>
            <span wire:loading wire:target="checkout">Обробка...</span>
            <x-lucide-loader-circle wire:loading wire:target="checkout" class="size-5 animate-spin ms-1.5" />
        </x-button>

        <x-slot:sidebar>
            <div class="sticky top-24">
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
@endsession
