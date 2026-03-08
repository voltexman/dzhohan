<?php

use App\Enums\ProductCategory;
use App\Models\Order;
use App\Enums\Order\OrderType;
use App\Enums\Order\DeliveryMethod;
use App\Notifications\OrderSubmitted;
use Illuminate\Support\Facades\Notification;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Transition;
use App\Services\CartService;
use Livewire\Component;

new class extends Component {
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

    public $type = '';

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

    public function send()
    {
        // dd($this);
        $this->validate();
    }
};
?>

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
    <x-section sidebar-position="right">
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

            <div wire:show="step === 1" wire:transition="step">
                <h2 class="text-2xl font-semibold mb1.5">Тип ножа</h2>

                <button type="button" wire:click="$set('step', 1)"
                    class="mb-5 text-sm font-medium text-orange-500/80 hover:text-orange-600 transition-colors flex items-center gap-1.5 cursor-pointer">
                    <x-lucide-info class="size-4" />
                    Дізнатись більше про типи?
                </button>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 lg:gap-5">
                    @foreach (ProductCategory::cases() as $type)
                        <button type="button" wire:click="type = '{{ $type->getLabel() }}'"
                            :class="$wire.type === '{{ $type->getLabel() }}' ? 'border-orange-500 bg-orange-50' :
                                'border-gray-200'"
                            class="relative border-2 rounded-md p-0 text-center transition hover:border-orange-400 cursor-pointer h-40 lg:h-50 w-full sm:w-auto overflow-hidden flex items-end justify-center">

                            <!-- фон через img -->
                            <img src="{{ Vite::asset("resources/images/{$type->icons()}") }}"
                                class="absolute inset-0 size-full object-contain -rotate-35 drop-shadow-xl"
                                alt="{{ $type->getLabel() }}">

                            <!-- текст поверх картинки -->
                            <div class="relative z-10 text-zinc-800 text-sm font-medium py-1.5">
                                {{ $type->getLabel() }}
                            </div>
                        </button>
                    @endforeach
                    <button type="button" wire:click="type = 'Інший'"
                        :class="$wire.type === 'Інший' ? 'border-orange-500 bg-orange-50' : 'border-gray-200'"
                        class="relative border-2 rounded-md p-0 text-center transition hover:border-orange-400 cursor-pointer h-40 lg:h-50 w-full sm:w-auto overflow-hidden flex items-end justify-center">

                        <!-- фон через img -->
                        <img src="{{ Vite::asset('resources/images/other-icon.png') }}"
                            class="absolute inset-0 size-full object-contain -rotate-35 drop-shadow-xl" alt="Інший">

                        <!-- текст поверх картинки -->
                        <div class="relative z-10 text-zinc-800 text-sm font-medium py-1.5">
                            Інший
                        </div>
                    </button>
                </div>
            </div>

            <div wire:show="step === 2" wire:transition="step">
                <h2 class="text-2xl font-semibold mb-6">Клинок</h2>
                <div class="space-y-5">
                    <label class="block text-sm mb-3 font-medium">Форма клинка</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">

                        {{-- <template x-for="shape in bladeShapes"> --}}
                        <button type="button" @click="form.bladeShape = shape.name"
                            :class="form.bladeShape === shape.name ?
                                'border-orange-500 bg-orange-50' :
                                'border-gray-200'"
                            class="border rounded-lg p-4 text-center transition hover:border-orange-400">
                            <img :src="shape.image" class="h-12 mx-auto mb-2 object-contain">
                            <div class="text-sm font-medium" x-text="shape.name"></div>
                        </button>
                        {{-- </template> --}}
                    </div>

                    <x-form.group>
                        <x-form.label class="block text-sm mb-1">Тип сталі</x-form.label>
                        <x-form.select x-model="form.steel">
                            <option value="">Оберіть</option>
                            <option>D2</option>
                            <option>N690</option>
                            <option>Elmax</option>
                            <option>M390</option>
                            <option>Damascus</option>
                        </x-form.select>
                    </x-form.group>

                    <div class="grid grid-cols-2 gap-5">
                        <x-form.group>
                            <x-form.label class="block text-sm mb-1">Довжина клинка (мм)</x-form.label>
                            <x-form.input type="number" x-model="form.bladeLength" />
                        </x-form.group>

                        <x-form.group>
                            <x-form.label class="block text-sm mb-1">Товщина (мм)</x-form.label>
                            <x-form.input type="number" x-model="form.bladeThickness" />
                        </x-form.group>
                    </div>
                </div>
            </div>

            <div wire:show="step === 3" wire:transition="step">

                <h2 class="text-2xl font-semibold mb-6">Руків’я</h2>

                <div class="space-y-5">

                    <div>
                        <label class="block text-sm mb-1">Матеріал</label>

                        <x-form.select x-model="form.handleMaterial">
                            <option value="">Оберіть</option>
                            <option>Дерево</option>
                            <option>Micarta</option>
                            <option>G10</option>
                            <option>Carbon Fiber</option>
                            <option>Ріг</option>
                        </x-form.select>
                    </div>

                    <div>
                        <x-form.label>Колір руків’я</x-form.label>

                        <!-- кольори -->
                        <div class="flex flex-wrap gap-3 mb-4">
                            {{-- <template x-for="color in handleColors"> --}}
                            <button type="button" @click="form.handleColor = color.value"
                                :class="form.handleColor === color.value ? 'ring-2 ring-orange-500 scale-110' : ''"
                                class="w-10 h-10 rounded-full border transition transform hover:scale-110"
                                :style="`background:${color.value}`" :title="color.name">
                            </button>
                            {{-- </template> --}}
                        </div>

                        <!-- кастомний колір -->
                        <div class="flex items-center gap-3">
                            <span class="text-sm text-gray-500">Свій колір</span>
                            <input type="color" x-model="form.handleColor"
                                class="w-12 h-10 border rounded cursor-pointer">
                            <span class="text-sm font-mono" x-text="form.handleColor"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div wire:show="step === 4" wire:transition="step">

                <h2 class="text-2xl font-semibold mb-6">Додаткові опції</h2>

                <div class="space-y-5">

                    <label class="flex items-center gap-2">
                        <input type="checkbox" x-model="form.sheath">
                        Потрібні піхви
                    </label>

                    <label class="flex items-center gap-2">
                        <input type="checkbox" x-model="form.engraving">
                        Гравіювання
                    </label>

                    <div x-show="form.engraving">

                        <label class="block text-sm mb-1">Текст гравіювання</label>

                        <input type="text" x-model="form.engravingText" class="w-full border rounded p-2">

                    </div>

                    <div>
                        <label class="block text-sm mb-1">Додаткові побажання</label>

                        <textarea x-model="form.notes" rows="4" class="w-full border rounded p-2"></textarea>
                    </div>

                </div>
            </div>

            <div wire:show="step === 5" wire:transition="step">

                <h2 class="text-2xl font-semibold mb-6">Контакти</h2>

                <div class="space-y-4">

                    <input type="text" placeholder="Ім’я" x-model="form.name" class="w-full border rounded p-2">

                    <input type="email" placeholder="Email" x-model="form.email" class="w-full border rounded p-2">

                    <input type="tel" placeholder="Телефон" x-model="form.phone" class="w-full border rounded p-2">

                </div>

            </div>

            <!-- navigation -->
            <div class="flex justify-between mt-10">
                <button type="button" wire:show="step > 1" wire:click="previousStep" wire:loading.attr="disabled"
                    wire:target="previousStep"
                    class="me-auto px-5 py-2 bg-orange-500 text-white text-sm rounded-md flex items-center gap-1.5 hover:bg-orange-600 transition-colors duration-300 cursor-pointer disabled:opacity-50 disabled:pointer-events-none">
                    <x-lucide-move-left wire:loading.remove wire:target="previousStep" class="size-4 shrink-0" />
                    <x-lucide-loader wire:loading wire:target="previousStep" class="size-4 shrink-0 animate-spin" />
                    Назад
                </button>

                <button type="button" wire:show="step < 5" wire:click="nextStep" wire:loading.attr="disabled"
                    wire:target="nextStep"
                    class="ml-auto px-5 py-2 bg-orange-500 text-white text-sm rounded-md flex items-center gap-1.5 hover:bg-orange-600 transition-colors duration-300 cursor-pointer disabled:opacity-50 disabled:pointer-events-none">
                    Далі
                    <x-lucide-move-right wire:loading.remove wire:target="nextStep" class="size-4 shrink-0" />
                    <x-lucide-loader wire:loading wire:target="nextStep" class="size-4 shrink-0 animate-spin" />
                </button>

                <button wire:show="step === 5" class="ml-auto px-6 py-2 bg-orange-500 text-white rounded">
                    Надіслати замовлення
                </button>
            </div>
        </div>

        <x-slot:sidebar>
            <div class="sticky top-24 lg:h-screen">
                <div wire:dirty>
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
                </div>

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
@endsession

<style>
    html:active-view-transition-type(forward) {
        &::view-transition-old(content) {
            animation: 300ms ease-out both slide-out-left;
        }

        &::view-transition-new(content) {
            animation: 300ms ease-in both slide-in-right;
        }
    }

    html:active-view-transition-type(backward) {
        &::view-transition-old(content) {
            animation: 300ms ease-out both slide-out-right;
        }

        &::view-transition-new(content) {
            animation: 300ms ease-in both slide-in-left;
        }
    }

    @keyframes slide-out-left {
        from {
            transform: translateX(0);
            opacity: 1;
        }

        to {
            transform: translateX(-100%);
            opacity: 0;
        }
    }

    @keyframes slide-in-right {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slide-out-right {
        from {
            transform: translateX(0);
            opacity: 1;
        }

        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    @keyframes slide-in-left {
        from {
            transform: translateX(-100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>
