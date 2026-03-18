<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Product;

new #[Layout('layouts::cart')] class extends Component {
    public Product $product;

    public bool $isLiked = false;

    public $replyTo = null;

    public function mount(Product $product): void
    {
        $this->product = $product;

        $this->isLiked = $product->isLiked();
    }

    public function like()
    {
        $this->product->isLiked() ? $this->product->unlike() : $this->product->like();
    }
};
?>

@section('images')
    <div class="fixed lg:sticky top-0 left-0 w-full h-[70vh] lg:h-screen z-0 overflow-hidden bg-zinc-100" wire:ignore>
        <div class="embla relative h-full w-full">
            <div class="embla__viewport overflow-hidden h-full">
                <div class="embla__container mx-0! flex h-full w-full">
                    @foreach ($product->getMedia('products') as $media)
                        @php
                            // Отримуємо реальні розміри зображення для PhotoSwipe
                            [$width, $height] = getimagesize($media->getPath());
                        @endphp
                        <a class="block embla__slide min-w-0 relative flex-[0_0_100%]! h-full pointer-eventsnone cursor-pointer"
                            wire:key="main-{{ $media->id }}" data-pswp-src="{{ $media->getFullUrl() }}"
                            data-pswp-width="{{ $width }}" data-pswp-height="{{ $height }}">
                            <img src="{{ $media->getFullUrl() }}" alt="{{ $product->name }}"
                                class="absolute inset-0 w-full h-full object-cover">
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="embla-thumbs absolute bottom-8 left-1/2 -translate-x-1/2 w-full max-w-lg px-4">
                <div class="embla-thumbs__viewport overflow-hidden">
                    <div class="embla-thumbs__container flex gap-2.5 justify-center p-5">
                        @foreach ($product->getMedia('products') as $media)
                            <div class="embla-thumbs__slide shrink-0 size-20 lg:size-24 cursor-pointer overflow-hidden rounded-lg border-2 border-zinc-100/15 transition-all duration-300 shadow-lg shadow-zinc-50/5 hover:shadow-zinc-50/10"
                                wire:key="thumb-{{ $media->id }}">
                                <img src="{{ $media->getFullUrl() }}" alt=""
                                    class="size-full object-cover opacity-70 hover:opacity-100 transition-opacity">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<section x-data="{ show: false }" class="bg-white min-h-screen pt-10 relative mt-[70vh] lg:mt-0">
    <div class="flex justify-between px-5 lg:px-10">
        <a href="{{ $product->collection->url() }}" class="flex items-center gap-1.5 text-zinc-700 hover:text-zinc-800"
            wire:navigate>
            <x-lucide-chevron-left class="size-6 shrink-0" />
            <span class="text-xs font-semibold tracking-wide">До колекції</span>
        </a>

        <div class="flex gap-4">
            <button type="button">
                <x-lucide-share-2 class="size-6.5 fill-gray-100 stroke-gray-800" />
            </button>
            <a href="#" class="flex gap-0.5 items-center">
                <x-lucide-message-circle class="size-6.5 fill-gray-100 stroke-gray-800" />
            </a>
            <button type="button" x-data="{
                active: @entangle('isLiked'),
                handleLike() {
                    this.active = !this.active;
                    $wire.like();
                }
            }" @click="handleLike()"
                class="flex gap-0.5 items-center cursor-pointer group focus:outline-none">
                <x-lucide-heart class="size-6.5 transition-all duration-300 group-hover:scale-110" ::class="active ? 'fill-red-600 stroke-red-600' : 'fill-gray-100 stroke-gray-800'" />
            </button>
        </div>
    </div>

    <div class="flex flex-col mt-2.5 px-5 lg:px-10">
        <div class="text-black font-[SN_Pro] text-xl font-semibold">{{ $product->name }}</div>
        <div class="text-gray-600 text-sm font-[Oswald] font-medium tracking-wide leading-none">
            {{ $product->collection->getLabel() }}
        </div>
    </div>

    <div class="flex items-center justify-between mt-5 px-5 lg:px-10" x-intersect.threshold.50="show = false"
        x-intersect:leave.threshold.50="show = true">
        <div class="text-2xl font-[Oswald] font-semibold text-orange-500">
            {{ $product->currency->format($product->price) }}
        </div>

        <x-button wire:key="cart-btn-{{ $product->id }}" x-data="{ loading: false }" size="md"
            @click="loading = true; $dispatch('cart:add', { productId: {{ $product->id }} })"
            @cart-added.window="loading = false" ::disabled="loading" class="relative">
            <x-lucide-loader-circle x-show="loading" class="size-5 animate-spin mr-1.5" x-cloak />
            <span x-show="!loading" class="flex items-center">
                @if ($product->hasStock())
                    <x-lucide-shopping-cart class="size-5 mr-1.5 stroke-white" />
                @else
                    <x-lucide-wrench class="size-5 mr-1.5 stroke-white" />
                @endif
            </span>
            <span>{{ $product->hasStock() ? 'В кошик' : 'Замовити' }}</span>
        </x-button>
    </div>

    <x-table class="flex-none lg:ms-10 mt-10 w-full lg:max-w-md">
        @if ($product->sku)
            <x-table.row>
                <x-table.cell class="font-semibold text-black text-nowrap">Артикул (SKU)</x-table.cell>
                <x-table.cell class="text-gray-700">{{ $product->sku }}</x-table.cell>
            </x-table.row>
        @endif

        @if ($product->steel)
            <x-table.row>
                <x-table.cell class="font-semibold text-black text-nowrap">Марка сталі</x-table.cell>
                <x-table.cell class="text-gray-700">{{ $product->steel->getLabel() }}</x-table.cell>
            </x-table.row>
        @endif

        @if ($product->blade_grind)
            <x-table.row>
                <x-table.cell class="font-semibold text-black text-nowrap">Тип спусків</x-table.cell>
                <x-table.cell class="text-gray-700">{{ $product->blade_grind->getLabel() }}</x-table.cell>
            </x-table.row>
        @endif

        @if ($product->blade_shape)
            <x-table.row>
                <x-table.cell class="font-semibold text-black text-nowrap">Профіль клинка</x-table.cell>
                <x-table.cell class="text-gray-700">{{ $product->blade_shape->getLabel() }}</x-table.cell>
            </x-table.row>
        @endif

        @if ($product->blade_finish)
            <x-table.row>
                <x-table.cell class="font-semibold text-black text-nowrap">Фінішна обробка</x-table.cell>
                <x-table.cell class="text-gray-700">{{ $product->blade_finish->getLabel() }}</x-table.cell>
            </x-table.row>
        @endif

        @if ($product->handle_material)
            <x-table.row>
                <x-table.cell class="font-semibold text-black text-nowrap">Матеріал руків'я</x-table.cell>
                <x-table.cell class="text-gray-700">{{ $product->handle_material->getLabel() }}</x-table.cell>
            </x-table.row>
        @endif

        @if ($product->sheath)
            <x-table.row>
                <x-table.cell class="font-semibold text-black text-nowrap">Піхви / Чохол</x-table.cell>
                <x-table.cell class="text-gray-700">{{ $product->sheath->getLabel() }}</x-table.cell>
            </x-table.row>
        @endif

        <x-table.row>
            <x-table.cell class="font-semibold text-black text-nowrap">Наявність</x-table.cell>
            <x-table.cell>
                @if ($product->hasStock())
                    <x-lucide-check-circle class="size-5 stroke-green-500 inline-flex mr-1" />
                    <span class="text-green-500 text-sm font-medium">В наявності</span>
                @else
                    <span class="text-red-500 text-sm block font-medium">
                        Немає в наявності
                        <span class="text-[11px] text-gray-500 leading-4 block font-normal">
                            можу виготовити спеціально для вас
                        </span>
                    </span>
                @endif
            </x-table.cell>
        </x-table.row>
    </x-table>

    @if ($product->total_length || $product->blade_length || $product->blade_thickness)
        <div class="flex items-center gap-1.5 ps-5 mt-5 lg:ms-10 lg:ps-0">
            <div class="flex-none me-1.5">
                <x-lucide-ruler class="size-8 fill-zinc-100 stroke-zinc-600 stroke-[1.5]" />
            </div>
            <div class="flex flex-nowrap items-center gap-x-2.5 gap-y-1.5">
                @if ($product->total_length > 0)
                    <div class="flex flex-col">
                        <span class="textsm font-bold text-zinc-800 leading-none">
                            {{ number_format($product->total_length, 0) }} <small
                                class="text-[10px] font-medium text-zinc-500 uppercase">мм</small>
                        </span>
                        <span class="text-[10px] uppercase tracking-wider text-zinc-400 font-medium">Загальна</span>
                    </div>
                @endif

                @if ($product->blade_length > 0)
                    <div class="flex flex-col border-l border-zinc-200 pl-2.5">
                        <span class="text-sm font-bold text-zinc-800 leading-none">
                            {{ number_format($product->blade_length, 0) }} <small
                                class="text-[10px] font-medium text-zinc-500 uppercase">мм</small>
                        </span>
                        <span class="text-[10px] uppercase tracking-wider text-zinc-400 font-medium">Клинок</span>
                    </div>
                @endif

                @if ($product->blade_thickness > 0)
                    <div class="flex flex-col border-l border-zinc-200 pl-2.5">
                        <span class="text-sm font-bold text-zinc-800 leading-none">
                            {{ number_format($product->blade_thickness, 1) }} <small
                                class="text-[10px] font-medium text-zinc-500 uppercase">мм</small>
                        </span>
                        <span class="text-[10px] uppercase tracking-wider text-zinc-400 font-medium">Обух</span>
                    </div>
                @endif
                <img src="{{ Vite::asset('resources/images/made-in-ukraine.png') }}" class="size-15 object-contain"
                    alt="">
            </div>
        </div>
    @endif

    <div class="max-w-2xl mt-10 space-y-2.5 px-5 lg:px-10">
        <h3 class="text-lg font-semibold font-[SN_Pro]">Огляд та особливості</h3>
        <p class="text-gray-700 font-[Inter]">{{ $product->description }}</p>
    </div>

    <div class="px-5 lg:px-10 mt-5 flex flex-wrap gap-2.5">
        @each('partials.product.show.tags', $product->tags, 'tag')
    </div>

    @if ($product->isSold())
        <livewire:review :$product />
    @endif

    <div class="max-w-lg mt-10 scroll-mt-6 lg:scroll-mt-10 px-5 lg:px-10" id="comments-section">
        <livewire:comments :model="$product" />
    </div>

    {{-- <div class="max-w-2xl mt-10 space-y-2 px-6 lg:px-10">
        <h3 class="text-xl font-semibold font-[SN_Pro]">Інші товари</h3>
        <span class="h-20 border border-zinc-100">other products</span>
    </div> --}}

    <div x-show="show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-10" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-10"
        class="sticky bottom-5 z-20 mx-auto bg-white rounded-md shadow-lg p-1.5 flex items-center gap-1.5 w-fit border border-zinc-200/80"
        x-cloak>

        <div class="flex items-center gap-1 px-1">
            <a href="#comments-section" class="text-zinc-400 hover:text-zinc-600 p-2 transition-colors">
                <x-lucide-message-circle class="size-6 fill-gray-100 stroke-gray-800" />
            </a>
            <button type="button" x-data="{
                active: @entangle('isLiked'),
                handleLike() {
                    this.active = !this.active;
                    $wire.like();
                }
            }" @click="handleLike()"
                class="flex gap-0.5 items-center cursor-pointer group focus:outline-none">
                <x-lucide-heart class="size-6.5 transition-all duration-300 group-hover:scale-110" ::class="active ? 'fill-red-600 stroke-red-600' : 'fill-gray-100 stroke-gray-800'" />
            </button>
        </div>

        <x-button wire:key="cart-btn-{{ $product->id }}" x-data="{ loading: false }" size="md"
            @click="loading = true; $dispatch('cart:add', { productId: {{ $product->id }} })"
            @cart-added.window="loading = false" ::disabled="loading" class="relative">
            {{-- Лоадер (показується тільки при завантаженні) --}}
            <x-lucide-loader-circle x-show="loading" class="size-5 animate-spin mr-1.5" x-cloak />

            {{-- Блок іконок (ховається при завантаженні) --}}
            <span x-show="!loading" class="flex items-center">
                @if ($product->hasStock())
                    <x-lucide-shopping-cart class="size-5 mr-1.5 stroke-white" />
                @else
                    <x-lucide-wrench class="size-5 mr-1.5 stroke-white" />
                @endif
            </span>

            <span>{{ $product->hasStock() ? 'В кошик' : 'Замовити' }}</span>
        </x-button>
    </div>
</section>

@assets
    @vite('resources/js/pages/product.js')
@endassets
