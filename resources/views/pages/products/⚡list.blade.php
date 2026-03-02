<?php

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Cookie;
use Livewire\Attributes\Url;
use Livewire\Attributes\Session;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use App\Models\Product;

new class extends Component {
    use WithPagination;

    public ?string $category = null;

    public $filters = [];

    #[On('apply-filters')]
    public function handleFilters($filters)
    {
        $this->filters = $filters;
    }

    #[Url(history: true)]
    public $search = '';

    #[Url, Session]
    public $sortBy = 'created_at';

    #[Url, Session]
    public $sortDirection = 'desc';

    public $perPage = 12;
    public $view = 'grid';

    public function mount()
    {
        $this->view = Cookie::get('product_view', 'grid');
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
            ->filter($this->filters)
            ->withCount(['likes', 'comments'])
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    #[Computed]
    public function categoryCounts()
    {
        return Product::query()
            //
            ->select('category')
            ->selectRaw('count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();
    }

    // #[Computed]
    // public function categoryCounts()
    // {
    //     return Product::query()
    //         // Фільтруємо за сталями, ціною тощо, щоб цифри були актуальними
    //         ->filter(collect($this->filters)->except('categories')->toArray())
    //         ->select('category')
    //         ->selectRaw('count(*) as total')
    //         ->groupBy('category')
    //         ->pluck('total', 'category');
    // }
};
?>

@section('header')
    <x-header :image="Vite::asset(
        'resources/images/' . (\App\Enums\ProductCategory::tryFrom($category)?->images() ?? 'header.png'),
    )">
        <x-slot:title>
            {{ App\Enums\ProductCategory::tryFrom($category)?->getLabel() ?? 'Каталог товарів' }}
        </x-slot:title>
        <x-slot:description>
            Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quas, tenetur animi voluptas
            veniam repellat eius.
        </x-slot:description>
    </x-header>
@endsection

<div class="lg:min-h-screen bg-neutral-50">
    <div class="max-w-6xl lg:grid lg:grid-cols-3 gap-10 mx-auto">
        <!-- Сайдбар з фільтрами -->
        <aside class="hidde lg:block w-full border-r border-zinc-200 bg-linear-to-r from-transparent to-zinc-100">
            <!-- Контейнер для "липкого" ефекту -->
            <div class="hidden sticky top-16 h-[calc(100vh-4rem)] overflow-y-auto lg:flex flex-col pt-8 pr-8">
                <livewire:product-filters :category="$this->category" />
            </div>
        </aside>

        <main class="flex-1 px-4 lg:px-0 lg:col-span-2 mt-10">
            @if (!$this->category)
                <div class="hidden lg:grid lg:grid-cols-2 gap-2.5 mb-5">
                    @foreach (App\Enums\ProductCategory::cases() as $category)
                        @php
                            $count = $this->categoryCounts[$category->value] ?? 0;
                        @endphp
                        <a href="{{ $category->url() }}"
                            class="first:col-span-full flex-none relative block overflow-hidden aspect-video group transition-all duration-700"
                            wire:navigate>

                            <!-- Зображення -->
                            <img src="{{ Vite::asset('resources/images/' . $category->images()) }}"
                                alt="{{ $category->getLabel() }}"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">

                            <!-- Градієнтне затемнення -->
                            <div
                                class="absolute inset-0 bg-linear-to-t from-15% from-black/90 via-black/30 to-transparent opacity-60 transition-opacity duration-500 group-hover:opacity-80">
                            </div>

                            <div class="absolute top-8 left-8">
                                <span class="text-2xl font-[Oswald] text-neutral-100/70 font-black">
                                    {{ $count }}
                                    <span class="text-base">
                                        {{ trans_choice('товар|товари|товарів', $count, [], 'uk') }}
                                    </span>
                                </span>
                            </div>

                            <!-- Контент -->
                            <div class="absolute inset-0 flex flex-col justify-end p-8">
                                <div class="flex flex-col gap-1.5">

                                    <!-- Заголовок -->
                                    <h3
                                        class="text-white text-xl md:text-2xl font-black uppercase tracking-wide leading-tight font-[Oswald]">
                                        {{ $category->getLabel() }}
                                    </h3>

                                    <!-- Опис (спочатку невидимий) -->
                                    <p
                                        class="text-white/70 text-xs md:text-sm font-medium leading-relaxed line-clamp-2 opacity-0 max-h-0 overflow-hidden transition-all duration-500 group-hover:opacity-100 group-hover:max-h-20">
                                        {{ $category->description() }}
                                    </p>

                                    <!-- Кнопка "Переглянути" -->
                                    <div class="">
                                        <span
                                            class="inline-flex items-center gap-2.5 text-xs font-bold uppercase text-orange-500 group-hover:text-amber-400 transition-colors">
                                            Переглянути
                                            <x-lucide-arrow-right
                                                class="size-3 transition-transform duration-300 group-hover:translate-x-1" />
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            <div class="flex justify-between 2.5 py-2.5 sticky top-16 z-40 bg-zinc-50">
                <!-- 🔍 Пошук + 🔽 Фільтр (Перший ряд на моб) -->
                <!-- Пошук -->
                <div class="relative flex-1 md:max-w-md">
                    <x-form.input wire:model.live.debounce.300ms="search" color="soft" icon="search" type="text"
                        placeholder="Пошук..." class="w-full" />

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

            <div @class([
                'grid gap-4 lg:gap-7.5 transition-all duration-500 mt-5',
                'grid-cols-2 lg:grid-cols-2' => $view === 'grid',
                'lg:grid-cols-2' => $view === 'list' || $view === 'cards',
            ])>
                @forelse($this->products as $product)
                    <x-product-card :$product :$view :category="$this->category" />

                    @if ($loop->iteration % 10 == 0)
                        <x-product.list.offer :image="Vite::asset('resources/images/products-list-order-bg.png')">
                            <x-slot:title>Виготовлення ножів <br> на замовлення</x-slot:title>
                            <x-slot:caption>
                                Окрім готових моделей, також створюю індивідуальні ножі —
                                з урахуванням ваших вимог, матеріалів та дизайну.
                            </x-slot:caption>

                            <x-button color="light" size="lg">
                                <x-lucide-hammer class="size-4.5 me-1.5" />
                                Замовити виготовлення
                            </x-button>
                        </x-product.list.offer>
                    @endif
                @empty
                    <x-product.list.not-found>
                        <x-lucide-package-search class="size-12 stroke-zinc-300" />
                        <p>Товарів не знайдено...</p>
                    </x-product.list.not-found>
                @endforelse
            </div>

            {{-- Секція нескінченної прокрутки --}}
            @if ($this->products->hasMorePages())
                <div x-data x-intersect="$wire.loadMore()" class="mt-10 py-10 flex justify-center">

                    {{-- Відображається під час завантаження (твій placeholder) --}}
                    <div wire:loading wire:target="loadMore"
                        class="grid gap-5 w-full {{ $view === 'grid' ? 'grid-cols-2 lg:grid-cols-3' : 'grid-cols-1' }}">
                        @foreach (range(1, 3) as $i)
                            <div class="animate-pulse bg-zinc-100 rounded-2xl h-64 w-full"></div>
                        @endforeach
                    </div>

                    {{-- Текст або іконка, яку видно мить до початку завантаження --}}
                    <div wire:loading.remove wire:target="loadMore" class="text-zinc-400 text-sm">
                        Завантаження ще товарів...
                    </div>
                </div>
            @endif
        </main>
    </div>
</div>
