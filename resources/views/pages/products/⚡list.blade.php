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

        <main class="flex-1 lg:col-span-2 py-8">
            @includeWhen($this->collection === null, 'partials.product.collections', [
                'collections' => ProductCategory::cases(),
            ])

            <!-- Кнопка відкриття фільтрів на мобілці -->
            <div class="sticky top-16 z-40 px-5 py-2.5 bg-zinc-100 border-b border-zinc-200 flex gap-0.5">
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

            @island('products-list', lazy: true, always: true)
                @placeholder
                    <div @class([
                        'grid px-5 lg:px-0 py-5',
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
                    'grid transition-all duration-500 px-5 lg:px-0 py-5',
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
                <div x-data x-intersect="$wire.loadMore()" class="w-full px-5 lg:px-0 lg:pb-10">

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
                        class="w-full flex justify-center ph-5 lg:py-10 text-zinc-400 text-sm italic">
                        Шукаємо ще ножі...
                    </div>
                </div>
            @endif
        </main>
    </div>
</section>
