<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Product;

new #[Layout('layouts::cart')] class extends Component {
    public Product $product;

    public bool $isLiked = false;

    public int $rating = 5;

    public $author_name = '';
    public $body = '';
    public $email = '';
    public $replyTo = null;

    public string $tab = 'instructions';

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
            ${{ number_format($product->price, 2) }}
        </div>

        <x-button size="md" x-data="{ loading: false }"
            @click="loading = true; $dispatch('cart:add', { productId: {{ $product->id }} })"
            @cart-added.window="loading = false" ::disabled="loading">
            <x-lucide-loader-circle x-show="loading" class="size-5 inline-flex mr-0.5 animate-spin" x-cloak />
            <template x-if="!loading">
                @if ($product->hasStock())
                    <x-lucide-plus class="size-5 inline-flex mr-0.5 stroke-white" />
                @else
                    <x-lucide-hammer class="size-5 inline-flex mr-0.5 stroke-white" />
                @endif
            </template>
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
                            можемо виготовити спеціально для вас
                        </span>
                    </span>
                @endif
            </x-table.cell>
        </x-table.row>
    </x-table>

    <div class="max-w-2xl mt-10 space-y-2.5 px-5 lg:px-10">
        <h3 class="text-lg font-semibold font-[SN_Pro]">Огляд та особливості</h3>
        <p class="text-gray-700 font-[Inter]">{{ $product->description }}</p>
    </div>

    <div class="px-5 lg:px-10 mt-5 flex flex-wrap gap-2.5">
        @each('partials.product.show.tags', $product->tags, 'tag')
    </div>

    <div x-data="{
        open: false,
        rating: @entangle('rating'),
        hoverRating: 0
    }" class="bg-zinc-50 max-w-xl lg:mx-10 p-5 border border-zinc-100 mt-10">
        <button @click="open = !open" class="flex items-center justify-between w-full group cursor-pointer text-left">
            <div class="flex items-center gap-3">
                <div
                    class="size-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 shrink-0">
                    <x-lucide-shopping-bag class="size-5 shrink-0" />
                </div>
                <div>
                    <h4 class="font-bold text-zinc-900">Ви покупець цього ножа?</h4>
                    <p class="text-xs text-zinc-500">Поділіться досвідом користування та оцініть якість</p>
                </div>
            </div>
            <x-lucide-chevron-down class="size-5 text-zinc-400 transition-transform duration-300" ::class="open ? 'rotate-180 text-orange-600' : ''" />
        </button>

        @if (!$product->hasStock())
            <div x-show="open" x-collapse x-cloak class="mt-5 pt-5 border-t border-zinc-200/60">
                <form wire:submit.prevent="send" class="space-y-5">
                    <div class="space-y-2.5">
                        <label class="text-sm font-semibold text-zinc-700">Ваша оцінка:</label>
                        <div class="flex gap-1.5">
                            @foreach (range(1, 5) as $star)
                                <button type="button" @click="rating = {{ $star }}"
                                    @mouseenter="hoverRating = {{ $star }}"
                                    class="cursor-pointer transition-all duration-200 transform hover:scale-125 focus:outline-none">
                                    <x-lucide-star class="size-8 transition-colors duration-200" ::class="(hoverRating || rating) >= {{ $star }} ?
                                        'fill-orange-500 stroke-orange-500' :
                                        'fill-zinc-200 stroke-zinc-300'" />
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-form.input wire:model="author_name" placeholder="Ваше ім’я" />
                        <x-form.input wire:model="email" type="email" placeholder="Email (не публікується)" />
                    </div>

                    <x-form.textarea wire:model="body" rows="3"
                        placeholder="Розкажіть про ніж: як тримає заточку, ергономіку..." />

                    <x-button type="submit" size="md" class="w-full sm:w-auto">
                        <x-lucide-award class="size-4 mr-2" />
                        Опублікувати відгук
                    </x-button>
                </form>
            </div>
        @endif
    </div>

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
                <x-lucide-message-circle class="size-6 stroke-zinc-700" />
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

        <x-button size="md" x-data="{ loading: false }"
            @click="loading = true; $dispatch('cart:add', { productId: {{ $product->id }} })"
            @cart-added.window="loading = false" ::disabled="loading">
            <x-lucide-loader-circle x-show="loading" class="size-5 inline-flex mr-0.5 animate-spin" x-cloak />
            <template x-if="!loading">
                @if ($product->hasStock())
                    <x-lucide-plus class="size-5 inline-flex mr-0.5 stroke-white" />
                @else
                    <x-lucide-hammer class="size-5 inline-flex mr-0.5 stroke-white" />
                @endif
            </template>
            <span>{{ $product->hasStock() ? 'В кошик' : 'Замовити' }}</span>
        </x-button>
    </div>
</section>

@assets
    @vite('resources/js/pages/product.js')
@endassets
