<!-- Offcanvas -->
<!-- An Alpine.js and Tailwind CSS component by https://pinemix.com -->
{{-- <div x-data="{
    open: false,
    mobileFullWidth: true,

    // 'start', 'end', 'top', 'bottom'
    position: 'end',

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
}" @offcanvas.window="open = true" x-on:keydown.esc.prevent="open = false"
    class="fixed top-0 z-40">
    <!-- Offcanvas Backdrop -->
    <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-bind:aria-hidden="!open"
        tabindex="-1" role="dialog" aria-labelledby="pm-offcanvas-title"
        class="z-90 fixed inset-0 overflow-hidden bg-zinc-900/60 backdrop-blur-[1px]">
        <!-- Offcanvas Sidebar -->
        <div x-cloak x-show="open" x-on:click.away="open = false" x-bind="transitionClasses"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-end="translate-x-0 translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0 translate-y-0"
            role="document" class="absolute flex w-full flex-col bg-white shadow-lg will-change-transform"
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
            <div
                class="flex min-h-16 flex-none items-center justify-between border-b border-zinc-100 px-5 dark:border-zinc-800 md:px-7">
                <h3 id="pm-offcanvas-title" class="py-5 font-semibold">Title</h3>

                <!-- Close Button -->
                <button x-on:click="open = false" type="button"
                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-zinc-200 bg-white px-3 py-2 text-xs font-semibold leading-5 text-zinc-800 hover:border-zinc-300 hover:text-zinc-900 hover:shadow-xs focus:ring-zinc-300/25 active:border-zinc-200 active:shadow-none dark:border-zinc-700 dark:bg-transparent dark:text-zinc-300 dark:hover:border-zinc-600 dark:hover:text-zinc-200 dark:focus:ring-zinc-600/50 dark:active:border-zinc-700">
                    <svg class="hi-solid hi-x -mx-1 inline-block size-4" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
                <!-- END Close Button -->
            </div>
            <!-- END Header -->

            <!-- Content -->
            <div class="flex grow flex-col overflow-y-auto p-5 md:p-7">
                <!-- Placeholder -->
                <div
                    class="flex h-full flex-col items-center justify-center gap-5 rounded-lg border-2 border-dashed border-zinc-200/75 bg-zinc-50 py-44 text-sm font-medium text-zinc-400 dark:border-zinc-700 dark:bg-zinc-950/25 dark:text-zinc-600">
                </div>
            </div>
            <!-- END Content -->
        </div>
        <!-- END Offcanvas Sidebar -->
    </div>
    <!-- END Offcanvas Backdrop -->
</div> --}}
<!-- END Offcanvas -->

<div x-data="{ scrolled: false }" @scroll.window="$wire.mode === 'top' && (scrolled = window.scrollY > 50)"
    x-init="scrolled = window.scrollY > 50"
    class="fixed z-30 top-0 h-18 text-white w-full flex items-center justify-between px-4 gap-2.5 transition-colors duration-500"
    :class="{ 'bg-black/90 backdrop-blur-xs shadow-lg': scrolled }">
    <div class="order-1 flex-none" :class="{ 'me-auto': $wire.mode === 'top', 'mb-auto': $wire.mode === 'left' }">
        @unless (Route::is('home'))
            <a href="{{ route('home') }}" wire:navigate>
                <img src="{{ Vite::asset('resources/images/logo_light.svg') }}" class="size-20"
                    :class="{ 'hidden': $wire.open !== null }" alt="">
            </a>
            <a href="{{ route('home') }}" wire:navigate>
                <img src="{{ Vite::asset('resources/images/logo_dark.svg') }}" class="size-20"
                    :class="{ 'hidden': $wire.open === null }" alt="">
            </a>
        @endunless
    </div>

    <x-nav class="lg:order-1 mx-auto" x-cloak>
        <x-nav.item label="Головна" url="home" icon="home" />
        <x-nav.item label="Про мене" url="about" icon="user-round" />
        <x-nav.item label="Товари" url="products" icon="package" />
        <x-nav.item label="Галерея" url="gallery" icon="images" />
        <x-nav.item label="Блог" url="blog" icon="newspaper" />
        <x-nav.item label="Контакти" url="contacts" icon="notebook-text" />
    </x-nav>

    <button wire:click="open = 'search'" type="button"
        class="order-2 relative rounded-md p-1.5 cursor-pointer transition-colors duration-500">
        <x-lucide-search class="size-6" />

        <!-- Індикатор активного пошуку -->
        @if ($search)
            <span class="absolute top-0 right-0 flex size-2">
                <span class="absolute inline-flex size-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex size-2 rounded-full bg-red-500"></span>
            </span>
        @endif
    </button>

    <button wire:click="open = 'cart'" type="button"
        class="order-3 relative rounded-md p-1.5 cursor-pointer transition-colors duration-500">
        <x-lucide-shopping-cart class="size-6" />

        <!-- Індикатор кількості (показується лише якщо кошик не порожній) -->
        @if ($this->cartItems->isNotEmpty())
            <span
                class="absolute -top-1.5 -right-1.5 flex size-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white shadow-sm">
                {{ $this->cartItems->sum('qty') }}
            </span>
        @endif
    </button>

    <button wire:click="open = 'menu'" type="button"
        class="order-4 lg:hidden relative rounded-md p-1.5 transition-colors duration-500">
        <x-lucide-menu class="size-6" />
    </button>
