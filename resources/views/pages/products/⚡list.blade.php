<?php

use App\Enums\ProductCategory;
use Illuminate\Support\Facades\Cookie;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\Session;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use App\Models\Product;

new class extends Component {
    use WithPagination;

    public ?string $collection = null;

    public string $search = '';

    #[Session]
    public array $collections = [];

    #[Session]
    public array $steels = [];

    #[Session]
    public array $blade_shapes = [];

    #[Session]
    public array $handle_materials = [];

    #[Session]
    public string $status = 'all';

    #[Session]
    public int $price_from = 0;

    #[Session]
    public int $price_to = 0;

    public int $minLimit;
    public int $maxLimit;

    public $filters = [];

    #[Url, Session]
    public $sortBy = 'created_at';

    #[Url, Session]
    public $sortDirection = 'desc';

    public $perPage = 12;
    public $view = 'grid';

    public function mount()
    {
        $this->minLimit = (int) Product::min('price') ?: 0;
        $this->maxLimit = (int) Product::max('price') ?: 5000;

        $this->price_from = $this->minLimit;
        $this->price_to = $this->maxLimit;

        $this->view = Cookie::get('product_view', 'grid');
    }

    public function resetPrice()
    {
        $this->price_from = $this->minLimit;
        $this->price_to = $this->maxLimit;
    }

    public function resetFilters()
    {
        $this->collectios = [];
        $this->status = 'all';
        $this->handle_materials = [];
        $this->blade_shapes = [];
        $this->steels = [];

        $this->price_from = $this->minLimit;
        $this->price_to = $this->maxLimit;

        if ($this->collection) {
            $this->collections = [$this->collection];
        }
    }

    public function updatedView($value)
    {
        Cookie::queue('product_view', $value, 43200);
    }

    public function loadMore()
    {
        $this->perPage += 4;
    }

    public function setView($value): void
    {
        $this->view = $value;
        Cookie::queue('product_view', $value, 60 * 24 * 30);
    }

    public function setSort(string $by, string $direction = 'asc'): void
    {
        $this->sortBy = $by;
        $this->sortDirection = $direction;

        Cookie::queue('product_sort_by', $by, 60 * 24 * 30);
        Cookie::queue('product_sort_dir', $direction, 60 * 24 * 30);
    }

    #[Computed]
    public function products()
    {
        return Product::query()
            ->when($this->collection, fn($q) => $q->where('collection', $this->collection))
            ->filter([
                'search' => $this->search,
                'collections' => $this->collections,
                'steels' => $this->steels,
                'blade_shapes' => $this->blade_shapes,
                'handle_materials' => $this->handle_materials,
                'status' => $this->status,
                'price_from' => $this->price_from,
                'price_to' => $this->price_to,
            ])
            ->withCount(['likes', 'comments'])
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    #[Computed]
    public function categoryCounts()
    {
        return Product::query()->select('collection')->selectRaw('count(*) as total')->groupBy('collection')->pluck('total', 'collection')->toArray();
    }
};
?>

@section('header')
    <x-header :image="Vite::asset('resources/images/' . (ProductCategory::tryFrom($collection)?->images() ?? 'header.png'))">
        <x-slot:title>
            {{ ProductCategory::tryFrom($collection)?->getLabel() ?? 'Каталог товарів' }}
        </x-slot:title>
        <x-slot:description>
            Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quas, tenetur animi voluptas
            veniam repellat eius.
        </x-slot:description>
    </x-header>
@endsection

<div x-data="{ open: false }" class="lg:min-h-screen bg-neutral-50">
    <div class="max-w-5xl xl:max-w-6xl mx-auto lg:grid lg:grid-cols-3 lg:gap-10">
        <aside
            class="sticky top-16 lg:top-14 z-40 lg:h-screen w-full border-b lg:border-b-0 lg:border-r border-zinc-200 bg-linear-to-b lg:bg-linear-to-r from-zinc-50 lg:from-transparent to-zinc-100">
            <div class="h-16 w-full bg-zinc-100 px-5 lg:hidden flex justify-center items-center overflow-hidden">
                <div x-show="open" class="me-auto">
                    <div class="text-sm text-gray-700 font-semibold">Фільтри</div>
                    <div class="text-xs text-gray-400">
                        Знайдено: {{ $this->products->count() }}
                        {{ trans_choice('товар|товари|товарів', $this->products->count(), [], 'uk') }}
                    </div>
                </div>
                <button type="button" @click="open = !open"
                    class="ms-auto h-10 rounded-sm bg-black text-white flex justify-center items-center"
                    :class="open ? 'w-fit px-2.5' : 'w-10'">
                    <span class="text-sm" x-show="open">Показати</span>
                    <x-lucide-funnel class="size-5" x-bind:class="open ? 'hidden' : 'block'" />
                </button>
            </div>
            <div class="lg:h-[calc(100vh-3.5rem)] px-5 lg:px-0 lg:pr-8 lg:pt-10 flex flex-col justify-between overflow-hidden"
                :class="open ? 'h-[calc(100vh-8rem)]' : 'h-0'" x-transition>
                <!-- 1. СТАТУС -->
                <div class="grid grid-cols-3 gap-x-0.5 p-1.5 mb-2.5 bg-white rounded-md border border-zinc-200">
                    @foreach (['all' => 'Всі', 'in_stock' => 'Наявні', 'sold' => 'Продані'] as $val => $label)
                        <button type="button" wire:click="$set('status', '{{ $val }}')"
                            class="py-2.5 text-xs font-semibold tracking-wide rounded-md transition-all duration-500 cursor-pointer 
                        {{ $status === $val ? 'bg-neutral-900 text-white' : 'text-neutral-500 hover:bg-zinc-50 hover:text-neutral-700' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>

                <div
                    class="flex-1 space-y-10 pt-2.5 pr-1.5 overflow-y-auto overflow-x-hidden [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-stone-300/0 hover:[&::-webkit-scrollbar-thumb]:bg-stone-300/90">

                    <!-- 2. БЮДЖЕТ -->
                    <div class="space-y-5" x-data="{
                        expanded: $persist(true).as('prices-expanded'),
                        minL: @js((int) $minLimit),
                        maxL: @js((int) $maxLimit),
                        from: @entangle('price_from'),
                        to: @entangle('price_to')
                    }"
                        wire:loading.class="animate-pulse pointer-events-none" wire:target="price_from, price_to"
                        x-cloak>
                        <div class="flex items-center justify-between w-full group outline-none">
                            <div
                                class="flex items-center gap-1.5 text-sm font-extrabold uppercase text-neutral-600 font-[Oswald] tracking-wide">
                                <x-lucide-wallet class="size-4" />
                                Бюджет
                            </div>
                            <button @click="expanded = !expanded" type="button" class="">
                                <x-lucide-chevron-down class="size-4 text-neutral-500 transition-transform duration-300"
                                    x-bind:class="expanded ? 'rotate-180' : ''" />
                            </button>
                        </div>

                        <!-- 2. КОНТЕНТ АКОРДЕОНА (Ціна, Скидання, Повзунки) -->
                        <div x-show="expanded" class="space-y-5" x-collapse>
                            <!-- Ряд з ціною та кнопкою скидання -->
                            <div class="flex justify-between items-end">
                                <div class="text-2xl font-light tracking-tighter text-stone-950">
                                    <span x-text="Number(from).toLocaleString()"></span> —
                                    <span x-text="Number(to).toLocaleString()"></span>
                                    <span class="text-xs align-top ml-1 font-bold text-zinc-400 uppercase">грн</span>
                                </div>

                                @if ($price_from !== $minLimit || $price_to !== $maxLimit)
                                    <button type="button" wire:click="resetPrice"
                                        class="p-2 rounded-full bg-white text-stone-500 hover:text-stone-800 hover:bg-stone-100 transition-all border border-stone-200 cursor-pointer">
                                        <x-lucide-rotate-ccw class="size-3.5" />
                                    </button>
                                @endif
                            </div>

                            <!-- Повзунки -->
                            <div class="relative py-1.5">
                                <div class="relative h-2 w-full rounded-full bg-stone-200">
                                    <div class="absolute h-full rounded-full bg-stone-950"
                                        :style="'left: ' + (((from - minL) / (maxL - minL)) * 100) + '%; right: ' + (100 - (
                                            (
                                                to -
                                                minL) / (
                                                maxL - minL)) * 100) + '%'">
                                    </div>

                                    <input type="range" :min="minL" :max="maxL"
                                        x-model.number="from" @change="$wire.set('price_from', from)"
                                        @input="if(from > to) from = to"
                                        class="pointer-events-none absolute -top-3 z-30 h-7 w-full appearance-none bg-transparent [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:size-6 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:border-[1.5px] [&::-webkit-slider-thumb]:border-stone-900 [&::-webkit-slider-thumb]:shadow-xl">

                                    <input type="range" :min="minL" :max="maxL"
                                        x-model.number="to" @change="$wire.set('price_to', to)"
                                        @input="if(to < from) to = from"
                                        class="pointer-events-none absolute -top-3 z-30 h-7 w-full appearance-none bg-transparent [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:size-6 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:border-[1.5px] [&::-webkit-slider-thumb]:border-stone-900 [&::-webkit-slider-thumb]:shadow-xl">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. КОЛЕКЦІЇ -->
                    @if (!$this->collection)
                        <div class="space-y-5" x-data="{ expanded: $persist(true).as('collection-expanded') }"
                            wire:loading.class="animate-pulse pointer-events-none" wire:target="collections" x-cloak>
                            <div class="flex items-center justify-between w-full group outline-none">
                                <div
                                    class="flex items-center gap-1.5 text-sm font-extrabold uppercase text-neutral-600 font-[Oswald] tracking-wide">
                                    <x-lucide-layers class="size-4" />
                                    Колекції
                                </div>
                                @if (count($collections))
                                    <button wire:click="$set('collections', [])" type="button"
                                        class="flex items-center text-xs ms-auto me-1.5 text-neutral-400 hover:text-neutral-500 font-medium transition-colors duration-250 cursor-pointer">
                                        <x-lucide-x-circle
                                            class="size-4 border border-neutral-200 rounded-full flex-none me-0.5" />
                                        очистити
                                    </button>
                                @endif
                                <button @click="expanded = !expanded" type="button" class="">
                                    <x-lucide-chevron-down
                                        class="size-4 text-neutral-500 transition-transform duration-300"
                                        x-bind:class="expanded ? 'rotate-180' : ''" />
                                </button>
                            </div>

                            <!-- Контент акордеона (Badge Cloud) -->
                            <div x-show="expanded" x-collapse>
                                <div class="flex flex-wrap gap-2.5">
                                    @foreach (ProductCategory::cases() as $collection)
                                        @php $isActive = in_array($collection->value, $collections); @endphp

                                        <label
                                            class="relative inline-flex items-center px-2.5 py-1.5 rounded-md border cursor-pointer transition-all duration-300 select-none
                        {{ $isActive
                            ? 'bg-neutral-900 border-neutral-900 text-white'
                            : 'bg-white border-neutral-200 text-gray-600 hover:border-neutral-200 hover:bg-neutral-100' }}">

                                            <input type="checkbox" value="{{ $collection->value }}"
                                                wire:model.live="collections" class="hidden">

                                            <span class="text-xs font-semibold tracking-tight">
                                                {{ $collection->getLabel() }}
                                            </span>

                                            @if ($isActive)
                                                <x-lucide-x
                                                    class="size-3.5 ml-1.5 text-stone-400 group-hover:text-white" />
                                            @endif
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- ФІЛЬТР: МАРКА СТАЛІ -->
                    <div class="space-y-5" x-data="{ expanded: $persist(true).as('steel-expanded') }"
                        wire:loading.class="animate-pulse pointer-events-none" wire:target="steels" x-cloak>
                        <div class="flex items-center justify-between w-full group outline-none">
                            <div
                                class="flex items-center gap-1.5 text-sm font-extrabold uppercase text-neutral-600 font-[Oswald] tracking-wide">
                                <x-lucide-layers class="size-4" />
                                Марка сталі
                            </div>
                            @if (count($steels))
                                <button wire:click="$set('steels', [])" type="button"
                                    class="flex items-center text-xs ms-auto me-1.5 text-neutral-400 hover:text-neutral-500 font-medium transition-colors duration-250 cursor-pointer">
                                    <x-lucide-x-circle
                                        class="size-4 border border-neutral-200 rounded-full flex-none me-0.5" />
                                    очистити
                                </button>
                            @endif
                            <button @click="expanded = !expanded" type="button" class="">
                                <x-lucide-chevron-down class="size-4 text-neutral-500 transition-transform duration-300"
                                    x-bind:class="expanded ? 'rotate-180' : ''" />
                            </button>
                        </div>

                        <div x-show="expanded" x-collapse>
                            <div class="flex flex-wrap gap-y-1.5 gap-x-2.5">
                                @foreach (App\Enums\SteelType::cases() as $steel)
                                    <label
                                        class="group flex items-center gap-x-1.5 cursor-pointer py-1 has-checked:bg-stone-50 rounded-lg transition-all duration-300">
                                        <div class="relative flex items-center justify-center">
                                            <input type="checkbox" value="{{ $steel->value }}"
                                                wire:model.live="steels"
                                                class="peer appearance-none size-5.5 border border-stone-300 rounded-sm checked:bg-stone-900 checked:border-stone-900 transition-all duration-300 cursor-pointer">

                                            <x-lucide-check
                                                class="absolute size-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"
                                                stroke-width="4" />
                                        </div>

                                        <span
                                            class="text-sm font-semibold capitalize transition-all duration-300 text-neutral-500 group-hover:text-stone-900 group-has-checked:text-black tracking-tight">
                                            {{ $steel->value }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- ФІЛЬТР: МАТЕРІАЛ РУКІВ'Я -->
                    <div class="space-y-5" x-data="{ expanded: $persist(true).as('handle-expanded') }"
                        wire:loading.class="animate-pulse pointer-events-none" wire:target="handle_materials" x-cloak>
                        <div class="flex items-center justify-between w-full group outline-none">
                            <div
                                class="flex items-center gap-1.5 text-sm font-extrabold uppercase text-neutral-600 font-[Oswald] tracking-wide">
                                <x-lucide-layers class="size-4" />
                                Матеріал руків'я
                            </div>
                            @if (count($handle_materials))
                                <button wire:click="$set('handle_materials', [])" type="button"
                                    class="flex items-center text-xs ms-auto me-1.5 text-neutral-400 hover:text-neutral-500 font-medium transition-colors duration-250 cursor-pointer">
                                    <x-lucide-x-circle
                                        class="size-4 border border-neutral-200 rounded-full flex-none me-0.5" />
                                    очистити
                                </button>
                            @endif
                            <button @click="expanded = !expanded" type="button" class="">
                                <x-lucide-chevron-down
                                    class="size-4 text-neutral-500 transition-transform duration-300"
                                    x-bind:class="expanded ? 'rotate-180' : ''" />
                            </button>
                        </div>

                        <div x-show="expanded" x-collapse>
                            <div class="flex flex-wrap gap-y-1.5 gap-x-2.5">
                                @foreach (App\Enums\HandleMaterial::cases() as $material)
                                    <label
                                        class="group flex items-center gap-x-1.5 cursor-pointer py-1 transition-all duration-300">
                                        <div class="relative flex items-center justify-center">
                                            <input type="checkbox" value="{{ $material->value }}"
                                                wire:model.live="handle_materials"
                                                class="peer appearance-none size-5.5 border border-neutral-300 rounded-sm checked:bg-neutral-900 checked:border-neutral-900 transition-all duration-300 cursor-pointer">

                                            <x-lucide-check
                                                class="absolute size-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"
                                                stroke-width="4" />
                                        </div>

                                        <span
                                            class="text-sm font-semibold capitalize transition-all duration-300 text-neutral-500 group-hover:text-neutral-900 group-has-checked:text-black tracking-tight">
                                            {{ $material->value }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- 4. ПРОФІЛЬ КЛИНКА -->
                    <div class="space-y-5" x-data="{ expanded: $persist(true).as('blade-expanded') }"
                        wire:loading.class="animate-pulse pointer-events-none" wire:target="blade_shapes" x-cloak>
                        <div class="flex items-center justify-between w-full group outline-none">
                            <div
                                class="flex items-center gap-1.5 text-sm font-extrabold uppercase text-neutral-600 font-[Oswald] tracking-wide">
                                <x-lucide-layers class="size-4" />
                                Профіль клинка
                            </div>
                            @if (count($blade_shapes))
                                <button wire:click="$set('blade_shapes', [])" type="button"
                                    class="flex items-center text-xs ms-auto me-1.5 text-neutral-400 hover:text-neutral-500 font-medium transition-colors duration-250 cursor-pointer">
                                    <x-lucide-x-circle
                                        class="size-4 border border-neutral-200 rounded-full flex-none me-0.5" />
                                    очистити
                                </button>
                            @endif
                            <button @click="expanded = !expanded" type="button" class="">
                                <x-lucide-chevron-down
                                    class="size-4 text-neutral-500 transition-transform duration-300"
                                    x-bind:class="expanded ? 'rotate-180' : ''" />
                            </button>
                        </div>

                        <div x-show="expanded" x-collapse>
                            <div class="flex flex-wrap gap-y-1.5 gap-x-2.5">
                                @foreach (App\Enums\BladeShape::cases() as $shape)
                                    <label
                                        class="group flex items-center gap-x-1.5 cursor-pointer py-1 transition-all duration-300">
                                        <div class="relative flex items-center justify-center">
                                            <input type="checkbox" value="{{ $shape->value }}"
                                                wire:model.live="blade_shapes"
                                                class="peer appearance-none size-5.5 border border-neutral-300 rounded-sm checked:bg-neutral-900 checked:border-neutral-900 transition-all duration-300 cursor-pointer">

                                            <x-lucide-check
                                                class="absolute size-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"
                                                stroke-width="4" />
                                        </div>

                                        <span
                                            class="text-sm font-semibold capitalize transition-all duration-300 text-neutral-500 group-hover:text-neutral-900 group-has-checked:text-black tracking-tight">
                                            {{ str_replace('_', ' ', $shape->value) }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. ОЧИСТИТИ ВСЕ -->
                <div class="shrink-0 py-2.5 lg:py-5">
                    <button wire:click="resetFilters"
                        class="group w-fit mx-auto h-full flex items-center justify-center gap-1.5 text-xs text-red-500 hover:text-red-500 uppercase font-semibold cursor-pointer">
                        <x-lucide-rotate-ccw class="size-3.5 transition duration-300 group-hover:-rotate-45" />
                        Очистити все
                    </button>
                </div>
            </div>
        </aside>

        <main class="flex-1 px-5 lg:px-0 lg:col-span-2 my-10">
            @includeWhen($this->collection === null, 'partials.product.collections', [
                'collections' => ProductCategory::cases(),
            ])

            <div class="flex justify-between 2.5 py-2.5 sticky top-16 z-40 bg-zinc-50">
                <!-- 🔍 Пошук + 🔽 Фільтр (Перший ряд на моб) -->
                <!-- Пошук -->
                <div class="relative flex-1 md:max-w-md">
                    <x-form.input wire:model.live.debounce.300ms="search" color="soft" icon="search"
                        type="text" placeholder="Пошук..." class="w-full" />

                    @if ($search)
                        <button wire:click="$set('search', '')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-stone-400 cursor-pointer">
                            <x-lucide-loader-circle wire:loading class="size-5 animate-spin" />
                            <x-lucide-circle-x wire:loading.remove class="size-5" />
                        </button>
                    @endif
                </div>

                <!-- DROPDOWN СОРТУВАННЯ -->
                <x-dropdown>
                    <x-slot:trigger>
                        @if ($sortBy === 'price' && $sortDirection === 'asc')
                            <x-lucide-trending-up class="size-4" />
                            <span>Дешевші</span>
                        @elseif ($sortBy === 'price' && $sortDirection === 'desc')
                            <x-lucide-trending-down class="size-4" />
                            <span>Дорожчі</span>
                        @elseif ($sortBy === 'created_at')
                            <x-lucide-sparkles class="size-4" />
                            <span>Новинки</span>
                        @else
                            <x-lucide-arrow-up-down class="size-4" />
                            <span>Сортувати</span>
                        @endif
                        <x-lucide-chevron-down class="size-3.5 transition-transform duration-300"
                            x-bind:class="open ? 'rotate-180' : ''" />
                    </x-slot:trigger>

                    <x-dropdown.content>
                        <!-- Дешевші спочатку -->
                        <x-dropdown.item x-data="{ loading: false }"
                            @click="loading=true; $wire.setSort('price','asc').then(() => {loading=false; open=false;})"
                            :active="$sortBy === 'price' && $sortDirection === 'asc'">
                            <x-lucide-trending-up class="size-4" />
                            <span>Дешевші спочатку</span>
                        </x-dropdown.item>

                        <!-- Дорожчі спочатку -->
                        <x-dropdown.item x-data="{ loading: false }"
                            @click="loading=true; $wire.setSort('price','desc').then(() => {loading=false; open=false;})"
                            :active="$sortBy === 'price' && $sortDirection === 'desc'" x-bind:disabled="loading">
                            <x-lucide-trending-down class="size-4" />
                            <span>Дорожчі спочатку</span>
                        </x-dropdown.item>

                        <!-- Новинки -->
                        <x-dropdown.item x-data="{ loading: false }"
                            @click="loading=true; $wire.setSort('created_at','desc').then(() => {loading=false; open=false;})"
                            :active="$sortBy === 'created_at' && $sortDirection === 'desc'" x-bind:disabled="loading">
                            <x-lucide-sparkles class="size-4" />
                            <span>Новинки</span>
                        </x-dropdown.item>
                    </x-dropdown.content>
                </x-dropdown>

                <!-- DROPDOWN ВІДОБРАЖЕННЯ -->
                <x-dropdown>
                    <x-slot:trigger>
                        @if ($view === 'grid')
                            <x-lucide-layout-grid class="size-4 me-1.5" />
                            <span>Сітка</span>
                        @elseif ($view === 'list')
                            <x-lucide-list class="size-4 me-1.5" />
                            <span>Список</span>
                        @else
                            <x-lucide-layout-template class="size-4 me-1.5" />
                            <span>Картки</span>
                        @endif
                        <x-lucide-chevron-down class="size-3.5 transition-transform duration-300"
                            x-bind:class="open ? 'rotate-180' : ''" />
                    </x-slot:trigger>

                    <x-dropdown.content>
                        <!-- Варіант: Сітка -->
                        <x-dropdown.item x-data="{ loading: false }"
                            @click="loading = true; $wire.setView('grid').then(() => { loading = false; open = false; })"
                            :active="$view === 'grid'" x-bind:disabled="loading">
                            <x-lucide-layout-grid class="size-4" />
                            <span class="font-medium">Сітка</span>
                        </x-dropdown.item>

                        <!-- Варіант: Список -->
                        <x-dropdown.item x-data="{ loading: false }"
                            @click="loading = true; $wire.setView('list').then(() => { loading = false; open = false; })"
                            :active="$view === 'list'" x-bind:disabled="loading">
                            <x-lucide-list class="size-4" />
                            <span class="font-medium">Список</span>
                        </x-dropdown.item>

                        <!-- Варіант: Картки -->
                        <x-dropdown.item x-data="{ loading: false }"
                            @click="loading = true; $wire.setView('cards').then(() => { loading = false; open = false; })"
                            :active="$view === 'cards'" x-bind:disabled="loading">
                            <x-lucide-layout-template class="size-4" />
                            <span class="font-medium">Картки</span>
                        </x-dropdown.item>
                    </x-dropdown.content>
                </x-dropdown>
            </div>

            @island('products-list', lazy: true, always: true)
                @placeholder
                    <div @class([
                        'grid gap-5 mt-5',
                        'grid-cols-2 lg:grid-cols-2' => $view === 'grid',
                        'lg:grid-cols-2' => $view === 'list' || $view === 'cards',
                    ])>
                        @foreach (range(1, 6) as $i)
                            @includeWhen($view === 'grid', 'partials.placeholders.product-list-grid')
                            @includeWhen($view === 'list', 'partials.placeholders.product-list-list')
                            @includeWhen($view === 'cards', 'partials.placeholders.product-list-cards')
                        @endforeach
                    </div>
                @endplaceholder

                <div @class([
                    'grid gap-5 transition-all duration-500 mt-5',
                    'grid-cols-2 lg:grid-cols-2' => $view === 'grid',
                    'lg:grid-cols-2' => $view === 'list' || $view === 'cards',
                ])>
                    @forelse($this->products as $product)
                        <x-product-card :$product :$view :collection="$this->collection" />

                        @includeWhen($loop->iteration % 10 == 0, 'partials.product.manufacture-section')
                    @empty
                        @include('partials.product.not-found')
                    @endforelse
                </div>
            @endisland

            {{-- Секція нескінченної прокрутки --}}
            @if ($this->products->hasMorePages())
                <div x-data x-intersect="$wire.loadMore()" class="mt-10 w-full">

                    {{-- Секція плейсхолдерів --}}
                    <div wire:loading.grid wire:target="loadMore" @class([
                        'w-full gap-5 transition-all duration-500',
                        'grid-cols-2 lg:grid-cols-2' => $view === 'grid',
                        'grid-cols-1 lg:grid-cols-2' => $view === 'list' || $view === 'cards',
                    ])>
                        @foreach (range(1, 4) as $i)
                            @includeWhen($view === 'grid', 'partials.placeholders.product-list-grid')
                            @includeWhen($view === 'list', 'partials.placeholders.product-list-list')
                            @includeWhen($view === 'cards', 'partials.placeholders.product-list-cards')
                        @endforeach
                    </div>

                    {{-- Текст завантаження --}}
                    <div wire:loading.remove wire:target="loadMore"
                        class="w-full flex justify-center py-10 text-zinc-400 text-sm italic">
                        Шукаємо ще ножі...
                    </div>
                </div>
            @endif
        </main>
    </div>
</div>
