<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Product;

new #[Layout('layouts::cart')] class extends Component {
    public Product $product;

    public function mount(Product $product): void
    {
        $this->product = $product;
    }

    public function like($productId)
    {
        $product = $this->product->find($productId);

        if ($product) {
            $product->isLiked() ? $product->unlike() : $product->like();
        }
    }
};
?>

@section('images')
    {{-- <div class="fixed lg:sticky top-0 left-0 w-full h-[80vh] lg:h-screen -z-10 overflow-hidden">
        <div class="bg-fixed lg:bg-local size-full bg-cover bg-center bg-no-repeat"
            style="background-image: url('{{ $product->getFirstMediaUrl('images') }}')">
        </div>
    </div> --}}

    <div class="embla">
        <div class="embla__viewport">
            <div class="embla__container">
                <div class="embla__slide">
                    <div class="embla__slide__number">1</div>
                </div>
                <div class="embla__slide">
                    <div class="embla__slide__number">2</div>
                </div>
                <div class="embla__slide">
                    <div class="embla__slide__number">3</div>
                </div>
                <div class="embla__slide">
                    <div class="embla__slide__number">4</div>
                </div>
                <div class="embla__slide">
                    <div class="embla__slide__number">5</div>
                </div>
                <div class="embla__slide">
                    <div class="embla__slide__number">6</div>
                </div>
                <div class="embla__slide">
                    <div class="embla__slide__number">7</div>
                </div>
                <div class="embla__slide">
                    <div class="embla__slide__number">8</div>
                </div>
                <div class="embla__slide">
                    <div class="embla__slide__number">9</div>
                </div>
                <div class="embla__slide">
                    <div class="embla__slide__number">10</div>
                </div>
            </div>
        </div>
        <div class="embla-thumbs">
            <div class="embla-thumbs__viewport">
                <div class="embla-thumbs__container">
                    <div class="embla-thumbs__slide embla-thumbs__slide--selected">
                        <button type="button" class="embla-thumbs__slide__number">
                            1
                        </button>
                    </div>
                    <div class="embla-thumbs__slide">
                        <button type="button" class="embla-thumbs__slide__number">
                            2
                        </button>
                    </div>
                    <div class="embla-thumbs__slide">
                        <button type="button" class="embla-thumbs__slide__number">
                            3
                        </button>
                    </div>
                    <div class="embla-thumbs__slide">
                        <button type="button" class="embla-thumbs__slide__number">
                            4
                        </button>
                    </div>
                    <div class="embla-thumbs__slide">
                        <button type="button" class="embla-thumbs__slide__number">
                            5
                        </button>
                    </div>
                    <div class="embla-thumbs__slide">
                        <button type="button" class="embla-thumbs__slide__number">
                            6
                        </button>
                    </div>
                    <div class="embla-thumbs__slide">
                        <button type="button" class="embla-thumbs__slide__number">
                            7
                        </button>
                    </div>
                    <div class="embla-thumbs__slide">
                        <button type="button" class="embla-thumbs__slide__number">
                            8
                        </button>
                    </div>
                    <div class="embla-thumbs__slide">
                        <button type="button" class="embla-thumbs__slide__number">
                            9
                        </button>
                    </div>
                    <div class="embla-thumbs__slide">
                        <button type="button" class="embla-thumbs__slide__number">
                            10
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<section x-data="{ show: false }" class="bg-white min-h-screen py-8 relative mt-[80vh] lg:mt-0">
    <div class="flex justify-between px-6 lg:px-10">
        <a href="{{ url()->previous() }}" class="flex" wire:navigate>
            <x-lucide-chevron-left class="size-6 stroke-gray-800" />
        </a>
        <div class="flex gap-4">
            <button type="button">
                <x-lucide-share-2 class="size-6.5 stroke-gray-800" />
            </button>
            <a href="#comments-section" class="flex gap-0.5 items-center">
                <x-lucide-message-circle class="size-6.5 stroke-gray-800" />
            </a>
            <button type="button" wire:click="like({{ $product->id }})"
                wire:loading.class="animate-pulse pointer-events-none" wire:target="like({{ $product->id }})"
                class="flex gap-0.5 items-center cursor-pointer">
                <x-lucide-heart
                    class="size-6.5 {{ $product->isLiked() ? 'fill-red-600 stroke-red-600' : 'stroke-gray-800' }}" />
            </button>
        </div>
    </div>

    <div class="flex flex-col mt-2.5 px-6 lg:px-10">
        <div class="text-gray-900 font-[SN_Pro] text-2xl font-semibold">{{ $product->name }}</div>
        <div class="text-gray-500 font-[Oswald] font-medium tracking-wide leading-none">
            {{ $product->category->label() }}
        </div>
    </div>

    <div class="flex items-center justify-between mt-5 px-6 lg:px-10" x-intersect.threshold.50="show = false"
        x-intersect:leave.threshold.50="show = true">
        <div class="text-2xl font-[Oswald] font-semibold text-zinc-900">
            ${{ number_format($product->price, 2) }}
        </div>

        @if ($product->hasStock())
            {{-- Товар у наявності --}}
            <x-button size="md" wire:click="$dispatch('cart:add', { productId: {{ $product->id }} })">
                <x-lucide-plus class="size-5 inline-flex mr-0.5 stroke-white" />
                В кошик
            </x-button>
        @else
            {{-- Товару немає, але можна замовити виготовлення --}}
            <x-button size="md" wire:click="$dispatch('cart:add', { productId: {{ $product->id }} })">
                <x-lucide-hammer class="size-5 inline-flex mr-0.5 stroke-white" />
                Замовити
            </x-button>
        @endif
    </div>

    <x-table class="flex-none lg:ms-10 mt-10 w-full max-w-md">
        @if ($product->sku)
            <x-table.row>
                <x-table.cell class="font-bold text-nowrap">Артикул (SKU)</x-table.cell>
                <x-table.cell class="text-gray-700">{{ $product->sku }}</x-table.cell>
            </x-table.row>
        @endif

        <x-table.row>
            <x-table.cell class="font-bold text-nowrap">Марка сталі</x-table.cell>
            <x-table.cell class="text-gray-700">{{ $product->steel->label() }}</x-table.cell>
        </x-table.row>

        <x-table.row>
            <x-table.cell class="font-bold text-nowrap">Матеріал руків'я</x-table.cell>
            <x-table.cell class="text-gray-700">{{ $product->handle_material->label() }}</x-table.cell>
        </x-table.row>

        <x-table.row>
            <x-table.cell class="font-bold text-nowrap">Профіль клинка</x-table.cell>
            <x-table.cell class="text-gray-700">{{ $product->blade_shape->label() }}</x-table.cell>
        </x-table.row>

        <x-table.row>
            <x-table.cell class="font-bold text-nowrap">Наявність</x-table.cell>
            <x-table.cell>
                @if ($product->hasStock())
                    <x-lucide-check-circle class="size-5 stroke-green-500 inline-flex mr-1" />
                    @if ($product->quantity === 1)
                        <span class="text-green-500 text-sm">
                            В наявності
                        </span>
                    @endif
                @else
                    <span class="text-red-500 text-sm block">
                        Немає в наявності <br>
                        <span class="text-xs text-gray-500 leading-4 block">
                            можемо виготовити спеціально для вас
                        </span>
                    </span>
                @endif
            </x-table.cell>
        </x-table.row>
    </x-table>

    <div class="max-w-2xl mt-10 space-y-2 px-6 lg:px-10">
        <h3 class="text-lg font-semibold font-[SN_Pro]">Огляд та особливості</h3>
        <p class="text-gray-700 font-[Inter]">{{ $product->description }}</p>
    </div>

    <div class="max-w-lg mt-10 scroll-mt-6 lg:scroll-mt-10 px-6 lg:px-10" id="comments-section">
        <livewire:comments :model="$product" />
    </div>

    <div class="max-w-2xl mt-10 space-y-2 px-6 lg:px-10">
        <h3 class="text-xl font-semibold font-[SN_Pro]">Інші товари</h3>
        <span class="h-20 border border-zinc-100">other products</span>
    </div>

    {{-- <template x-teleport="body"> --}}
    {{-- <div x-show="show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-10" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-10"
        class="fixed z-20 bottom-5 left-1/2 -translate-x-1/2 bg-white rounded-md shadow-lg p-1.5 flex items-center gap-1.5 w-fit max-w-sm border border-zinc-100">
        <a href="#comments-section" class="text-gray-400 hover:text-gray-600 flex-none px-2">
            <x-lucide-message-circle class="size-6 stroke-gray-700" />
        </a>
        <button type="button" class="flex-none">
            <x-lucide-heart class="size-6 stroke-red-500" />
        </button>
        @if ($product->hasStock())
            <x-button size="md" class="shrink whitespace-nowrap flex-none ms-2.5"
                wire:click="$dispatch('cart:add', { productId: {{ $product->id }} })">
                <x-lucide-plus class="size-5 inline-flex mr-1.5 mt-0.5 stroke-white" />
                В кошик
            </x-button>
        @else
            <x-button size="md" class="shrink whitespace-nowrap flex-none ms-2.5"
                wire:click="$dispatch('cart:add', { productId: {{ $product->id }} })">
                <x-lucide-hammer class="size-5 inline-flex mr-1.5 mt-0.5 stroke-white" />
                Замовити
            </x-button>
        @endif
    </div> --}}
    {{-- </template> --}}
