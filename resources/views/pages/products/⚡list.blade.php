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

    #[Url, Session]
    public string $search = '';

    #[Url, Session]
    public array $collections = [];

    #[Url, Session]
    public array $steels = [];

    #[Url, Session]
    public array $blade_shapes = [];

    #[Url, Session]
    public array $handle_materials = [];

    #[Url, Session]
    public array $blade_grinds = [];

    #[Url, Session]
    public string $status = 'all';

    #[Url, Session]
    public int $price_from = 0;

    #[Url, Session]
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
        $this->search = '';

        $this->status = 'all';
        $this->collections = [];
        $this->steels = [];
        $this->handle_materials = [];
        $this->blade_shapes = [];
        $this->blade_grinds = [];

        $this->price_from = $this->minLimit;
        $this->price_to = $this->maxLimit;

        if ($this->collection) {
            $this->collections = [$this->collection];
        }
    }

    #[Computed]
    public function activeFilters(): array
    {
        $filters = [];

        if ($this->search) {
            $filters[] = 'Пошук: ' . $this->search;
        }

        if ($this->status !== 'all') {
            $filters[] = $this->status === 'in_stock' ? 'В наявності' : 'Продані';
        }

        if ($this->price_from > $this->minLimit || $this->price_to < $this->maxLimit) {
            $filters[] = "Ціна: {$this->price_from}-{$this->price_to} грн";
        }

        foreach ($this->collections as $slug) {
            if (request()->routeIs('products.collection') && $slug === $this->collection) {
                continue;
            }
            $filters[] = ProductCategory::tryFrom($slug)?->getLabel();
        }

        foreach ($this->steels as $steelSlug) {
            $filters[] = \App\Enums\SteelType::tryFrom($steelSlug)?->getLabel() ?? $steelSlug;
        }

        foreach ($this->blade_shapes as $shapeSlug) {
            $filters[] = \App\Enums\BladeShape::tryFrom($shapeSlug)?->getLabel() ?? $shapeSlug;
        }

        foreach ($this->handle_materials as $materialSlug) {
            $filters[] = \App\Enums\HandleMaterial::tryFrom($materialSlug)?->getLabel() ?? $materialSlug;
        }

        foreach ($this->blade_grinds as $grindSlug) {
            $filters[] = \App\Enums\BladeGrind::tryFrom($grindSlug)?->getLabel() ?? $grindSlug;
        }

        return array_filter($filters);
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
    public function currentCollectionLabel()
    {
        return ProductCategory::tryFrom((string) $this->collection)?->getLabel();
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
        return Product::query()->where('is_active', true)->select('collection')->selectRaw('count(*) as total')->groupBy('collection')->pluck('total', 'collection')->toArray();
    }
};
?>

@section('header')
    <x-header :image="Vite::asset(
        'resources/images/' . (ProductCategory::tryFrom((string) $this->collection)?->images() ?? 'header.png'),
    )">
        <x-slot:title>
            {{ ProductCategory::tryFrom((string) $this->collection)?->getLabel() ?? 'Каталог товарів' }}
        </x-slot:title>

        <x-slot:description>
            {{ ProductCategory::tryFrom((string) $this->collection)?->description() ?? 'Оберіть ніж зі складу або замовте виготовлення.' }}
        </x-slot:description>
    </x-header>
@endsection

