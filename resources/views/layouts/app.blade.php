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
    <livewire:navigation mode="top" />

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