</section>

@vite('resources/js/pages/product.js')

<style>
    .embla {
        max-width: 48rem;
        margin: auto;
        --slide-height: 19rem;
        --slide-spacing: 1rem;
        --slide-size: 100%;
    }

    .embla__viewport {
        overflow: hidden;
    }

    .embla__container {
        display: flex;
        touch-action: pan-y pinch-zoom;
        margin-left: calc(var(--slide-spacing) * -1);
    }

    .embla__slide {
        flex: 0 0 var(--slide-size);
        min-width: 0;
        padding-left: var(--slide-spacing);
    }

    .embla__slide__number {
        border: 0.2rem solid var(--detail-medium-contrast);
        border-radius: 1.8rem;
        font-size: 4rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        height: var(--slide-height);
        user-select: none;
    }

    .embla-thumbs {
        --thumbs-slide-spacing: 0.8rem;
        --thumbs-slide-height: 6rem;
        margin-top: var(--thumbs-slide-spacing);
    }

    .embla-thumbs__viewport {
        overflow: hidden;
    }

    .embla-thumbs__container {
        display: flex;
        flex-direction: row;
        margin-left: calc(var(--thumbs-slide-spacing) * -1);
    }

    .embla-thumbs__slide {
        flex: 0 0 22%;
        min-width: 0;
        padding-left: var(--thumbs-slide-spacing);
    }

    @media (min-width: 576px) {
        .embla-thumbs__slide {
            flex: 0 0 15%;
        }
    }

    .embla-thumbs__slide__number {
        border-radius: 1.8rem;
        -webkit-tap-highlight-color: rgba(var(--text-high-contrast-rgb-value), 0.5);
        -webkit-appearance: none;
        appearance: none;
        background-color: transparent;
        touch-action: manipulation;
        display: inline-flex;
        text-decoration: none;
        cursor: pointer;
        border: 0;
        padding: 0;
        margin: 0;
        border: 0.2rem solid var(--detail-medium-contrast);
        font-size: 1.8rem;
        font-weight: 600;
        color: var(--detail-high-contrast);
        display: flex;
        align-items: center;
        justify-content: center;
        height: var(--thumbs-slide-height);
        width: 100%;
    }

    .embla-thumbs__slide--selected .embla-thumbs__slide__number {
        color: var(--text-body);
    }
</style>
