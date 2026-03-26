<?php

use Livewire\Attributes\Title;
use App\Livewire\Forms;
use App\Enums\SteelType;
use App\Enums\Order\OrderType;
use App\Enums\KnifeCollection;
use App\Enums\BladeShape;
use App\Models\Order;
use App\Models\Subscriber;
use App\Livewire\Forms\OrderForm;
use App\Notifications\OrderManufactureSubmitted;
use Illuminate\Support\Facades\Notification;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Transition;
use Livewire\Component;

new #[Title('Замовлення ножів ручної роботи')] class extends Component {
    public OrderForm $form;

    public $step = 1;

    #[Transition(type: 'forward')]
    public function nextStep()
    {
        $this->step++;
    }

    #[Transition(type: 'backward')]
    public function previousStep()
    {
        $this->step--;
    }

    public $knife_type = '';

    public $sheath = '';

    public $blade_shape = '';
    public $blade_steel = '';
    public $blade_grind = '';
    public $blade_finish = '';
    public $blade_length = '';
    public $blade_thickness = '';

    public $handle_material = '';
    public $handle_color = '';

    public $sheath_type = '';
    public $sheath_carry = '';

    public $engraving = '';
    public $engraving_text = '';

    public string $notes = '';

    public bool $subscribe = false;

    public function send()
    {
        $this->validate();

        $order = Order::create($this->form->all() + ['type' => OrderType::Manufacturing]);

        $order->manufacture()->create($this->all());

        $this->subscribe && Subscriber::firstOrCreate(['email' => $this->form->email]);

        Notification::routes([
            'mail' => env('ADMIN_EMAIL'),
            'telegram' => env('TELEGRAM_CHAT_ID'),
        ])->notify(new OrderManufactureSubmitted($order));

        session()->flash('success-order', $order->number);
    }
};
?>

<x-slot name="description">
    Замовте ніж ручної роботи від майстра: індивідуальне виготовлення ножів, якісні матеріали, унікальний дизайн.
</x-slot>