<section class="lg:min-h-screen bg-neutral-50">
    <div x-data="{ mobileFiltersOpen: false }" class="max-w-5xl xl:max-w-6xl mx-auto lg:grid lg:grid-cols-3 lg:gap-10">
        <aside
            class="hidden lg:block shrink-0 sticky top-16 lg:top-14 z-40 lg:h-screen w-full border-b lg:border-b-0 lg:border-r border-zinc-200 bg-linear-to-b lg:bg-linear-to-r from-zinc-50 lg:from-transparent to-zinc-100">
            @include('partials.product.filters')
        </aside>

        <main class="flex-1 lg:col-span-2 flex flex-col gap-5 py-10">
            @includeWhen($this->collection === null, 'partials.product.collections', [
                'collections' => ProductCategory::cases(),
            ])

            <!-- Кнопка відкриття фільтрів на мобілці -->
            <div
                class="sticky top-16 z-40 px-5 py-2.5 lg:px-0 bg-zinc-100 lg:bg-zinc-50 border-b lg:border-0 border-zinc-200 flex flex-col gap-0.5">
                <div class="flex justify-between lg:gap-x-1.5">
                    <x-form.input size="sm" wire:model.trim.live.debounce.300ms="search" placeholder="Пошук ножів" />

                    @include('partials.product.list.sorting')
                    @include('partials.product.list.viewing')

                    <x-drawer class="">
                        <x-slot:trigger>
                            <x-button variant="ghost" color="dark" size="sm" icon>
                                <x-lucide-filter class="size-5 stroke-zinc-800" />
                            </x-button>
                        </x-slot:trigger>
                        <x-slot:header>Фільтри</x-slot:header>

                        @include('partials.product.filters')
                    </x-drawer>
                </div>

                @php
                    // Створюємо відфільтровану колекцію один раз для всього блоку
                    $displayFilters = collect($this->activeFilters)->filter(
                        fn($f) => $f !== $this->currentCollectionLabel(),
                    );
                @endphp

                @if ($displayFilters->isNotEmpty())
                    <div class="w-full flex flex-wrap items-center gap-1.5 mt-1.5">
                        @foreach ($displayFilters as $filter)
                            <div
                                class="flex items-center gap-0.5 text-xs font-semibold text-zinc-700 bg-zinc-100 px-2 py-0.5 rounded">
                                {{ $filter }}
                            </div>
                        @endforeach

                        {{-- Тепер кнопка з'явиться тільки якщо є що скидати (крім самої колекції) --}}
                        <button wire:click="resetFilters" wire:loading.attr="disabled"
                            class="bg-orange-500 size-4 flex justify-center items-center rounded-full hover:bg-orange-700 transition ml-1.5 cursor-pointer disabled:opacity-50">
                            <x-lucide-x wire:loading.remove wire:target="resetFilters" class="size-3 stroke-white" />
                            <x-lucide-loader-circle wire:loading wire:target="resetFilters"
                                class="size-3 stroke-white animate-spin" />
                        </button>
                    </div>
                @endif

            </div>

            @island('products-list', lazy: true, always: true)
                @placeholder
                    <div @class([
                        'grid px-5 lg:px-0',
                        'gap-2.5 lg:gap-5 grid-cols-2 lg:grid-cols-2' => $view === 'grid',
                        'gap-2.5 lg:gap-5 lg:grid-cols-2' => $view === 'list',
                        'gap-5 lg:grid-cols-2' => $view === 'cards',
                    ])>
                        @foreach (range(1, 6) as $i)
                            @includeWhen($view === 'grid', 'partials.placeholders.product-list-grid')
                            @includeWhen($view === 'list', 'partials.placeholders.product-list-list')
                            @includeWhen($view === 'cards', 'partials.placeholders.product-list-cards')
                        @endforeach
                    </div>
                @endplaceholder

                <div @class([
                    'grid transition-all duration-500 px-5 lg:px-0',
                    'gap-2.5 lg:gap-5 grid-cols-2 lg:grid-cols-2' => $view === 'grid',
                    'gap-2.5 lg:gap-5 lg:grid-cols-2' => $view === 'list',
                    'gap-5 lg:grid-cols-2' => $view === 'cards',
                ])>
                    @forelse($this->products as $product)
                        <x-product-card :$product :$view :collection="$this->collection" wire:key="product-{{ $product->id }}" />

                        @includeWhen($loop->iteration % 10 == 0, 'partials.product.manufacture-section')
                    @empty
                        @include('partials.product.not-found')
                    @endforelse
                </div>
            @endisland

            {{-- Секція нескінченної прокрутки --}}
            @if ($this->products->hasMorePages())
                <div x-data x-intersect="$wire.loadMore()" class="w-full px-5 lg:px-0">

                    {{-- Секція плейсхолдерів --}}
                    <div wire:loading.grid wire:target="loadMore" @class([
                        'grid transition-all duration-500',
                        'gap-2.5 lg:gap-5 grid-cols-2 lg:grid-cols-2' => $view === 'grid',
                        'gap-2.5 lg:gap-5 lg:grid-cols-2' => $view === 'list',
                        'gap-5 lg:grid-cols-2' => $view === 'cards',
                    ])>
                        @foreach (range(1, 4) as $i)
                            @includeWhen($view === 'grid', 'partials.placeholders.product-list-grid')
                            @includeWhen($view === 'list', 'partials.placeholders.product-list-list')
                            @includeWhen($view === 'cards', 'partials.placeholders.product-list-cards')
                        @endforeach
                    </div>

                    {{-- Текст завантаження --}}
                    <div wire:loading.remove wire:target="loadMore"
                        class="w-full flex justify-center py5 lg:py10 text-zinc-400 text-sm italic">
                        Шукаємо ще ножі...
                    </div>
                </div>
            @endif
        </main>
    </div>
    {{-- <div class="h-screen px-5 lg:px-0 py-20">
        <div class="max-w-md mx-auto flex flex-col gap-10 text-center">
            <!-- Іконка та заклик -->
            <div>
                <x-lucide-x-octagon class="size-12 mx-auto text-zinc-300 mb-4" />
                <h3 class="font-[Oswald] text-xl uppercase font-bold text-zinc-800">Товари відсутні</h3>
                <div class="max-w-sm text-sm text-zinc-500 text-balance mx-auto text-center mt-2.5">
                    Наразі ножів у наявності немає. Я працюю над новими виробами — загляньте трохи пізніше.
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
            <a href="{{ route('order') }}" wire:navigate
                class="inline-flex justify-center items-center px-10 py-3.5 w-fit mx-auto rounded-md bg-zinc-900 text-white text-xs font-bold uppercase tracking-widest hover:bg-orange-600 transition-colors duration-300">
                Перейти до замовлень
            </a>
        </div>
    </div> --}}
</section>
