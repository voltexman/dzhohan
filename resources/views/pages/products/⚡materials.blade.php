<?php

use App\Enums\KnifeCollection;
use Illuminate\Support\Collection;
use App\Enums\ProductCategory;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Session;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\Attribute;
use App\Models\Product;
use Livewire\Component;

new class extends Component {
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Session]
    public $sortBy = 'created_at';

    #[Session]
    public $sortDirection = 'desc';

    #[Session]
    public $view = 'grid';

    public $perPage = 12;

    #[Url]
    public int $price_from = 0;
    #[Url]
    public int $price_to = 0;

    public int $minLimit, $maxLimit;

    public $filters = [];

    public function mount()
    {
        $this->minLimit = (int) Product::where('category', ProductCategory::MATERIAL)->min('price') ?: 0;
        $this->maxLimit = (int) Product::where('category', ProductCategory::MATERIAL)->max('price') ?: 5000;
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

        $this->price_from = $this->minLimit;
        $this->price_to = $this->maxLimit;
    }

    #[Computed]
    public function activeFilters(): array
    {
        $filters = [];

        if ($this->search) {
            $filters[] = 'Пошук: ' . $this->search;
        }

        if ($this->price_from > $this->minLimit || $this->price_to < $this->maxLimit) {
            $filters[] = "Ціна: {$this->price_from}-{$this->price_to}";
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
    public function stockCounts()
    {
        $baseQuery = Product::query()->where('is_active', true)->when($this->collection, fn($q) => $q->where('collection', $this->collection));

        return [
            'available' => (clone $baseQuery)->where('quantity', '>', 0)->count(),
            'sold' => (clone $baseQuery)->where('quantity', 0)->count(),
        ];
    }

    #[Computed]
    public function currentCollectionLabel()
    {
        return KnifeCollection::tryFrom((string) $this->collection)?->getLabel();
    }

    public function toggleFilter(string $slug, int $valueId)
    {
        if (!isset($this->filters[$slug])) {
            $this->filters[$slug] = [];
        }

        if (in_array($valueId, $this->filters[$slug])) {
            // прибираємо
            $this->filters[$slug] = array_diff($this->filters[$slug], [$valueId]);
        } else {
            // додаємо
            $this->filters[$slug][] = $valueId;
        }

        // чистимо порожні масиви
        if (empty($this->filters[$slug])) {
            unset($this->filters[$slug]);
        }
    }

    #[Computed]
    public function allAttributes(): Collection
    {
        return Attribute::with('values')->where('group', 'material')->orderBy('sort')->get();
    }

    #[Computed]
    public function products()
    {
        return Product::query()
            ->where('category', ProductCategory::MATERIAL)
            ->with(['media'])
            ->withCount(['likes', 'comments'])
            ->filter([
                'search' => $this->search,
            ])
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }
};
?>

@section('header')
    <x-header :image="Vite::asset('resources/images/header.png')">
        <x-slot:title>Майстерня ножовика</x-slot:title>

        <x-slot:description>
            Якісні матеріали для професійного виготовлення та ремонту. Знайдіть усе необхідне для втілення власних ідей.
        </x-slot:description>
    </x-header>
@endsection

<section class="lg:min-h-screen bg-neutral-50">
    <div x-data="{ mobileFiltersOpen: false }" class="max-w-5xl xl:max-w-6xl mx-auto lg:grid lg:grid-cols-3 lg:gap-10">
        <aside
            class="hidden lg:block shrink-0 sticky top-16 lg:top-14 z-40 lg:h-screen w-full border-b lg:border-b-0 lg:border-r border-zinc-200 bg-linear-to-b lg:bg-linear-to-r from-zinc-50 lg:from-transparent to-zinc-100">
            @include('partials.product.materials-filters')
        </aside>

        <main class="flex-1 lg:col-span-2 flex flex-col gap-5 lg:pt-8 pb-10">
            <!-- Кнопка відкриття фільтрів на мобілці -->
            <div
                class="sticky top-16 z-40 px-5 py-2.5 lg:px-0 bg-zinc-100 lg:bg-zinc-50 border-b lg:border-0 border-zinc-200 flex flex-col gap-0.5">
                <div class="flex justify-between gap-x-0.5 lg:gap-x-2.5">
                    @php
                        // Створюємо відфільтровану колекцію один раз для всього блоку
                        $displayFilters = collect($this->activeFilters)->filter(
                            fn($f) => $f !== $this->currentCollectionLabel(),
                        );
                    @endphp

                    <x-form.input size="sm" wire:model.trim.live.debounce.300ms="search" placeholder="Пошук ножів"
                        class="lg:py-3.5!" />

                    @include('partials.product.list.sorting')
                    @include('partials.product.list.viewing')

                    <x-drawer>
                        <x-slot:trigger>
                            <x-button variant="ghost" color="dark" size="sm" icon>
                                <x-lucide-filter class="size-5 stroke-zinc-800" />
                            </x-button>
                        </x-slot:trigger>
                        <x-slot:header class="flex justify-between">
                            <div class="flex flex-col me-auto">
                                <div>Фільтри</div>
                                @if ($displayFilters->isNotEmpty())
                                    <div class="text-xs font-normal">
                                        Знайдено: <span class="text-orange-500">{{ $this->products->count() }}</span>
                                        {{ trans_choice('товар|товари|товарів', $this->products->count(), [], 'uk') }}
                                    </div>
                                @endif
                            </div>
                            <x-button @click="open = false" color="dark" size="xs" class="ms-auto">
                                Показати
                            </x-button>
                        </x-slot:header>

                        @include('partials.product.materials-filters')

                        <x-slot:footer>
                            <button wire:click="resetFilters"
                                class="group w-fit mx-auto h-full flex items-center justify-center gap-1.5 text-xs text-red-500 hover:text-red-500 uppercase font-semibold cursor-pointer">
                                <x-lucide-rotate-ccw class="size-3.5 transition duration-300 group-hover:-rotate-45" />
                                Очистити все
                            </button>
                        </x-slot:footer>
                    </x-drawer>
                </div>

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
                        <x-product-card :$product :$view wire:key="product-{{ $product->id }}" />

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
</section>
