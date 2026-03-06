<?php

use App\Models\Order;
use App\Enums\Order\OrderType;
use App\Enums\Order\DeliveryMethod;
use App\Notifications\OrderSubmitted;
use Illuminate\Support\Facades\Notification;
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

    #[Validate('required|email', message: 'Вкажіть електронну пошту')]
    public string $email = '';

    #[Validate('required', message: 'Оберіть спосіб доставки')]
    public string $delivery_method = 'nova_poshta';

    #[Validate('required', message: 'Оберіть ваше місто')]
    public string $city = '';

    #[Validate('required', message: 'Вкажіть місто та відділення')]
    public string $address = '';

    public string $comment = '';

    public array $custom_options = [
        'blade' => [
            'shape' => null,
            'steel' => null,
            'grind' => null,
            'finish' => null,
            'length' => null,
            'thickness' => null,
        ],
        'handle' => [
            'material' => null,
            'color' => null,
        ],
        'sheath' => [
            'type' => null,
            'carry' => null,
        ],
    ];

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
        // dd($this->custom_options);
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
        foreach ($this->items() as $item) {
            $order->products()->create([
                'product_id' => $item->id,
                'product_name' => $item->name,
                'qty' => $item->qty,
                'price' => $item->price,
            ]);
        }

        Notification::route('telegram', env('TELEGRAM_CHAT_ID'))->notify(new OrderSubmitted($order));

        $this->dispatch('cart:clear');

        session()->flash('success-order', $order->number);
    }
};
?>

@section('header')
    <x-header :image="Vite::asset('resources/images/header.png')">
        <x-slot:title>Оформлення<br>замовлення</x-slot:title>
    </x-header>
@endsection

@session('success-order')
    <div class="min-h-[50vh] flex flex-col justify-center items-center py-20 px-5 lg:px-0">
        <x-lucide-badge-check class="size-40 shrink-0 fill-green-100 stroke-green-500" stroke-width="1" />
        <div class="text-orange-600 font-[Oswald] text-lg font-bold mt-10">#{{ session('success-order') }}</div>
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
        <!-- Ліва частина: Форма -->
        <form wire:submit="checkout" class="lg:col-span-7 space-y-10" novalidate>

            @includeWhen($this->items->isEmpty(), 'partials.order.manufacture-fields')

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
                        wire:transition class="grid grid-cols-1 md:grid-cols-2 gap-5">
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

                    <div wire:show="delivery_method === 'pickup'" wire:transition
                        class="grid grid-cols-1 md:grid-cols-2 gap-5">
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

                @if ($this->items->isEmpty())
                    <div class="text-gray-900 font-medium uppercase mt-2.5">Марка сталі</div>
                    <div wire:text="custom_options.blade.steel || 'не вказано'" class="text-sm text-gray-600"></div>

                    <div class="text-gray-900 font-medium uppercase">Профіль клинка</div>
                    <div wire:text="custom_options.blade.shape || 'не вказано'" class="text-sm text-gray-600"></div>

                    <div class="text-gray-900 font-medium uppercase mt-2.5">Тип спусків</div>
                    <div wire:text="custom_options.blade.grind || 'не вказано'" class="text-sm text-gray-600"></div>

                    <div class="text-gray-900 font-medium uppercase mt-2.5">Покриття клинка</div>
                    <div wire:text="custom_options.blade.finish || 'не вказано'" class="text-sm text-gray-600"></div>

                    <div class="text-gray-900 font-medium uppercase mt-2.5">Довжина клинка</div>
                    <div wire:text="custom_options.blade.length || 'не вказано'" class="text-sm text-gray-600"></div>

                    <div class="text-gray-900 font-medium uppercase mt-2.5">Товщина клинка</div>
                    <div wire:text="custom_options.blade.thickness || 'не вказано'" class="text-sm text-gray-600"></div>

                    <div class="text-gray-900 font-medium uppercase mt-2.5">Матеріал руків'я</div>
                    <div wire:text="custom_options.handle.material || 'не вказано'" class="text-sm text-gray-600"></div>

                    <div class="text-gray-900 font-medium uppercase mt-2.5">Колір / побажання</div>
                    <div wire:text="custom_options.handle.color || 'не вказано'"
                        class="text-sm text-gray-600 overflow-hidden line-clamp-1"></div>

                    <div class="text-gray-900 font-medium uppercase mt-2.5">Піхви / Чохол</div>
                    <div wire:text="custom_options.sheath.type || 'не вказано'" class="text-sm text-gray-600"></div>
                @else
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

                    <div class="flex justify-between items-center pt-5 border-t-2 border-zinc-200">
                        <span class="text-lg text-gray-600">Разом до сплати:</span>
                        <span class="text-3xl font-black text-black tracking-tighter">
                            {{ number_format($this->total, 0, '.', ' ') }} <small class="text-sm font-normal">грн</small>
                        </span>
                    </div>
                @endif

                <div class="mt-10 text-xs text-orange-700">
                    <span>Я зателефоную вам для підтвердження замовлення протягом дня.</span>
                </div>
            </div>
        </x-slot:sidebar>
    </x-section>
@endsession
