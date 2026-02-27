<?php

use App\Models\Product;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Session;
use Livewire\Component;

new class extends Component {
    public string $position = '';

    #[Session]
    public string $search = '';

    #[Computed]
    public function results()
    {
        if (strlen($this->search) < 2) {
            return collect();
        }

        return Product::where('name', 'like', '%' . $this->search . '%')
            ->limit(5)
            ->get();
    }
};
?>

<div x-data="{
    open: false,
    mobileFullWidth: true,

    // 'start', 'end', 'top', 'bottom'
    position: '{{ $position }}',

    // 'xs', 'sm', 'md', 'lg', 'xl'
    size: 'xl',

    // Set transition classes based on position
    transitionClasses: {
        'x-transition:enter-start'() {
            if (this.position === 'start') {
                return '-translate-x-full rtl:translate-x-full';
            } else if (this.position === 'end') {
                return 'translate-x-full rtl:-translate-x-full';
            } else if (this.position === 'top') {
                return '-translate-y-full';
            } else if (this.position === 'bottom') {
                return 'translate-y-full';
            }
        },
        'x-transition:leave-end'() {
            if (this.position === 'start') {
                return '-translate-x-full rtl:translate-x-full';
            } else if (this.position === 'end') {
                return 'translate-x-full rtl:-translate-x-full';
            } else if (this.position === 'top') {
                return '-translate-y-full';
            } else if (this.position === 'bottom') {
                return 'translate-y-full';
            }
        },
    },
}" x-on:keydown.esc.prevent="open = false">
    {{-- Trigger --}}
    <button x-on:click="open = 'search'" type="button"
        class="relative rounded-md p-1.5 cursor-pointer transition-colors duration-500">
        <x-lucide-search class="size-6" />

        <span class="absolute top-0 right-0 flex size-2">
            <span class="absolute inline-flex size-full animate-ping rounded-full bg-red-400 opacity-75"></span>
            <span class="relative inline-flex size-2 rounded-full bg-red-500"></span>
        </span>
    </button>
    {{-- End Trigger --}}

    <template x-teleport="body">
        <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" x-bind:aria-hidden="!open" tabindex="-1" role="dialog"
            aria-labelledby="pm-offcanvas-title"
            class="z-90 fixed inset-0 overflow-hidden bg-stone-900/60 backdrop-blur-sm"
            x-effect="document.body.style.overflow = open ? 'hidden' : 'auto'">
            <!-- Offcanvas Sidebar -->
            <div x-cloak x-show="open" x-on:click.away="open = false" x-bind="transitionClasses"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-end="translate-x-0 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="translate-x-0 translate-y-0" role="document"
                class="absolute flex w-full flex-col bg-white shadow-lg will-change-transform"
                x-bind:class="{
                    'h-dvh top-0 end-0': position === 'end',
                    'h-dvh top-0 start-0': position === 'start',
                    'bottom-0 start-0 end-0': position === 'top',
                    'bottom-0 start-0 end-0': position === 'bottom',
                    'h-64': position === 'top' || position === 'bottom',
                    'sm:max-w-xs': size === 'xs' && !(position === 'top' || position === 'bottom'),
                    'sm:max-w-sm': size === 'sm' && !(position === 'top' || position === 'bottom'),
                    'sm:max-w-md': size === 'md' && !(position === 'top' || position === 'bottom'),
                    'sm:max-w-lg': size === 'lg' && !(position === 'top' || position === 'bottom'),
                    'sm:max-w-xl': size === 'xl' && !(position === 'top' || position === 'bottom'),
                    'max-w-72': !mobileFullWidth && !(position === 'top' || position === 'bottom'),
                }">
                <!-- Header -->
                <div class="flex min-h-16 flex-none items-center justify-between border-b border-zinc-50 px-6 md:px-10">
                    <h3 id="offcanvas-title" class="py-5 font-medium">Пошук</h3>

                    <!-- Close Button -->
                    <button x-on:click="open = false" type="button"
                        class="absolute top-3 right-3 inline-flex items-center justify-center size-8 rounded-full bg-black text-zinc-50 hover:bg-zinc-800 hover:text-zinc-200 transition-colors duration-300 cursor-pointer">
                        <x-lucide-x class="-mx-1 inline-block size-4" />
                    </button>
                    <!-- END Close Button -->
                </div>
                <!-- END Header -->

                <!-- Content -->
                <div class="flex grow flex-col overflow-y-auto p-5 md:p-7">
                    <div x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-4"
                        class="flex flex-col size-full max-w-xl mx-auto">
                        <div class="grow overflow-y-auto">
                            @if (strlen($search) < 2)
                                <!-- Початковий стан -->
                                <div class="flex flex-col justify-center items-center size-full">
                                    <x-lucide-search class="size-15 opacity-50" stroke-width="1.5" />
                                    <span class="font-semibold text-lg mt-5">Пошук матеріалів</span>
                                    <span class="text-gray-600 text-sm text-center max-w-xs">
                                        Введіть назву товара або статті для пошуку...
                                    </span>
                                </div>
                            @elseif($this->results->isEmpty())
                                <!-- Нічого не знайдено -->
                                <div class="flex flex-col justify-center items-center size-full">
                                    <x-lucide-search-x class="size-15 opacity-50" stroke-width="1.5" />
                                    <span class="font-semibold text-lg mt-5">Нічого не знайдено</span>
                                    <span class="text-gray-500 text-sm text-center max-w-xs">
                                        Ми не знайшли товарів за запитом "{{ $search }}". Спробуйте іншу назву.
                                    </span>
                                </div>
                            @else
                                <!-- Список результатів -->
                                <div class="flex flex-col gap-2.5">
                                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Результати
                                        пошуку</span>
                                    @foreach ($this->results as $product)
                                        <a href="{{ route('product.show', $product) }}" wire:navigate
                                            class="flex items-center gap-5 p-1.5 hover:bg-zinc-50 transition-colors">
                                            <div class="size-14 bg-zinc-100 shrink-0 overflow-hidden">
                                                <img src="{{ Vite::asset('resources/images/header.png') }}"
                                                    class="size-full object-cover" alt="{{ $product->name }}">
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="font-medium text-gray-900 leading-tight">
                                                    {{ $product->name }}
                                                </span>
                                                <span class="text-sm text-gray-500">
                                                    {{ number_format($product->price, 0, '.', ' ') }} грн
                                                </span>
                                            </div>
                                            <x-lucide-chevron-right class="size-4 ms-auto stroke-gray-400" />
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Поле вводу -->
                        <div class="relative">
                            <x-form.input wire:model.live.debounce.300ms="search" class="bg-zinc-100 w-full"
                                color="soft" size="lg" icon="search" placeholder="Що ви шукаєте?" />

                            @if ($search)
                                <button wire:click="$set('search', '')"
                                    class="absolute right-4 top-1/2 -translate-y-1/2">
                                    <x-lucide-loader-circle wire:loading class="size-5 stroke-gray-700 animate-spin" />
                                    <x-lucide-circle-x wire:loading.remove class="size-5 stroke-gray-700" />
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- END Content -->
            </div>
            <!-- END Offcanvas Sidebar -->
        </div>
    </template>
</div>
