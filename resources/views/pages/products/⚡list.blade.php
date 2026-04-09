<?php

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Collection;
use App\Enums\ProductCategory;
use App\Enums\CurrencyType;
use App\Enums\KnifeCollection;
use App\Models\AttributeValue;
use App\Models\Attribute;
use App\Models\Product;
use Livewire\Attributes\Url;
use Livewire\Attributes\Title;
use Livewire\Attributes\Session;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Component;

new class extends Component {
    use WithPagination;

    public ?string $collection = null;

    #[Url]
    public string $search = '';

    #[Url]
    public array $filters = [];

    #[Url]
    public array $currency = [];

    #[Url]
    public array $collections = [];

    #[Url]
    public string $status = 'all';

    #[Url]
    public int $price_from = 0;

    #[Url]
    public int $price_to = 0;

    #[Url(history: true)]
    public $blade_length_from = null;

    #[Url(history: true)]
    public $blade_length_to = null;

    #[Url(history: true)]
    public $blade_thickness_from = null;

    #[Url(history: true)]
    public $blade_thickness_to = null;

    public int $minLimit = 0;
    public int $maxLimit = 5000;

    public float $minBladeLen = 0;
    public float $maxBladeLen = 300;

    public float $minThickness = 0;
    public float $maxThickness = 10;

    #[Session]
    public string $sortBy = 'created_at';

    #[Session]
    public string $sortDirection = 'desc';

    #[Session]
    public string $view = 'grid';

    public int $perPage = 12;

    public function mount()
    {
        // Ціна - спочатку рахуємо мінімум і максимум
        $this->minLimit = (int) Product::where('category', ProductCategory::KNIFE)->min('price') ?: 0;
        $this->maxLimit = (int) Product::where('category', ProductCategory::KNIFE)->max('price') ?: 5000;

        if ($this->price_from === 0 || $this->price_from === null) {
            $this->price_from = $this->minLimit;
        }
        if ($this->price_to === 0 || $this->price_to === null || $this->price_to > $this->maxLimit) {
            $this->price_to = $this->maxLimit;
        }

        $this->minBladeLen = (float) Product::where('category', ProductCategory::KNIFE)->min('blade_length') ?: 0;
        $this->maxBladeLen = (float) Product::where('category', ProductCategory::KNIFE)->max('blade_length') ?: 300;

        $this->blade_length_from = $this->blade_length_from ?? $this->minBladeLen;
        $this->blade_length_to = $this->blade_length_to ?? $this->maxBladeLen;

        $this->minThickness = (float) Product::where('category', ProductCategory::KNIFE)->min('blade_thickness') ?: 0;
        $this->maxThickness = (float) Product::where('category', ProductCategory::KNIFE)->max('blade_thickness') ?: 10;

        $this->blade_thickness_from = $this->blade_thickness_from ?? $this->minThickness;
        $this->blade_thickness_to = $this->blade_thickness_to ?? $this->maxThickness;

        if (empty($this->currency)) {
            $this->currency = CurrencyType::values();
        }

        $this->view = Cookie::get('product_view', 'grid');
    }

    public function resetFilters()
    {
        $this->reset(['search', 'status', 'currency', 'collections', 'price_from', 'price_to', 'blade_length_from', 'blade_length_to', 'blade_thickness_from', 'blade_thickness_to', 'filters']);

        $this->price_from = $this->minLimit;
        $this->price_to = $this->maxLimit;

        $this->blade_length_from = $this->minBladeLen;
        $this->blade_length_to = $this->maxBladeLen;

        $this->blade_thickness_from = $this->minThickness;
        $this->blade_thickness_to = $this->maxThickness;

        if ($this->collection) {
            $this->collections = [$this->collection];
        }
    }

    public function resetPrice()
    {
        $this->price_from = $this->minLimit;
        $this->price_to = $this->maxLimit;
    }

    #[Computed]
    public function activeFilters(): array
    {
        $active = [];

        if ($this->search) {
            $active[] = [
                'type' => 'search',
                'label' => 'Пошук: ' . $this->search,
                'value' => null,
            ];
        }

        if ($this->status !== 'all') {
            $active[] = [
                'type' => 'status',
                'label' => $this->status === 'in_stock' ? 'В наявності' : 'Продані',
                'value' => null,
            ];
        }

        if ($this->price_from > $this->minLimit || $this->price_to < $this->maxLimit) {
            $active[] = [
                'type' => 'price',
                'label' => "Ціна: {$this->price_from}–{$this->price_to} грн",
                'value' => null,
            ];
        }

        if ($this->blade_length_from > $this->minBladeLen || $this->blade_length_to < $this->maxBladeLen) {
            $active[] = [
                'type' => 'blade_length',
                'label' => "Довжина: {$this->blade_length_from}–{$this->blade_length_to} мм",
                'value' => null,
            ];
        }

        if ($this->blade_thickness_from > $this->minThickness || $this->blade_thickness_to < $this->maxThickness) {
            $active[] = [
                'type' => 'blade_thickness',
                'label' => 'Товщина обуху: ' . round($this->blade_thickness_from, 1) . '–' . round($this->blade_thickness_to, 1) . ' мм',
                'value' => null,
            ];
        }

        foreach ($this->collections as $slug) {
            if (request()->routeIs('products.collection') && $slug === $this->collection) {
                continue;
            }
            if ($label = KnifeCollection::tryFrom($slug)?->getLabel()) {
                $active[] = [
                    'type' => 'collection',
                    'label' => $label,
                    'value' => $slug,
                ];
            }
        }

        if (!empty($this->filters)) {
            $attributes = Attribute::whereIn('slug', array_keys($this->filters))
                ->with('values')
                ->get()
                ->keyBy('slug');

            foreach ($this->filters as $slug => $valueIds) {
                if (empty($valueIds)) {
                    continue;
                }

                $attribute = $attributes->get($slug);
                if (!$attribute) {
                    continue;
                }

                foreach ($valueIds as $valueId) {
                    $value = $attribute->values->firstWhere('id', $valueId);
                    if (!$value) {
                        continue;
                    }

                    $active[] = [
                        'type' => 'attribute',
                        'label' => "{$attribute->name}: {$value->value}",
                        'slug' => $slug,
                        'valueId' => $valueId,
                    ];
                }
            }
        }

        return $active;
    }

    public function removeFilter(string $type, string $slug = '', $valueId = null)
    {
        match ($type) {
            'search' => ($this->search = ''),
            'status' => ($this->status = 'all'),
            'price' => $this->resetPriceRange(),
            'blade_length' => $this->resetBladeLength(),
            'blade_thickness' => $this->resetBladeThickness(),
            'collection' => ($this->collections = array_values(array_diff($this->collections, [$slug]))),
            'attribute' => $this->removeAttributeValue($slug, $valueId),
            default => null,
        };
    }

    private function resetPriceRange(): void
    {
        $this->price_from = $this->minLimit;
        $this->price_to = $this->maxLimit;
    }

    private function resetBladeLength(): void
    {
        $this->blade_length_from = $this->minBladeLen;
        $this->blade_length_to = $this->maxBladeLen;
    }

    private function resetBladeThickness(): void
    {
        $this->blade_thickness_from = $this->minThickness;
        $this->blade_thickness_to = $this->maxThickness;
    }

    private function removeAttributeValue(string $slug, $valueId): void
    {
        if (!isset($this->filters[$slug])) {
            return;
        }

        if ($valueId !== null && is_numeric($valueId)) {
            $this->filters[$slug] = array_diff($this->filters[$slug], [(int) $valueId]);

            if (empty($this->filters[$slug])) {
                unset($this->filters[$slug]);
            }
        } else {
            unset($this->filters[$slug]);
        }
    } // Livewire v3 — дуже рекомендується

    #[Computed]
    public function collectionCounts()
    {
        return Product::where('category', ProductCategory::KNIFE)->where('is_active', true)->select('collection')->selectRaw('COUNT(*) as total')->groupBy('collection')->pluck('total', 'collection')->all();
    }

    #[Computed]
    public function stockCounts()
    {
        $baseQuery = Product::query()->where('category', ProductCategory::KNIFE)->where('is_active', true)->when($this->collection, fn($q) => $q->where('collection', $this->collection));

        return [
            'available' => (clone $baseQuery)->where('quantity', '>', 0)->count(),
            'sold' => (clone $baseQuery)->where('quantity', '=', 0)->count(),
        ];
    }

    #[Computed]
    public function currentCollectionLabel()
    {
        return KnifeCollection::tryFrom((string) $this->collection)?->getLabel();
    }

    #[Computed]
    public function products()
    {
        return Product::query()
            ->where('category', ProductCategory::KNIFE)
            ->where('is_active', true)
            ->when($this->collection, fn($q) => $q->where('collection', $this->collection))
            ->when($this->search, function ($q, $search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when(!empty($this->collections), fn($q) => $q->whereIn('collection', $this->collections))
            ->when($this->status === 'in_stock', fn($q) => $q->where('quantity', '>', 0))
            ->when($this->status === 'sold', fn($q) => $q->where('quantity', '=', 0))
            ->when($this->price_from > 0, fn($q) => $q->where('price', '>=', $this->price_from))
            ->when($this->price_to > 0, fn($q) => $q->where('price', '<=', $this->price_to))
            ->when($this->blade_length_from !== null, fn($q) => $q->where('blade_length', '>=', (float) $this->blade_length_from))
            ->when($this->blade_length_to !== null, fn($q) => $q->where('blade_length', '<=', (float) $this->blade_length_to))
            ->when($this->blade_thickness_from !== null, fn($q) => $q->where('blade_thickness', '>=', (float) $this->blade_thickness_from))
            ->when($this->blade_thickness_to !== null, fn($q) => $q->where('blade_thickness', '<=', (float) $this->blade_thickness_to))
            ->when(!empty($this->filters), function ($q) {
                foreach ($this->filters as $slug => $values) {
                    if (empty($values)) {
                        continue;
                    }
                    $q->whereHas('attributeValues', function ($pivot) use ($slug, $values) {
                        $pivot->whereIn('attribute_value_id', $values)->whereHas('attribute', fn($attr) => $attr->where('slug', $slug));
                    });
                }
            })
            ->with(['media'])
            ->withCount(['likes', 'comments'])
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    #[Computed]
    public function allAttributes(): Collection
    {
        return Attribute::with('values')
            ->where('group', ProductCategory::KNIFE->value ?? 'knife')
            ->orderBy('sort')
            ->get();
    }

    public function toggleFilter(string $slug, int $valueId)
    {
        if (!isset($this->filters[$slug])) {
            $this->filters[$slug] = [];
        }

        if (in_array($valueId, $this->filters[$slug])) {
            $this->filters[$slug] = array_diff($this->filters[$slug], [$valueId]);
        } else {
            $this->filters[$slug][] = $valueId;
        }

        if (empty($this->filters[$slug])) {
            unset($this->filters[$slug]);
        }
    }

    public function updatedView($value)
    {
        Cookie::queue('product_view', $value, 43200);
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

    public function loadMore()
    {
        $this->perPage += 4;
    }
};
?>

<x-slot name="title">
    Каталог ножів ручної роботи — купити авторський ніж
</x-slot>
<x-slot name="description">
    Каталог ножів ручної роботи: мисливські, кухонні та універсальні ножі. Висока якість матеріалів, ручне виготовлення
    та доставка по Україні.
</x-slot>

@section('header')
    <x-header :image="Vite::asset(
        'resources/images/' . (KnifeCollection::tryFrom((string) $this->collection)?->images() ?? 'header.png'),
    )">
        <x-slot:title>
            {{ KnifeCollection::tryFrom((string) $this->collection)?->getLabel() ?? 'Каталог ножів' }}
        </x-slot:title>

        <x-slot:description>
            {{ KnifeCollection::tryFrom((string) $this->collection)?->description() ?? 'Оберіть ніж зі складу або замовте виготовлення.' }}
        </x-slot:description>

        @if ($this->stockCounts['available'] || $this->stockCounts['sold'])
            <div class="flex gap-5 mt-2.5 -mb-5">
                @if ($this->stockCounts['available'])
                    <div class="flex items-center gap-1.5">
                        <x-lucide-package-check class="size-6 text-orange-500" stroke-width="1.5" />
                        <div class="flex flex-col">
                            <span class="text-zinc-50 leading-none">
                                <span class="text-sm font-bold">{{ $this->stockCounts['available'] }}</span>
                            </span>
                            <span class="text-xs uppercase tracking-wide text-zinc-300 font-medium">Наявні</span>
                        </div>
                    </div>
                @endif
                @if ($this->stockCounts['sold'])
                    <div class="flex items-center gap-1.5">
                        <x-lucide-hammer class="size-6 text-orange-500" stroke-width="1.5" />
                        <div class="flex flex-col">
                            <span class="text-zinc-50 leading-none">
                                <span class="text-sm font-bold">{{ $this->stockCounts['sold'] }}</span>
                            </span>
                            <span class="text-xs uppercase tracking-wide text-zinc-300 font-medium">Замовні</span>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </x-header>
@endsection

<section class="lg:min-h-screen bg-neutral-50">
    <div x-data="{ mobileFiltersOpen: false }" class="max-w-5xl xl:max-w-6xl mx-auto lg:grid lg:grid-cols-3 lg:gap-10">
        <aside
            class="hidden lg:block shrink-0 sticky top-16 lg:top-14 z-40 lg:h-screen w-full border-b lg:border-b-0 lg:border-r border-zinc-200 bg-linear-to-b lg:bg-linear-to-r from-zinc-50 lg:from-transparent to-zinc-100">
            @include('partials.product.filters')
        </aside>

        <main class="flex-1 lg:col-span-2 flex flex-col gap-5 lg:pt-8 pb-10">
            @includeWhen($this->collection === null, 'partials.product.collections', [
                'collections' => KnifeCollection::cases(),
            ])

            <!-- Кнопка відкриття фільтрів на мобілці -->
            <div
                class="sticky top-16 z-40 px-5 py-2.5 lg:px-0 bg-zinc-100 lg:bg-zinc-50 border-b lg:border-0 border-zinc-200 flex flex-col gap-0.5">
                <div class="flex justify-between gap-x-0.5 lg:gap-x-2.5">
                    @php
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

                        @include('partials.product.filters')

                        <x-slot:footer>
                            <button wire:click="resetFilters"
                                class="group w-fit mx-auto h-full flex items-center justify-center gap-1.5 text-xs text-red-500 hover:text-red-500 uppercase font-semibold cursor-pointer">
                                <x-lucide-rotate-ccw class="size-3.5 transition duration-300 group-hover:-rotate-45" />
                                Очистити все
                            </button>
                        </x-slot:footer>
                    </x-drawer>
                </div>

                @if (!empty($this->activeFilters))
                    <div class="flex flex-wrap items-center gap-1.5 mt-2.5">
                        @foreach ($this->activeFilters as $filter)
                            <div class="group flex items-center gap-0.5 bg-zinc-100 px-1.5 py-0.5 rounded">
                                <span class="text-xs font-semibold text-zinc-700">{{ $filter['label'] }}</span>

                                <button
                                    wire:click="removeFilter('{{ $filter['type'] }}', '{{ $filter['slug'] ?? '' }}', {{ $filter['valueId'] ?? 'null' }})"
                                    class="ml-0.5 text-zinc-400 hover:text-red-500 p-0.5 rounded-full hover:bg-red-50 transition">
                                    <x-lucide-x class="size-3.5" />
                                </button>
                            </div>
                        @endforeach

                        <button wire:click="resetFilters"
                            class="ml-2 text-xs text-red-500 hover:text-red-600 font-medium flex items-center gap-0.5 cursor-pointer">
                            <x-lucide-rotate-ccw class="size-3.5" />
                            Очистити все
                        </button>
                    </div>
                @endif

                {{-- @if ($displayFilters->isNotEmpty())
                    <div class="w-full flex flex-wrap items-center gap-1.5 mt-1.5">
                        @foreach ($displayFilters as $filter)
                            <div
                                class="flex items-center gap-0.5 text-xs font-semibold text-zinc-700 bg-zinc-100 px-2 py-0.5 rounded">
                                {{ $filter }}
                            </div>
                        @endforeach

                        <button wire:click="resetFilters" wire:loading.attr="disabled"
                            class="bg-orange-500 size-4 flex justify-center items-center rounded-full hover:bg-orange-700 transition ml-1.5 cursor-pointer disabled:opacity-50">
                            <x-lucide-x wire:loading.remove wire:target="resetFilters" class="size-3 stroke-white" />
                            <x-lucide-loader-circle wire:loading wire:target="resetFilters"
                                class="size-3 stroke-white animate-spin" />
                        </button>
                    </div>
                @endif --}}
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
</section>

@vite('resources/js/pages/product-list.js')
