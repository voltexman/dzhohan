<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Oswald:wght@200..700&family=Russo+One&family=SN+Pro:ital,wght@0,200..900;1,200..900&display=swap"
        rel="stylesheet">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body>
    <div x-data="{ scrolled: false }" @scroll.window="scrolled = window.scrollY > 50" x-init="scrolled = window.scrollY > 50"
        class="fixed z-50 top-0 h-16 text-white w-full flex items-center justify-between px-4 transition-colors duration-500"
        :class="scrolled ? 'bg-black/90 backdrop-blur-xs shadow-lg text-white' : 'bg-transparent text-white'">
        <div class="flex-none me-auto">
            @unless (Route::is('home'))
                <a href="{{ route('home') }}" wire:navigate>
                    <img src="{{ Vite::asset('resources/images/logo_light.svg') }}" class="size-20" alt="logo">
                </a>
            @endunless
        </div>

        <x-nav class="hidden lg:flex lg:mx-auto" x-cloak>
            <x-nav.item label="Головна" url="home" icon="home" />
            <x-nav.item label="Про мене" url="about" icon="user-round" />
            <x-nav.item label="Товари" url="products" icon="package" />
            <x-nav.item label="Галерея" url="gallery" icon="images" />
            <x-nav.item label="Блог" url="blog" icon="newspaper" />
            <x-nav.item label="Контакти" url="contacts" icon="notebook-text" />
        </x-nav>

        <div class="flex gap-1 ms-auto">
            <livewire:search position="end" />
            <livewire:cart position="end" />
        </div>
    </div>

    @yield('header')

    <main class="" id="main-section">
        {{ $slot }}
    </main>

    <footer class="flex flex-col bg-linear-to-t from-zinc-50 to-zinc-200/60 border-t border-zinc-200">
        <div class="px-8 py-10 flex flex-col items-center">
            Subscribe Form
        </div>

        {{-- content --}}
        <div class="px-8 py-20 flex flex-col items-center grow">
            <div class="text-2xl font-[Oswald] text-gray-700 drop-shadow-xl">
                +380 (63) 951 88 42
            </div>
            <div class="text-xl tracking-wide font-[Oswald] text-gray-700 drop-shadow-xl">
                voltexman@gmail.com
            </div>
            <div class="mt-5 grid grid-cols-3 gap-2.5">
                <a href="https://www.instagram.com/dzhohan_knives" target="_blank">
                    <x-lucide-instagram class="size-8 stroke-gray-700 drop-shadow-xl" stroke-width="1.5" />
                </a>
                <a href="https://www.facebook.com/KostyantynDzhohun" target="_blank">
                    <x-lucide-facebook class="size-8 stroke-gray-700 drop-shadow-xl" stroke-width="1.5" />
                </a>
            </div>
        </div>

        <div class="flex justify-center items-center py-2.5 border-t bg-zinc-100 border-zinc-200/40 h-10">
            <span class="text-xs text-gray-700">&copy;</span>
            <span class="text-xs text-gray-700">{{ now()->format('Y') }}&nbsp;</span>
            <span class="text-xs text-gray-700">{{ env('APP_NAME') }}.&nbsp;</span>
            <span class="text-xs text-gray-700">Всі права застережено</span>
        </div>
    </footer>

    @livewireScripts
</body>

</html>
