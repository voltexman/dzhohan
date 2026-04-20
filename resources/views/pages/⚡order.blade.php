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

    public int $step = 1;
    public int $totalSteps = 5;

    public string $knife_type = '';
    public bool $sheath = false;

    public string $blade_shape = '';
    public string $blade_steel = '';
    public string $blade_grind = '';
    public string $blade_finish = '';
    public string $blade_length = '';
    public string $blade_thickness = '';

    public string $handle_material = '';
    public string $handle_color = '';

    public bool $engraving = false;
    public string $engraving_text = '';

    public string $notes = '';
    public bool $subscribe = false;

    #[Computed]
    public function progress(): int
    {
        return (int) (($this->step / $this->totalSteps) * 100);
    }

    #[Computed]
    public function stepLabels(): array
    {
        return [
            1 => 'Тип',
            2 => 'Клинок',
            3 => "Руків'я",
            4 => 'Опції',
            5 => 'Контакти',
        ];
    }

    #[Computed]
    public function hasConfiguration(): bool
    {
        return !empty($this->knife_type) || !empty($this->blade_steel) || !empty($this->handle_material);
    }

    protected function validateStep(): void
    {
        match ($this->step) {
            1 => $this->validate(['knife_type' => 'required|string']),
            2 => $this->validate([
                'blade_steel' => 'required|string',
                'blade_length' => 'required|numeric|min:50|max:500',
            ]),
            5 => $this->form->validate(),
            default => null,
        };
    }

    #[Transition(type: 'forward')]
    public function nextStep(): void
    {
        $this->validateStep();
        if ($this->step < $this->totalSteps) {
            $this->step++;
        }
    }

    #[Transition(type: 'backward')]
    public function previousStep(): void
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function goToStep(int $targetStep): void
    {
        if ($targetStep < $this->step && $targetStep >= 1) {
            $this->step = $targetStep;
        }
    }

    public function send(): void
    {
        $this->validateStep();

        $order = Order::create($this->form->all() + ['type' => OrderType::Manufacturing]);

        $order->manufacture()->create([
            'knife_type' => $this->knife_type,
            'blade_shape' => $this->blade_shape,
            'blade_steel' => $this->blade_steel,
            'blade_length' => $this->blade_length,
            'blade_thickness' => $this->blade_thickness,
            'handle_material' => $this->handle_material,
            'handle_color' => $this->handle_color,
            'sheath' => $this->sheath,
            'engraving' => $this->engraving,
            'engraving_text' => $this->engraving_text,
            'notes' => $this->notes,
        ]);

        if ($this->subscribe) {
            Subscriber::firstOrCreate(['email' => $this->form->email]);
        }

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
    {{-- Success State --}}
    <div class="min-h-[60vh] flex flex-col justify-center items-center py-16 lg:py-24 px-5">
        <div class="relative">
            <x-lucide-badge-check class="size-20 lg:size-28 fill-green-50 stroke-green-600" stroke-width="1.25" />
        </div>

        <div class="mt-10 text-center max-w-md">
            <div
                class="inline-flex items-center gap-2 px-4 py-1.5 bg-orange-100 text-orange-700 rounded-sm text-sm font-semibold mb-5">
                <x-lucide-hash class="size-3.5" />
                {{ session('success-order') }}
            </div>

            <h2 class="font-[Oswald] text-3xl lg:text-4xl text-zinc-800 font-bold tracking-tight">
                Замовлення прийнято
            </h2>

            <p class="text-zinc-600 mt-4 leading-relaxed text-balance">
                Найближчим часом я зв'яжусь з вами, щоб підтвердити деталі та узгодити виготовлення.
            </p>
        </div>

        <div class="mt-10 max-w-sm text-center">
            <p class="text-sm text-zinc-500 leading-relaxed">
                Є питання? Зателефонуйте
                <a href="tel:{{ $settings->phone }}"
                    class="text-orange-600 font-semibold hover:underline whitespace-nowrap">
                    {{ $settings->phone }}
                </a>
                або напишіть на
                <a href="mailto:{{ $settings->email }}" class="text-orange-600 font-semibold hover:underline">
                    {{ $settings->email }}
                </a>
            </p>
        </div>
    </div>
@else
    <x-section sidebar-position="right" x-data="knifeOrderForm($wire)">
        <div class="relative">
            {{-- Step Indicator - Mobile Only --}}
            <div class="lg:hidden mb-8">
                <div class="flex items-center justify-between text-sm mb-3">
                    <span class="text-zinc-500">
                        Крок <span class="font-bold text-zinc-800">{{ $step }}</span> з {{ $totalSteps }}
                    </span>
                    <span class="font-semibold text-orange-600">{{ $this->stepLabels[$step] }}</span>
                </div>
                <div class="h-2.5 bg-zinc-100 rounded-full overflow-hidden">
                    <div class="h-full bg-linear-to-r from-orange-500 to-orange-400 rounded-full transition-all duration-500 ease-out"
                        style="width: {{ $this->progress }}%"></div>
                </div>
            </div>

            {{-- ============================================= --}}
            {{-- STEP 1: Knife Type --}}
            {{-- ============================================= --}}
            <div wire:show="step === 1" x-cloak class="animate-fade-in">
                <header class="mb-8">
                    <h2 class="font-[Oswald] text-2xl lg:text-3xl font-bold text-zinc-800 tracking-tight">
                        Оберіть тип ножа
                    </h2>
                    <p class="text-zinc-500 mt-2">Виберіть категорію, яка найкраще відповідає вашим потребам</p>
                </header>

                <button type="button"
                    class="mb-8 text-sm font-medium text-orange-500 hover:text-orange-600 transition-colors inline-flex items-center gap-2 group">
                    <span class="p-1 bg-orange-50 rounded-sm group-hover:bg-orange-100 transition-colors">
                        <x-lucide-info class="size-4" />
                    </span>
                    Дізнатись більше про типи ножів
                </button>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach (KnifeCollection::cases() as $type)
                        <button type="button" wire:click="$set('knife_type', '{{ $type->getLabel() }}')"
                            @class([
                                'relative rounded-sm text-center transition-all duration-200 cursor-pointer h-48 lg:h-56 w-full overflow-hidden group',
                                'ring-2 ring-orange-500 ring-offset-2 bg-orange-50' =>
                                    $knife_type === $type->getLabel(),
                                'bg-white border border-zinc-200 hover:border-orange-300' =>
                                    $knife_type !== $type->getLabel(),
                            ])>

                            <img src="{{ Vite::asset("resources/images/{$type->icons()}") }}"
                                alt="{{ $type->getLabel() }}"
                                class="absolute inset-0 size-full object-contain p-6 -rotate-35 group-hover:scale-110 group-hover:-rotate-40 transition-all duration-500 ease-out">

                            @if ($knife_type === $type->getLabel())
                                <div class="absolute top-3 right-3 bg-orange-500 text-white p-1.5 rounded-sm z-10">
                                    <x-lucide-check class="size-4" stroke-width="3" />
                                </div>
                            @endif

                            <div @class([
                                'absolute inset-x-0 bottom-0 pt-10 pb-4 transition-all duration-200',
                                'bg-transparent' => $knife_type === $type->getLabel(),
                                'bg-linear-to-t from-white via-white/95 to-transparent' =>
                                    $knife_type !== $type->getLabel(),
                            ])>
                                <span @class([
                                    'text-sm font-bold tracking-wide relative z-10',
                                    'text-orange-600' => $knife_type === $type->getLabel(),
                                    'text-zinc-700 group-hover:text-zinc-900' =>
                                        $knife_type !== $type->getLabel(),
                                ])>
                                    {{ $type->getLabel() }}
                                </span>
                            </div>
                        </button>
                    @endforeach

                    {{-- Other Option --}}
                    <button type="button" wire:click="$set('knife_type', 'Інший')" @class([
                        'relative rounded-sm text-center transition-all duration-200 cursor-pointer h-48 lg:h-56 w-full overflow-hidden group',
                        'ring-2 ring-orange-500 ring-offset-2 bg-orange-50' =>
                            $knife_type === 'Інший',
                        'bg-white border border-zinc-200 hover:border-orange-300' =>
                            $knife_type !== 'Інший',
                    ])>
                        <img src="{{ Vite::asset('resources/images/other-icon.png') }}" alt="Інший"
                            class="absolute inset-0 size-full object-contain p-6 -rotate-35 
               group-hover:scale-110 group-hover:-rotate-40 
               transition-all duration-500 ease-out">

                        @if ($knife_type === 'Інший')
                            <div class="absolute top-3 right-3 bg-orange-500 text-white p-1.5 rounded-sm z-10">
                                <x-lucide-check class="size-4" stroke-width="3" />
                            </div>
                        @endif

                        <div @class([
                            'absolute inset-x-0 bottom-0 pt-10 pb-4 transition-all duration-200',
                            'bg-transparent' => $knife_type === 'Інший',
                            'bg-linear-to-t from-white via-white/95 to-transparent' =>
                                $knife_type !== 'Інший',
                        ])>
                            <span @class([
                                'text-sm font-bold tracking-wide relative z-10',
                                'text-orange-600' => $knife_type === 'Інший',
                                'text-zinc-700 group-hover:text-zinc-900' => $knife_type !== 'Інший',
                            ])>
                                Інший
                            </span>
                        </div>
                    </button>
                </div>

                @error('knife_type')
                    <p class="mt-6 text-sm text-red-600 flex items-center gap-2 p-3 bg-red-50 rounded-sm">
                        <x-lucide-alert-circle class="size-5 shrink-0" />
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- ============================================= --}}
            {{-- STEP 2: Blade Configuration --}}
            {{-- ============================================= --}}
            <div wire:show="step === 2" x-cloak class="animate-fade-in">
                <header class="mb-8">
                    <h2 class="font-[Oswald] text-2xl lg:text-3xl font-bold text-zinc-800 tracking-tight">
                        Параметри клинка
                    </h2>
                    <p class="text-zinc-500 mt-2">Налаштуйте характеристики леза під ваші потреби</p>
                </header>

                <button type="button"
                    class="mb-8 text-sm font-medium text-orange-500 hover:text-orange-600 transition-colors inline-flex items-center gap-2 group">
                    <span class="p-1 bg-orange-50 rounded-sm group-hover:bg-orange-100 transition-colors">
                        <x-lucide-info class="size-4" />
                    </span>
                    Які бувають форми клинків?
                </button>

                {{-- Blade Shape --}}
                <x-form.group class="mb-8">
                    <x-form.label class="text-zinc-700 font-bold text-base mb-4 block">Форма клинка</x-form.label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach (BladeShape::cases() as $item)
                            <button type="button" @click="form.bladeShape = '{{ $item->getLabel() }}'"
                                :class="form.bladeShape === '{{ $item->getLabel() }}' ?
                                    'ring-2 ring-orange-500 bg-orange-50 border-transparent' :
                                    'bg-white border-zinc-200 hover:border-orange-300 hover:bg-zinc-50'"
                                class="relative border rounded-sm p-5 text-center transition-all duration-200 h-28 flex flex-col items-center justify-center cursor-pointer group">
                                <div class="size-8 mb-3 transition-colors"
                                    :class="form.bladeShape === '{{ $item->getLabel() }}' ? 'text-orange-500' :
                                        'text-zinc-400 group-hover:text-orange-400'">
                                    <x-lucide-sword class="size-full" stroke-width="1.5" />
                                </div>
                                <span class="text-sm font-semibold text-zinc-700">{{ $item->getLabel() }}</span>
                            </button>
                        @endforeach
                    </div>
                </x-form.group>

                {{-- Steel Type --}}
                <x-form.group class="mb-8">
                    <x-form.label>
                        Тип сталі
                        <span class="text-red-500 text-lg">*</span>
                    </x-form.label>
                    <div class="max-w-md">
                        <x-form.select wire:model.live="blade_steel">
                            @foreach (SteelType::cases() as $item)
                                <option value="{{ $item->getLabel() }}">{{ $item->getLabel() }}</option>
                            @endforeach
                        </x-form.select>
                        @error('blade_steel')
                            <p class="mt-3 text-sm text-red-600 flex items-center gap-2">
                                <x-lucide-alert-circle class="size-4 shrink-0" />
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </x-form.group>

                {{-- Dimensions --}}
                <div class="grid grid-cols-2 gap-5 max-w-md">
                    <x-form.group>
                        <x-form.label>
                            Довжина клинка
                            <span class="text-red-500 text-lg">*</span>
                        </x-form.label>
                        <div class="relative">
                            <x-form.input type="number" wire:model.live="blade_length" placeholder="150" min="50"
                                max="500"
                                class="w-full px-5 py-3.5 pr-14 bg-white border border-zinc-200 rounded-sm text-zinc-800 font-medium
                                       focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all" />
                            <span class="absolute right-5 top-1/2 -translate-y-1/2 text-zinc-400 font-medium">мм</span>
                        </div>
                        @error('blade_length')
                            <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </x-form.group>

                    <x-form.group>
                        <x-form.label>Товщина</x-form.label>
                        <div class="relative">
                            <x-form.input type="number" wire:model.live="blade_thickness" placeholder="4" min="1"
                                max="10"
                                class="w-full px-5 py-3.5 pr-14 bg-white border border-zinc-200 rounded-sm text-zinc-800 font-medium
                                       focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all" />
                            <span class="absolute right-5 top-1/2 -translate-y-1/2 text-zinc-400 font-medium">мм</span>
                        </div>
                    </x-form.group>
                </div>
            </div>

            {{-- ============================================= --}}
            {{-- STEP 3: Handle --}}
            {{-- ============================================= --}}
            <div wire:show="step === 3" x-cloak class="animate-fade-in">
                <header class="mb-8">
                    <h2 class="font-[Oswald] text-2xl lg:text-3xl font-bold text-zinc-800 tracking-tight">
                        Руків'я
                    </h2>
                    <p class="text-zinc-500 mt-2">Оберіть матеріал та колір для комфортного хвату</p>
                </header>

                <button type="button"
                    class="mb-8 text-sm font-medium text-orange-500 hover:text-orange-600 transition-colors inline-flex items-center gap-2 group">
                    <span class="p-1 bg-orange-50 rounded-sm group-hover:bg-orange-100 transition-colors">
                        <x-lucide-info class="size-4" />
                    </span>
                    Як обрати матеріал руків'я?
                </button>

                {{-- Handle Material --}}
                <x-form.group class="mb-10">
                    <x-form.label class="text-zinc-700 font-bold text-base mb-4 block">Матеріал</x-form.label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 max-w-2xl">
                        @php
                            $materials = [
                                ['name' => 'Дерево', 'desc' => 'Класичний вибір', 'icon' => 'tree-deciduous'],
                                ['name' => 'Micarta', 'desc' => 'Міцний композит', 'icon' => 'layers'],
                                ['name' => 'G10', 'desc' => 'Надміцний пластик', 'icon' => 'hexagon'],
                                ['name' => 'Carbon Fiber', 'desc' => 'Легкий та міцний', 'icon' => 'diamond'],
                                ['name' => 'Ріг', 'desc' => 'Природний матеріал', 'icon' => 'mountain'],
                                ['name' => 'Кістка', 'desc' => 'Традиційний вибір', 'icon' => 'bone'],
                            ];
                        @endphp

                        @foreach ($materials as $material)
                            <button type="button" wire:click="$set('handle_material', '{{ $material['name'] }}')"
                                @class([
                                    'flex flex-col items-start p-5 rounded-sm border transition-all duration-200 cursor-pointer group text-left',
                                    'ring-2 ring-orange-500 bg-orange-50 border-transparent' =>
                                        $handle_material === $material['name'],
                                    'bg-white border-zinc-200 hover:border-orange-300' =>
                                        $handle_material !== $material['name'],
                                ])>
                                <span @class([
                                    'p-2.5 rounded-sm mb-3 transition-colors',
                                    'bg-orange-500 text-white' => $handle_material === $material['name'],
                                    'bg-zinc-100 text-zinc-400 group-hover:bg-orange-100 group-hover:text-orange-500' =>
                                        $handle_material !== $material['name'],
                                ])>
                                    @switch($material['icon'])
                                        @case('tree-deciduous')
                                            <x-lucide-tree-deciduous class="size-5" />
                                        @break

                                        @case('layers')
                                            <x-lucide-layers class="size-5" />
                                        @break

                                        @case('hexagon')
                                            <x-lucide-hexagon class="size-5" />
                                        @break

                                        @case('diamond')
                                            <x-lucide-diamond class="size-5" />
                                        @break

                                        @case('mountain')
                                            <x-lucide-mountain class="size-5" />
                                        @break

                                        @case('bone')
                                            <x-lucide-bone class="size-5" />
                                        @break
                                    @endswitch
                                </span>
                                <span class="font-bold text-zinc-800">{{ $material['name'] }}</span>
                                <span class="text-xs text-zinc-500 mt-0.5">{{ $material['desc'] }}</span>
                            </button>
                        @endforeach
                    </div>
                </x-form.group>

                {{-- Handle Color --}}
                <x-form.group>
                    <x-form.label class="text-zinc-700 font-bold text-base mb-4 block">Колір руків'я</x-form.label>

                    <div class="flex flex-wrap gap-4 mb-6">
                        <template x-for="color in handleColors" :key="color.value">
                            <button type="button" x-on:click="$wire.handle_color = color.value"
                                :class="$wire.handle_color === color.value ?
                                    'ring-2 ring-orange-500 ring-offset-2 scale-110' :
                                    'hover:scale-110 ring-1 ring-zinc-200'"
                                class="size-14 rounded-sm transition-all duration-200 cursor-pointer"
                                :style="`background:${color.value}`" :title="color.name">
                                <span class="sr-only" x-text="color.name"></span>
                            </button>
                        </template>
                    </div>

                    {{-- Custom Color --}}
                    <div class="inline-flex items-center gap-5 p-5 bg-zinc-50 rounded-sm">
                        <div class="relative">
                            <input type="color" wire:model.live="handle_color"
                                class="size-14 rounded-sm cursor-pointer border border-zinc-200">
                        </div>
                        <div>
                            <p class="text-sm text-zinc-500 font-medium">Свій колір</p>
                            <p class="text-base font-mono font-bold text-zinc-800 mt-0.5" wire:text="handle_color">
                                {{ $handle_color ?: '#000000' }}</p>
                        </div>
                    </div>
                </x-form.group>
            </div>

            {{-- ============================================= --}}
            {{-- STEP 4: Additional Options --}}
            {{-- ============================================= --}}
            <div wire:show="step === 4" x-cloak class="animate-fade-in">
                <header class="mb-8">
                    <h2 class="font-[Oswald] text-2xl lg:text-3xl font-bold text-zinc-800 tracking-tight">
                        Додаткові опції
                    </h2>
                    <p class="text-zinc-500 mt-2">Персоналізуйте ваш ніж</p>
                </header>

                {{-- Option Cards --}}
                <div class="grid sm:grid-cols-2 gap-5 max-w-xl mb-8">
                    {{-- Sheath --}}
                    <label @class([
                        'flex items-start gap-5 p-6 rounded-sm border cursor-pointer transition-all duration-200',
                        'border-orange-500 bg-orange-50' => $sheath,
                        'border-zinc-200 bg-white hover:border-orange-300' => !$sheath,
                    ])>
                        <input type="checkbox" wire:model.live="sheath" class="sr-only">
                        <div @class([
                            'flex items-center justify-center size-14 rounded-sm shrink-0 transition-all duration-200',
                            'bg-orange-500 text-white' => $sheath,
                            'bg-zinc-100 text-zinc-400' => !$sheath,
                        ])>
                            <x-lucide-package class="size-6" />
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <p class="font-bold text-zinc-800 text-lg">Піхви</p>
                                <div @class([
                                    'size-6 rounded-sm border flex items-center justify-center transition-all',
                                    'bg-orange-500 border-orange-500' => $sheath,
                                    'border-zinc-300' => !$sheath,
                                ])>
                                    @if ($sheath)
                                        <x-lucide-check class="size-4 text-white" stroke-width="3" />
                                    @endif
                                </div>
                            </div>
                            <p class="text-sm text-zinc-500 mt-1">Шкіряні піхви для зберігання та носіння ножа</p>
                        </div>
                    </label>

                    {{-- Engraving --}}
                    <label @class([
                        'flex items-start gap-5 p-6 rounded-sm border cursor-pointer transition-all duration-200',
                        'border-orange-500 bg-orange-50' => $engraving,
                        'border-zinc-200 bg-white hover:border-orange-300' => !$engraving,
                    ])>
                        <input type="checkbox" wire:model.live="engraving" class="sr-only">
                        <div @class([
                            'flex items-center justify-center size-14 rounded-sm shrink-0 transition-all duration-200',
                            'bg-orange-500 text-white' => $engraving,
                            'bg-zinc-100 text-zinc-400' => !$engraving,
                        ])>
                            <x-lucide-pen-tool class="size-6" />
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <p class="font-bold text-zinc-800 text-lg">Гравіювання</p>
                                <div @class([
                                    'size-6 rounded-sm border flex items-center justify-center transition-all',
                                    'bg-orange-500 border-orange-500' => $engraving,
                                    'border-zinc-300' => !$engraving,
                                ])>
                                    @if ($engraving)
                                        <x-lucide-check class="size-4 text-white" stroke-width="3" />
                                    @endif
                                </div>
                            </div>
                            <p class="text-sm text-zinc-500 mt-1">Персональний напис, ініціали або символ</p>
                        </div>
                    </label>
                </div>

                {{-- Engraving Text --}}
                <div wire:show="engraving" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                    class="max-w-xl mb-8">
                    <x-form.label class="text-zinc-700 font-bold text-base mb-3 block">Текст гравіювання</x-form.label>
                    <x-form.input wire:model.live="engraving_text" placeholder="Введіть текст для гравіювання..."
                        maxlength="50"
                        class="w-full px-5 py-4 bg-white border border-zinc-200 rounded-sm text-zinc-800 font-medium
                               focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all" />
                    <p class="text-sm text-zinc-400 mt-2 flex items-center gap-2">
                        <x-lucide-type class="size-4" />
                        Максимум 50 символів
                    </p>
                </div>

                {{-- Notes --}}
                <x-form.group class="max-w-xl">
                    <x-form.label class="text-zinc-700 font-bold text-base mb-3 block">Додаткові побажання</x-form.label>
                    <x-form.textarea wire:model.live="notes" rows="5"
                        placeholder="Опишіть будь-які особливі побажання щодо вашого ножа: особливості конструкції, деталі оформлення, або інші ідеї..."
                        class="w-full px-5 py-4 bg-white border border-zinc-200 rounded-sm text-zinc-800
                               focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all resize-none"></x-form.textarea>
                </x-form.group>
            </div>

            {{-- ============================================= --}}
            {{-- STEP 5: Contact Info --}}
            {{-- ============================================= --}}
            <div wire:show="step === 5" x-cloak class="animate-fade-in">
                <header class="mb-8">
                    <h2 class="font-[Oswald] text-2xl lg:text-3xl font-bold text-zinc-800 tracking-tight">
                        Контактна інформація
                    </h2>
                    <p class="text-zinc-500 mt-2">Вкажіть ваші дані для оформлення замовлення</p>
                </header>

                @include('partials.order.order-form')
            </div>
        </div>

        {{-- ============================================= --}}
        {{-- Navigation Buttons --}}
        {{-- ============================================= --}}
        <nav class="flex items-center justify-between mt-12 pt-8 border-t-2 border-zinc-100" x-cloak
            aria-label="Навігація форми">
            {{-- Back Button --}}
            <x-button variant="soft" color="dark" wire:show="step > 1" wire:click="previousStep"
                wire:loading.attr="disabled" wire:target="previousStep">
                <x-lucide-arrow-left wire:loading.remove wire:target="previousStep" class="size-5" />
                <x-lucide-loader-2 wire:loading wire:target="previousStep" class="size-5 animate-spin" />
                Назад
            </x-button>

            <div class="flex-1"></div>

            {{-- Next Button --}}
            <x-button wire:show="step < 5" wire:click="nextStep" wire:loading.attr="disabled" wire:target="nextStep"
                class="">
                Далі
                <x-lucide-arrow-right wire:loading.remove wire:target="nextStep" class="size-5" />
                <x-lucide-loader-2 wire:loading wire:target="nextStep" class="size-5 animate-spin" />
            </x-button>

            {{-- Submit Button --}}
            <button type="button" wire:show="step === 5" wire:click="send" wire:loading.attr="disabled"
                wire:target="send"
                class="inline-flex items-center gap-2.5 px-8 py-3 text-sm font-bold text-white bg-orange-500 hover:bg-orange-600 rounded-xl shadow-xl shadow-orange-500/25 hover:shadow-orange-500/40 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                Відправити замовлення
                <x-lucide-send wire:loading.remove wire:target="send" class="size-5" />
                <x-lucide-loader-2 wire:loading wire:target="send" class="size-5 animate-spin" />
            </button>
        </nav>

        {{-- ============================================= --}}
        {{-- Sidebar --}}
        {{-- ============================================= --}}
        <x-slot:sidebar>
            <div class="lg:sticky lg:top-24 space-y-5">
                {{-- Vertical Step Indicator - Desktop Only --}}
                <nav class="hidden lg:block" aria-label="Кроки замовлення">
                    <div class="relative">
                        @foreach ($this->stepLabels as $num => $label)
                            <div class="relative flex items-start gap-4 @if (!$loop->last) pb-8 @endif">
                                {{-- Connecting Line --}}
                                @if (!$loop->last)
                                    <div
                                        class="absolute left-5 top-10 w-0.5 h-full -translate-x-1/2 transition-colors duration-300 {{ $num < $step ? 'bg-orange-300' : 'bg-zinc-200' }}">
                                    </div>
                                @endif

                                {{-- Step Circle --}}
                                <button type="button" wire:click="goToStep({{ $num }})"
                                    @class([
                                        'relative z-10 flex items-center justify-center size-10 rounded-full text-sm font-bold transition-all duration-300 shrink-0',
                                        'bg-orange-500 text-white shadow-lg shadow-orange-500/30 ring-4 ring-orange-500/20' =>
                                            $num === $step,
                                        'bg-orange-100 text-orange-600 hover:bg-orange-200 cursor-pointer' =>
                                            $num < $step,
                                        'bg-zinc-100 text-zinc-400 cursor-default' => $num > $step,
                                    ]) @if ($num > $step) disabled @endif
                                    aria-current="{{ $num === $step ? 'step' : 'false' }}">
                                    @if ($num < $step)
                                        <x-lucide-check class="size-5" stroke-width="2.5" />
                                    @else
                                        {{ $num }}
                                    @endif
                                </button>

                                {{-- Step Label --}}
                                <div class="pt-2">
                                    <p @class([
                                        'text-sm font-semibold transition-colors',
                                        'text-zinc-800' => $num === $step,
                                        'text-zinc-600' => $num < $step,
                                        'text-zinc-400' => $num > $step,
                                    ])>
                                        {{ $label }}
                                    </p>
                                    @if ($num === $step)
                                        <p class="text-xs text-orange-500 font-medium mt-0.5">Поточний крок</p>
                                    @elseif($num < $step)
                                        <p class="text-xs text-zinc-400 mt-0.5">Завершено</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </nav>

                {{-- Order Summary --}}
                <div x-show="$wire.knife_type || $wire.blade_steel || $wire.handle_material"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                    class="">
                    <h3 class="font-[Oswald] text-xl font-bold text-zinc-800 tracking-wide flex items-center gap-3 mb-6">
                        Ваше замовлення
                    </h3>

                    <div class="space-y-5">
                        {{-- Knife Type --}}
                        <div wire:show="knife_type" class="flex items-start gap-4">
                            <div class="size-10 rounded-xl bg-zinc-200/60 flex items-center justify-center shrink-0">
                                <x-lucide-sword class="size-5 text-zinc-600" />
                            </div>
                            <div>
                                <p class="text-xs text-zinc-400 uppercase tracking-wider font-bold">Тип ножа</p>
                                <p class="text-sm font-semibold text-zinc-800 mt-0.5" wire:text="knife_type"></p>
                            </div>
                        </div>

                        {{-- Steel --}}
                        <div wire:show="blade_steel" class="flex items-start gap-4">
                            <div class="size-10 rounded-xl bg-zinc-200/60 flex items-center justify-center shrink-0">
                                <x-lucide-shield class="size-5 text-zinc-600" />
                            </div>
                            <div>
                                <p class="text-xs text-zinc-400 uppercase tracking-wider font-bold">Сталь</p>
                                <p class="text-sm font-semibold text-zinc-800 mt-0.5" wire:text="blade_steel"></p>
                            </div>
                        </div>

                        {{-- Blade Shape --}}
                        <div wire:show="blade_shape" class="flex items-start gap-4">
                            <div class="size-10 rounded-xl bg-zinc-200/60 flex items-center justify-center shrink-0">
                                <x-lucide-pen class="size-5 text-zinc-600" />
                            </div>
                            <div>
                                <p class="text-xs text-zinc-400 uppercase tracking-wider font-bold">Форма клинка</p>
                                <p class="text-sm font-semibold text-zinc-800 mt-0.5" wire:text="blade_shape"></p>
                            </div>
                        </div>

                        {{-- Dimensions --}}
                        <div wire:show="blade_length" class="flex items-start gap-4">
                            <div class="size-10 rounded-xl bg-zinc-200/60 flex items-center justify-center shrink-0">
                                <x-lucide-ruler class="size-5 text-zinc-600" />
                            </div>
                            <div>
                                <p class="text-xs text-zinc-400 uppercase tracking-wider font-bold">Розміри</p>
                                <p class="text-sm font-semibold text-zinc-800 mt-0.5">
                                    <span wire:text="blade_length"></span> мм
                                    <span wire:show="blade_thickness" class="text-zinc-400">
                                        / <span wire:text="blade_thickness"></span> мм
                                    </span>
                                </p>
                            </div>
                        </div>

                        {{-- Handle --}}
                        <div wire:show="handle_material" class="flex items-start gap-4">
                            <div class="size-10 rounded-xl bg-zinc-200/60 flex items-center justify-center shrink-0">
                                <x-lucide-grip class="size-5 text-zinc-600" />
                            </div>
                            <div>
                                <p class="text-xs text-zinc-400 uppercase tracking-wider font-bold">Руків'я</p>
                                <p class="text-sm font-semibold text-zinc-800 mt-0.5" wire:text="handle_material"></p>
                            </div>
                        </div>

                        {{-- Handle Color --}}
                        <div wire:show="handle_color" class="flex items-start gap-4">
                            <div class="size-10 rounded-xl shrink-0 border-2 border-zinc-200 shadow-inner"
                                :style="`background-color: ${$wire.handle_color}`"></div>
                            <div>
                                <p class="text-xs text-zinc-400 uppercase tracking-wider font-bold">Колір</p>
                                <p class="text-sm font-semibold text-zinc-800 font-mono mt-0.5" wire:text="handle_color">
                                </p>
                            </div>
                        </div>

                        {{-- Options --}}
                        <div wire:show="sheath || engraving" class="flex items-start gap-4">
                            <div class="size-10 rounded-xl bg-zinc-200/60 flex items-center justify-center shrink-0">
                                <x-lucide-sparkles class="size-5 text-zinc-600" />
                            </div>
                            <div>
                                <p class="text-xs text-zinc-400 uppercase tracking-wider font-bold">Опції</p>
                                <div class="flex flex-wrap gap-2 mt-1.5">
                                    <span wire:show="sheath"
                                        class="text-xs px-3 py-1 bg-orange-100 text-orange-700 rounded-sm font-semibold">Піхви</span>
                                    <span wire:show="engraving"
                                        class="text-xs px-3 py-1 bg-orange-100 text-orange-700 rounded-sm font-semibold">Гравіювання</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Divider --}}
                    <div class="border-t-2 border-zinc-100 my-6"></div>

                    {{-- Price Note --}}
                    <div class="flex items-start gap-3 p-4 bg-zinc-100 rounded-lg">
                        <x-lucide-info class="size-5 text-zinc-400 shrink-0 mt-0.5" />
                        <p class="text-sm text-zinc-500 leading-relaxed">
                            Остаточна вартість буде розрахована після узгодження з майстром всіх деталей замовлення.
                        </p>
                    </div>
                </div>

                {{-- Contact Card --}}
                <div class="">
                    <div class="text-sm font-semibold text-zinc-700 mb-4">
                        Є питання? Зв'яжіться:
                    </div>
                    <div class="space-y-3">
                        <a href="tel:+380639518842"
                            class="flex items-center gap-3 text-sm font-medium text-zinc-700 hover:text-orange-600 transition-colors group">
                            <span class="p-2 bg-white rounded-md">
                                <x-lucide-phone class="size-4 text-orange-500" />
                            </span>
                            {{ $settings->phone }}
                        </a>
                        <a href="mailto:dzhogun@gmail.com"
                            class="flex items-center gap-3 text-sm font-medium text-zinc-700 hover:text-orange-600 transition-colors group">
                            <span class="p-2 bg-white rounded-md">
                                <x-lucide-mail class="size-4 text-orange-500" />
                            </span>
                            {{ $settings->email }}
                        </a>
                    </div>
                </div>
            </div>
        </x-slot:sidebar>
    </x-section>

    {{-- Alpine.js Component --}}
    <script>
        function knifeOrderForm($wire) {
            return {
                form: {
                    bladeShape: $wire.entangle('blade_shape'),
                    handleColor: $wire.entangle('handle_color'),
                },

                handleColors: [{
                        name: 'Чорний',
                        value: '#111111'
                    },
                    {
                        name: 'Горіх',
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
                        name: 'Бордовий',
                        value: '#8b0000'
                    },
                    {
                        name: 'Темно-синій',
                        value: '#1e3a8a'
                    },
                    {
                        name: 'Помаранчевий',
                        value: '#ea580c'
                    },
                    {
                        name: 'Сірий',
                        value: '#525252'
                    },
                ],
            }
        }
    </script>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
    </style>
@endsession