</div>


{{-- <div x-data="{ scrolled: false }" @scroll.window="$wire.mode === 'top' && (scrolled = window.scrollY > 50)"
    x-init="scrolled = window.scrollY > 50"
    class="fixed flex w-full h-screen top-0 left-0 z-50 overflowhidden transition-all duration-750"
    :class="{
        /* TOP */
        'bg-transparent -translate-y-[calc(100%-64px)]': $wire.mode === 'top' && $wire.open === null,
        'bg-white flex-col': $wire.mode === 'top' && $wire.open !== null,
    
        /* LEFT */
        'bg-transparent flex-row -translate-x-[calc(100%-80px)]': $wire.mode === 'left' && $wire.open === null,
        'bg-white flex-col lg:flex-row': $wire.mode === 'left' && $wire.open !== null,
    }"
    x-effect="document.body.style.overflow = $wire.open !== null ? 'hidden' : 'auto'">

    <div wireshow="open !== null" class="flex-1 flex flex-col">
        <div class="relative h-18 shrink-0 flex items-center py-5">
            <h1 class=" font-[Russo_One] tracking-wide text-xl mx-auto drop-shadow-lg font-black">
                <span class="font-thin text-gray-600">Dzhohun</span><span class="text-gray-900">Knives</span>
            </h1>

            <x-button color="dark" size="sm" icon wire:click="open = null"
                class="absolute top-3 hover:bg-orange-500 right-3 lg:top-4 lg:right-4 rounded-full!">
                <x-lucide-x class="size-4" />
            </x-button>
        </div>

        <div class="overflow-hidden relative size-full flex-1">
            <div wire:show="open === 'search'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4"
                class="p-4 flex flex-col size-full overflow-hidden max-w-xl mx-auto absolute inset-0">
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
                                        <span
                                            class="font-medium text-gray-900 leading-tight">{{ $product->name }}</span>
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
                    <x-form.input wire:model.live.debounce.300ms="search" class="bg-zinc-100 w-full" color="soft"
                        size="lg" icon="search" placeholder="Що ви шукаєте?" />

                    @if ($search)
                        <button wire:click="$set('search', '')" class="absolute right-4 top-1/2 -translate-y-1/2">
                            <x-lucide-loader-circle wire:loading class="size-5 stroke-gray-700 animate-spin" />
                            <x-lucide-circle-x wire:loading.remove class="size-5 stroke-gray-700" />
                        </button>
                    @endif
                </div>
            </div>

            <div wire:show="open === 'cart'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-8"
                class="p-5 flex flex-col size-full overflow-hidden max-w-xl mx-auto absolute inset-0">
                <div class="grow overflow-y-auto">
                    @if ($this->cartItems->isEmpty())
                        <!-- Стан: Порожньо -->
                        <div class="flex flex-col justify-center items-center size-full text-center">
                            <x-lucide-shopping-cart class="size-15 opacity-50" stroke-width="1.5" />
                            <span class="font-semibold text-lg mt-5">Кошик порожній</span>
                            <span class="text-gray-500 text-sm max-w-2xs">
                                Перегляньте товари та додайте їх до кошика, щоб зробити замовлення
                            </span>
                            <x-button wire:click="open = null" color="dark" class="mt-8 px-10">
                                До покупок
                            </x-button>
                        </div>
                    @else
                        <!-- Список товарів у кошику -->
                        <div class="flex flex-col">
                            <div class="flex justify-between items-end">
                                <span class="text-xs font-bold uppercase tracking-wider text-gray-500">
                                    Ваше замовлення
                                </span>
                                <span class="text-sm text-gray-900 font-medium">
                                    {{ $this->cartItems->count() }} тов.
                                </span>
                            </div>

                            <div class="flex flex-col divide-y divide-zinc-100">
                                @foreach ($this->cartItems as $item)
                                    <div class="py-5 flex gap-5 items-center">
                                        <div class="size-20 bg-zinc-50 rounded-md overflow-hidden shrink-0">
                                            <img src="{{ Vite::asset('resources/images/header.png') }}"
                                                class="size-full object-cover" alt="">
                                        </div>

                                        <div class="flex flex-col grow">
                                            <span class="font-medium text-gray-900 leading-tight line-clamp-1">
                                                {{ $item->name }}
                                            </span>
                                            <span class="text-sm text-gray-500 mt-1">
                                                {{ number_format($item->price, 0, '.', ' ') }} грн
                                            </span>

                                            <!-- Керування кількісqтю -->
                                            <div class="flex items-center gap-2.5 mt-1.5">
                                                <x-button color="light" size="sm" icon class="rounded-full!"
                                                    wire:click="decrement({{ $item->id }})">
                                                    <x-lucide-minus class="size-3" />
                                                </x-button>
                                                <span class="text-sm font-semibold">{{ $item->qty }}</span>
                                                <x-button color="light" size="sm" icon class="rounded-full!"
                                                    wire:click="increment({{ $item->id }})">
                                                    <x-lucide-plus class="size-3" />
                                                </x-button>
                                            </div>
                                        </div>

                                        <button wire:click="remove({{ $item->id }})">
                                            <x-lucide-trash-2 class="size-5 stroke-red-500" stroke-width="1.5" />
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Нижня частина з підсумком -->
                @if ($this->cartItems->isNotEmpty())
                    <div class="pt-5 border-t border-zinc-100 mt-auto">
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-gray-500">Разом до сплати:</span>
                            <span class="text-2xl font-bold text-gray-900">
                                {{ number_format($this->total, 0, '.', ' ') }} грн
                            </span>
                        </div>

                        <div class="flex flex-col gap-3">
                            <a href="{{ route('order') }}"
                                class="bg-black hover:bg-gray-900 text-white py-3.5 px-5 text-sm inline-flex items-center justify-center rounded-md font-medium w-full shadow-lg"
                                wire:navigate>
                                Оформити замовлення
                            </a>
                            <button wire:click="open = null" class="text-sm text-gray-500 font-medium py-2.5">
                                Продовжити покупки
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            <div wire:show="open === 'menu'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-8"
                class="p-5 flex flex-col items-center justify-between size-full absolute inset-0">
                <x-nav class="h-full">
                    <x-nav.item label="Головна" url="home" icon="home" />
                    <x-nav.item label="Про мене" url="about" icon="user-round" />
                    <x-nav.item label="Товари" url="products" icon="package" />
                    <x-nav.item label="Галерея" url="gallery" icon="images" />
                    <x-nav.item label="Блог" url="blog" icon="newspaper" />
                    <x-nav.item label="Контакти" url="contacts" icon="notebook-text" />
                </x-nav>
                <div class="flex gap-2.5 mt-auto mx-auto py-5">
                    <x-lucide-instagram class="size-5.5" />
                    <x-lucide-facebook class="size-5.5" />
                    <x-lucide-youtube class="size-5.5" />
                </div>
            </div>
        </div>
    </div>

    <div class="shrink-0 flex items-center justify-between gap-2.5"
        :class="{
            'flex flex-row h-16 w-full px-4': $wire.mode === 'top',
            'flex flex-row lg:flex-col w-20 h-full py-5': $wire.mode === 'left'
        }">
        <div class="order-1 flex-none"
            :class="{ 'me-auto': $wire.mode === 'top', 'mb-auto': $wire.mode === 'left' }">
            @unless (Route::is('home'))
                <a href="{{ route('home') }}" wire:navigate>
                    <img src="{{ Vite::asset('resources/images/logo_light.svg') }}" class="size-20"
                        :class="{ 'hidden': $wire.open !== null }" alt="">
                </a>
                <a href="{{ route('home') }}" wire:navigate>
                    <img src="{{ Vite::asset('resources/images/logo_dark.svg') }}" class="size-20"
                        :class="{ 'hidden': $wire.open === null }" alt="">
                </a>
            @endunless
        </div>

        <x-nav class="lg:order-1 mx-auto" x-cloak
            x-bind:class="$wire.mode === 'left' ? 'hidden' : 'hidden lg:flex'">
            <x-nav.item label="Головна" url="home" icon="home"
                x-bind:class="$wire.open ? 'lg:text-gray-800' : 'lg:text-zinc-100'" />
            <x-nav.item label="Про мене" url="about" icon="user-round"
                x-bind:class="$wire.open ? 'lg:text-gray-800' : 'lg:text-zinc-100'" />
            <x-nav.item label="Товари" url="products" icon="package"
                x-bind:class="$wire.open ? 'lg:text-gray-800' : 'lg:text-zinc-100'" />
            <x-nav.item label="Галерея" url="gallery" icon="images"
                x-bind:class="$wire.open ? 'lg:text-gray-800' : 'lg:text-zinc-100'" />
            <x-nav.item label="Блог" url="blog" icon="newspaper"
                x-bind:class="$wire.open ? 'lg:text-gray-800' : 'lg:text-zinc-100'" />
            <x-nav.item label="Контакти" url="contacts" icon="notebook-text"
                x-bind:class="$wire.open ? 'lg:text-gray-800' : 'lg:text-zinc-100'" />
        </x-nav>

        <button wire:click="open = 'search'" type="button"
            class="order-2 relative rounded-md p-1.5 cursor-pointer transition-colors duration-500"
            x-bind:class="{
                'msauto': $wire.mode === 'top',
                'text-zinc-50': $wire.open === null,
                'text-black bg-zinc-100': $wire.open === 'search',
                'bg-transparent text-zinc-700': $wire.open !== null && $wire.open !== 'search',
            }">
            <x-lucide-search class="size-6" />

            <!-- Індикатор активного пошуку -->
            @if ($search)
                <span class="absolute top-0 right-0 flex size-2">
                    <span
                        class="absolute inline-flex size-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex size-2 rounded-full bg-red-500"></span>
                </span>
            @endif
        </button>

        <button wire:click="open = 'cart'" type="button"
            class="order-3 relative rounded-md p-1.5 cursor-pointer transition-colors duration-500"
            x-bind:class="{
                'text-zinc-50': $wire.open === null,
                'text-black bg-zinc-100': $wire.open === 'cart',
                'bg-transparent text-zinc-700': $wire.open !== null && $wire.open !== 'cart',
            }">
            <x-lucide-shopping-cart class="size-6" />

            <!-- Індикатор кількості (показується лише якщо кошик не порожній) -->
            @if ($this->cartItems->isNotEmpty())
                <span
                    class="absolute -top-1.5 -right-1.5 flex size-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white shadow-sm">
                    {{ $this->cartItems->sum('qty') }}
                </span>
            @endif
        </button>

        <button wire:click="open = 'menu'" type="button"
            class="order-4 lg:hidden relative rounded-md p-1.5 transition-colors duration-500"
            x-bind:class="{
                'text-zinc-50': $wire.open === null,
                'text-black bg-zinc-100': $wire.open === 'menu',
                'bg-transparent text-zinc-700': $wire.open !== null && $wire.open !== 'menu',
            }">
            <x-lucide-menu class="size-6" />
        </button>
    </div>
</div> --}}
