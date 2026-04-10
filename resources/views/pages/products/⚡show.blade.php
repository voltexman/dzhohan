<?php

use Livewire\Attributes\Layout;
use App\Enums\ProductCategory;
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

        $this->isLiked = $this->product->isLiked();
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
                    <div class="embla-thumbs__container flex gap-1.5 lg:gap-2.5 justify-center p-5">
                        @foreach ($product->getMedia('products') as $media)
                            <div class="embla-thumbs__slide shrink-0 size-16 lg:size-24 cursor-pointer overflow-hidden rounded-lg border-2 border-zinc-100/15 transition-all duration-300 shadow-lg shadow-zinc-50/5 hover:shadow-zinc-50/10"
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

<section x-data="{ show: false }" class="bg-white min-h-screen pt-8 relative mt-[70vh] lg:mt-0">
    <div class="flex justify-between px-5 lg:px-10">
        <a href="{{ $product->category === ProductCategory::KNIFE ? $product->collection->url() : route('materials') }}"
            class="flex items-center gap-1.5 text-zinc-700 hover:text-zinc-800" wire:navigate>
            <x-lucide-chevron-left class="size-6 shrink-0" />
            <span class="text-xs font-semibold tracking-wide">
                {{ $product->category === ProductCategory::KNIFE ? 'До колекції' : 'До магазину' }}
            </span>
        </a>

        <div class="flex gap-4">
            {{-- <button type="button">
                <x-lucide-share-2 class="size-6.5 fill-gray-100 stroke-gray-800" />
            </button> --}}
            <div @click="const el = document.getElementById('comment-body'); el.scrollIntoView({ behavior: 'smooth', block: 'center' }); el.focus();"
                class="flex gap-0.5 items-center cursor-pointer">
                <x-lucide-message-circle class="size-6.5 fill-gray-100 stroke-gray-800" />
            </div>
            <button type="button" wire:click="like"
                class="flex gap-0.5 items-center cursor-pointer group focus:outline-none">
                <x-lucide-heart class="size-6.5 transition-all duration-300 group-hover:scale-110"
                    x-bind:class="$wire.isLiked ? 'fill-red-600 stroke-red-600' : 'fill-gray-100 stroke-gray-800'" />
            </button>
        </div>
    </div>

    <div class="flex flex-col mt-2.5 px-5 lg:px-10">
        <div class="text-black font-[SN_Pro] text-xl font-semibold">{{ $product->name }}</div>
        @if ($product->category === ProductCategory::KNIFE)
            <div class="text-zinc-600 text-sm font-[Oswald] font-medium tracking-wider leading-none">
                {{ $product->collection->getLabel() }}
            </div>
        @endif
    </div>

    <div class="flex items-center justify-between mt-5 px-5 lg:px-10" x-intersect.threshold.50="show = false"
        x-intersect:leave.threshold.50="show = true">
        <div class="text-2xl font-[Oswald] font-semibold text-orange-500">
            {{ $product->currency?->format($product->price) }}
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
        <x-table.row>
            <x-table.cell class="font-semibold text-black text-nowrap">Артикул (SKU)</x-table.cell>
            <x-table.cell class="text-gray-700">{{ $product->sku }}</x-table.cell>
        </x-table.row>

        @foreach ($product->productAttributeValues as $item)
            <x-table.row>
                <x-table.cell class="font-semibold text-black text-nowrap">
                    {{ $item->attribute->name }}
                </x-table.cell>
                <x-table.cell class="text-gray-700">
                    {{ $item->value->value }}
                </x-table.cell>
            </x-table.row>
        @endforeach

        <x-table.row>
            <x-table.cell class="font-semibold text-black text-nowrap">Наявність</x-table.cell>
            <x-table.cell>
                @if ($product->category === ProductCategory::KNIFE)
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
                @else
                    <span class="text-zinc-700 text-sm font-medium">
                        В наявності: <span class="text-zinc-900 font-bold">{{ $product->quantity }}</span> шт.
                    </span>
                @endif
            </x-table.cell>
        </x-table.row>
    </x-table>

    @if ($product->category === ProductCategory::KNIFE)
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

    @isset($product->short_youtube_video_id)
        <div class="px-5 lg:px-10 mt-5">
            <a href="https://www.youtube.com/shorts/{{ $product->short_youtube_video_id }}"
                class="py-3.5 px-6 text-sm bg-zinc-900 hover:bg-black text-white rounded-md inline-flex items-center gap-2 transition-colors"
                target="_blank" rel="noopener noreferrer">
                <x-lucide-play class="size-4 shrink-0" />
                Короткий огляд ножа
            </a>
        </div>
    @endisset

    @if (filled(trim(strip_tags($product->description))))
        <div class="max-w-3xl mt-10 space-y-2.5 px-5 lg:px-10">
            <h3 class="text-lg font-semibold font-[SN_Pro]">Огляд та особливості</h3>
            <p class="text-gray-700 font-[Inter]">{!! $product->description !!}</p>
        </div>
    @endif

    <div class="px-5 lg:px-10 mt-5 flex flex-wrap gap-2.5">
        @each('partials.product.show.tags', $product->tags, 'tag')
    </div>

    @isset($product->full_youtube_video_id)
        <div class="px-5 lg:px-10 mt-5 max-w-2xl">
            <div class="relative w-full aspect-video">
                <iframe class="absolute top-0 left-0 w-full h-full"
                    src="https://www.youtube.com/embed/{{ $product->full_youtube_video_id }}" title="YouTube video player"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    referrerpolicy="strict-origin-when-cross-origin" frameborder="0" allowfullscreen>
                </iframe>
            </div>
        </div>
    @endisset

    @if ($product->isSold())
        <livewire:review :$product />
    @endif

    <div class="max-w-xl mt-10 scroll-mt-20 lg:scroll-mt-8 px-5 lg:px-10" id="comments-section">
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
            <div @click="const el = document.getElementById('comment-body'); el.scrollIntoView({ behavior: 'smooth', block: 'center' }); el.focus();"
                class="text-zinc-400 hover:text-zinc-600 p-2 transition-colors cursor-pointer">
                <x-lucide-message-circle class="size-6 fill-gray-100 stroke-gray-800" />
            </div>
            <button wire:click="like" class="flex gap-0.5 items-center cursor-pointer group focus:outline-none">
                <x-lucide-heart class="size-6.5 transition-all duration-300 group-hover:scale-110"
                    x-bind:class="$wire.isLiked ? 'fill-red-600 stroke-red-600' : 'fill-gray-100 stroke-gray-800'" />
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

@push('scripts')
    @vite('resources/js/pages/product-show.js')
@endpush
