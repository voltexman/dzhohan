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
        class="fixed z-50 top-0 text-white w-full lg:w-20 h-16 lg:h-screen flex flex-row lg:flex-col items-center justify-between px-4 lg:py-4 transition-colors duration-500"
        :class="scrolled ? 'bg-black/90 backdrop-blur-xs shadow-lg text-white' : 'bg-transparent text-white'">
        <div class="flex-none lg:mb-auto">
            @unless (Route::is('home'))
                <a href="{{ route('home') }}" wire:navigate>
                    <img src="{{ Vite::asset('resources/images/logo_light.svg') }}" class="size-20" alt="logo">
                </a>
            @endunless
        </div>

        <div class="flex flex-row lg:flex-col gap-1 lg:mt-auto">
            <livewire:search position="start" />
            <livewire:cart position="start" />
            <livewire:menu position="start" />
        </div>
    </div>

    <main class="lg:grid lg:grid-cols-2">
        @yield('images')

        <div class="">
            {{ $slot }}
        </div>
    </main>

    @livewireScripts
</body>

</html>