@section('header')
    <x-header :image="Vite::asset('resources/images/header.png')">
        <x-slot:title>Створення<br>замовлення</x-slot:title>
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
    <x-section sidebar-position="right" x-data="knifeConfigurator($wire)">
        <div class="relative outline-hidden">
            <!-- progress -->
            <div class="mb-5">
                <div class="flex justify-between text-sm mb-2.5 text-zinc-600">
                    <span>Крок <span wire:text="step"></span> з 5</span>
                </div>

                <div class="w-full h-2 bg-gray-200 rounded">
                    <div class="h-2 bg-orange-500 rounded transition-all duration-300"
                        :style="`width:${($wire.step/5)*100}%`">
                    </div>
                </div>
            </div>

            <div wire:show="step === 1" x-cloak>
                <h2 class="text-2xl font-semibold">Тип ножа</h2>

                <button type="button" wire:click="$set('step', 1)"
                    class="mb-5 text-sm font-medium text-orange-500/80 hover:text-orange-600 transition-colors flex items-center gap-1.5 cursor-pointer">
                    <x-lucide-info class="size-4" />
                    Дізнатись більше про типи?
                </button>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 lg:gap-5">
                    @foreach (KnifeCollection::cases() as $type)
                        <button type="button" wire:click="knife_type = '{{ $type->getLabel() }}'"
                            :class="$wire.knife_type === '{{ $type->getLabel() }}' ? 'border-orange-500 bg-orange-50' :
                                'border-gray-200'"
                            class="relative border-2 rounded-md p-0 text-center transition hover:border-orange-400 cursor-pointer h-40 lg:h-50 w-full sm:w-auto overflow-hidden flex items-end justify-center group">

                            <!-- фон через img -->
                            <img src="{{ Vite::asset("resources/images/{$type->icons()}") }}"
                                class="absolute inset-0 size-full object-contain -rotate-35 drop-shadow-xl group-hover:scale-105 group-hover:drop-shadow-2xl group-hover:-rotate-40 transition-all duration-500"
                                alt="{{ $type->getLabel() }}">

                            <!-- текст поверх картинки -->
                            <div class="relative z-10 text-zinc-800 text-sm font-medium py-1.5">
                                {{ $type->getLabel() }}
                            </div>
                        </button>
                    @endforeach
                    <button type="button" wire:click="knife_type = 'Інший'"
                        :class="$wire.type === 'Інший' ? 'border-orange-500 bg-orange-50' : 'border-gray-200'"
                        class="relative border-2 rounded-md p-0 text-center transition hover:border-orange-400 cursor-pointer h-40 lg:h-50 w-full sm:w-auto overflow-hidden flex items-end justify-center group">

                        <!-- фон через img -->
                        <img src="{{ Vite::asset('resources/images/other-icon.png') }}"
                            class="absolute inset-0 size-full object-contain -rotate-35 drop-shadow-xl group-hover:scale-105 group-hover:drop-shadow-2xl group-hover:-rotate-40 transition-all duration-500"
                            alt="Інший">

                        <!-- текст поверх картинки -->
                        <div class="relative z-10 text-zinc-800 text-sm font-medium py-1.5">
                            Інший
                        </div>
                    </button>
                </div>
            </div>

            <div wire:show="step === 2" x-cloak>
                <h2 class="text-2xl font-semibold">Клинок</h2>

                <button type="button" wire:click="$set('step', 1)"
                    class="mb-5 text-sm font-medium text-orange-500/80 hover:text-orange-600 transition-colors flex items-center gap-1.5 cursor-pointer">
                    <x-lucide-info class="size-4" />
                    Які бувають види?
                </button>

                <x-form.group>
                    <x-form.label>Форма клинка</x-form.label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 lg:gap-5">
                        @foreach (BladeShape::cases() as $item)
                            <button type="button" @click="form.bladeShape = '{{ $item->getLabel() }}'"
                                :class="form.bladeShape === '{{ $item->getLabel() }}' ?
                                    'border-orange-500 bg-orange-50' :
                                    'border-gray-200'"
                                class="relative border-2 rounded-md p-0 text-center transition hover:border-orange-400 h-40 lg:h-30 w-full overflow-hidden flex items-end justify-center cursor-pointer">

                                {{-- <img src="{{ Vite::asset("resources/images/icons/{$item->icon()}") }}"
                                    class="absolute inset-0 size-full object-contain"> --}}

                                <div class="relative z-10 text-zinc-800 text-sm font-medium py-1.5">
                                    {{ $item->getLabel() }}
                                </div>
                            </button>
                        @endforeach
                    </div>
                </x-form.group>

                <x-form.group class="max-w-xs mt-5">
                    <x-form.label>Тип сталі</x-form.label>
                    <x-form.select wire:model="blade_steel">
                        <option value="">Оберіть</option>
                        @foreach (SteelType::cases() as $item)
                            <option value={{ $item->getLabel() }}>{{ $item->getLabel() }}</option>
                        @endforeach
                    </x-form.select>
                </x-form.group>

                <div class="grid grid-cols-2 max-w-sm gap-5 mt-5">
                    <x-form.group>
                        <x-form.label>Довжина клинка (мм)</x-form.label>
                        <x-form.input type="number" wire:model="blade_length" />
                    </x-form.group>

                    <x-form.group>
                        <x-form.label>Товщина (мм)</x-form.label>
                        <x-form.input type="number" wire:model="blade_thickness" />
                    </x-form.group>
                </div>
            </div>

            <div wire:show="step === 3" x-cloak>
                <h2 class="text-2xl font-semibold">Руків’я</h2>

                <button type="button" wire:click="$set('step', 1)"
                    class="text-sm font-medium text-orange-500/80 hover:text-orange-600 transition-colors flex items-center gap-1.5 cursor-pointer">
                    <x-lucide-info class="size-4" />
                    Як обрати?
                </button>

                <x-form.group class="max-w-xs mt-5">
                    <x-form.label>Матеріал</x-form.label>
                    <x-form.select wire:model="handle_material">
                        <option value="">Оберіть</option>
                        <option>Дерево</option>
                        <option>Micarta</option>
                        <option>G10</option>
                        <option>Carbon Fiber</option>
                        <option>Ріг</option>
                    </x-form.select>
                </x-form.group>

                <x-form.group class="mt-5">
                    <x-form.label>Колір руків’я</x-form.label>
                    <div class="flex flex-wrap gap-2.5 mb-5">
                        <template x-for="color in handleColors" :key="color.value">
                            <button type="button" x-on:click="$wire.handle_color = color.value"
                                :class="$wire.handle_color === color.value ?
                                    'ring-2 ring-offset-2 ring-orange-500 scale-110' : ''"
                                class="w-10 h-10 rounded-full transition transform hover:scale-105 cursor-pointer"
                                :style="`background:${color.value}`" :title="color.name">
                            </button>
                        </template>
                    </div>

                    <!-- кастомний колір -->
                    <x-form.group>
                        <x-form.label class="text-sm text-gray-500">Свій колір</x-form.label>
                        <input type="color" wire:model="handle_color" class="w-16 h-10 rounded-md cursor-pointer">
                        <span class="text-sm font-mono" wire:text="handle_color"></span>
                    </x-form.group>
                </x-form.group>
            </div>

            <div wire:show="step === 4" x-cloak>
                <h2 class="text-2xl font-semibold">Додаткові опції</h2>

                <div class="flex gap-5 mt-5">
                    <x-form.checkbox label="Потрібні піхви" wire:model="sheath" />
                    <x-form.checkbox label="Гравіювання" wire:model="engraving" />
                </div>

                <div wire:show="engraving" class="mt-5">
                    <x-form.label>Текст гравіювання</x-form.label>
                    <x-form.input wire:model="engraving_text" />
                </div>

                <x-form.group class="max-w-lg mt-5">
                    <x-form.label>Додаткові побажання</x-form.label>
                    <x-form.textarea wire:model="notes" rows="4"></x-form.textarea>
                </x-form.group>
            </div>

            <div wire:show="step === 5" x-cloak>

                <h2 class="text-2xl font-semibold">Контакти</h2>

                @include('partials.order.order-form')

            </div>
        </div>

        <!-- navigation -->
        <div class="flex justify-between mt-10" x-cloak>
            <button type="button" wire:show="step > 1" @click="previousStep()" wire:loading.attr="disabled"
                wire:target="previousStep"
                class="me-auto px-5 py-2 bg-orange-500 text-white text-sm rounded-md flex items-center gap-1.5 hover:bg-orange-600 transition-colors duration-300 cursor-pointer disabled:opacity-50 disabled:pointer-events-none">
                <x-lucide-move-left wire:loading.remove wire:target="previousStep" class="size-4 shrink-0" />
                <x-lucide-loader wire:loading wire:target="previousStep" class="size-4 shrink-0 animate-spin" />
                Назад
            </button>

            <button type="button" wire:show="step < 5" @click="nextStep()" wire:loading.attr="disabled"
                wire:target="nextStep"
                class="ml-auto px-5 py-2 bg-orange-500 text-white text-sm rounded-md flex items-center gap-1.5 hover:bg-orange-600 transition-colors duration-300 cursor-pointer disabled:opacity-50 disabled:pointer-events-none">
                Далі
                <x-lucide-move-right wire:loading.remove wire:target="nextStep" class="size-4 shrink-0" />
                <x-lucide-loader wire:loading wire:target="nextStep" class="size-4 shrink-0 animate-spin" />
            </button>

            <button type="button" wire:show="step === 5" wire:click="send" wire:loading.attr="disabled"
                wire:target="send"
                class="ml-auto px-5 py-2 bg-orange-500 text-white text-sm rounded-md flex items-center gap-1.5 hover:bg-orange-600 transition-colors duration-300 cursor-pointer disabled:opacity-50 disabled:pointer-events-none">
                Відправити
                <x-lucide-send wire:loading.remove wire:target="nextStep" class="size-4 shrink-0" />
                <x-lucide-loader wire:loading wire:target="nextStep" class="size-4 shrink-0 animate-spin" />
            </button>
        </div>

        <x-slot:sidebar>
            <div class="lg:sticky lg:top-24 lg:h-screen">
                {{-- <div wire:dirty>
                    <h2 class="font-[Oswald] text-lg font-medium tracking-wide mb-5">Ваше замовлення</h2>

                    <div wire:dirty="type">
                        <div class="text-gray-900 font-medium uppercase mt-2.5">Тип ножа</div>
                        <div wire:text="type" class="text-sm text-gray-600"></div>
                    </div>

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

                    <div class="mt-10 text-xs text-orange-700">
                        <span>Я зателефоную вам для підтвердження замовлення протягом дня.</span>
                    </div>
                </div> --}}

                <div wire:dirty.remove>
                    <div
                        class="flex flex-col items-center justify-center p-5 bg-orange-50 border border-orange-100 rounded-lg text-center">
                        <div class="bg-white p-2.5 rounded-full mb-5">
                            <x-lucide-info class="size-8 text-orange-500" stroke-width="1.5" />
                        </div>

                        <h3 class="font-[Oswald] text-lg font-medium text-gray-800 uppercase tracking-wide">
                            Конфігурація порожня
                        </h3>

                        <p class="text-sm text-gray-600 mt-2.5 leading-relaxed">
                            Схоже, ви ще не обрали жодного параметра для вашого майбутнього ножа.
                            Почніть з першого кроку, щоб ми могли створити ідеальний виріб разом.
                        </p>
                    </div>
                </div>
            </div>
        </x-slot:sidebar>
    </x-section>

    <script>
        function knifeConfigurator($wire) {
            return {
                step: @entangle('step'),


                // методи переходу
                nextStep() {
                    if (this.step < 5) this.step++;
                },
                previousStep() {
                    if (this.step > 1) this.step--;
                },

                form: {
                    bladeShape: $wire.entangle('blade_shape'),
                    handleColor: $wire.entangle('handle_color'),
                },

                handleColors: [{
                        name: 'Чорний',
                        value: '#111111'
                    },
                    {
                        name: 'Коричневий',
                        value: '#6b3e26'
                    },
                    {
                        name: 'Пісочний',
                        value: '#d6b98c'
                    },
                    {
                        name: 'Оливковий',
                        value: '#556b2f'
                    },
                    {
                        name: 'Червоний',
                        value: '#8b0000'
                    },
                    {
                        name: 'Синій',
                        value: '#1e3a8a'
                    },
                    {
                        name: 'Помаранчевий',
                        value: '#ea580c'
                    },
                    {
                        name: 'Carbon',
                        value: '#2b2b2b'
                    }
                ]
            }
        }
    </script>
@endsession
