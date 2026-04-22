<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="/favicons/favicon.ico">

    <link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png">

    <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">

    <link rel="icon" type="image/png" sizes="192x192" href="/favicons/android-chrome-192x192.png">
    <link rel="icon" type="image/png" sizes="512x512" href="/favicons/android-chrome-512x512.png">

    <link rel="manifest" href="/favicons/site.webmanifest">

    <link rel="mask-icon" href="/favicons/safari-pinned-tab.svg" color="#000000">

    <meta name="theme-color" content="#ffffff">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Oswald:wght@200..700&family=Russo+One&family=SN+Pro:ital,wght@0,200..900;1,200..900&display=swap"
        rel="stylesheet">

    <title>{{ config('app.name') . ' - ' . ($title ?? config('app.name')) }}</title>

    <meta name="description"
        content="{{ $description ?? 'Авторські ножі ручної роботи. Індивідуальне виготовлення, висока якість та унікальний дизайн.' }}">
    <meta name="robots" content="noindex, nofollow">

    {{-- Canonical --}}
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph (Facebook, LinkedIn, etc.) --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title ?? config('app.name') }}">
    <meta property="og:description" content="{{ $description ?? '' }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $og_image ?? Vite::asset('resources/images/header.png') }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? config('app.name') }}">
    <meta name="twitter:description" content="{{ $description ?? '' }}">
    <meta name="twitter:image" content="{{ $og_image ?? Vite::asset('resources/images/header.png') }}">

    {{-- Favicon --}}
    <link rel="icon" href="/favicons/favicon.ico" sizes="any">
    <link rel="icon" type="image/svg+xml" href="/favicons/favicon.svg">
    <link rel="apple-touch-icon" href="/favicons/apple-touch-icon.png">

    {{-- Theme --}}
    <meta name="theme-color" content="#000000">

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
            @else
                <div class="flex lg:hidden items-center font-[Oswald] text-lg text-zinc-50 me-auto">
                    {{ $settings->phone }}
                </div>
            @endunless
        </div>

        <x-nav class="hidden lg:flex lg:mx-auto" x-cloak>
            <x-nav.item label="Головна" url="home" icon="home" />
            <x-nav.item label="Ножі" url="knives" icon="package" />
            <x-nav.item label="Матеріали" url="materials" icon="package" />
            <x-nav.item label="Галерея" url="gallery" icon="images" />
            <x-nav.item label="Блог" url="blog" icon="newspaper" />
            <x-nav.item label="Замовлення" url="order" icon="package-open" />
            <x-nav.item label="Контакти" url="contacts" icon="notebook-text" />
        </x-nav>

        <div class="flex gap-1 ms-auto">
            <div class="hidden xl:flex items-center font-[Oswald] text-lg text-zinc-50 me-1.5">
                {{ $settings->phone }}
            </div>

            <livewire:search position="end" />

            @if (!request()->routeIs('checkout'))
                <livewire:cart position="end" />
            @endif

            <livewire:menu position="end" />
        </div>
    </div>

    @yield('header')

    <main class="" id="main-section">
        {{ $slot }}
    </main>

    <footer class="flex flex-col border-t border-zinc-200"
        style="background-image: linear-gradient(to top, rgb(250 250 250), rgb(228 228 231 / 0.6)), url('{{ Vite::asset('resources/images/bg-footer.svg') }}'); background-size: cover, cover; background-repeat: no-repeat, repeat;">
        <div
            class="max-w-5xl xl:max-w-6xl mx-auto px-5 py-10 lg:py-20 lg:px-0 flex flex-col lg:flex-row gap-y-7.5 items-center justify-between w-full">

            {{-- СОЦІАЛЬНІ МЕРЕЖІ --}}
            <div class="flex flex-col items-center lg:items-start order-3 lg:order-1">
                <div class="text-2xl font-[Oswald] text-gray-700">
                    {{ $settings->phone }}
                </div>
                <div class="text-xl tracking-wide font-[Oswald] text-gray-700">
                    {{ $settings->email }}
                </div>
                <div class="flex justify-center items-center mt-5 gap-1.5">
                    @isset($settings->socials['instagram'])
                        <a href="https://www.instagram.com/dzhohan_knives" target="_blank">
                            <img src="{{ Vite::asset('resources/images/icons/socials/instagram.svg') }}"
                                class="size-7 opacity-70 hover:opacity-100 transition-opacity duration-250 drop-shadow-xl"
                                alt="" />
                        </a>
                    @endisset

                    @isset($settings->socials['facebook'])
                        <a href="https://www.facebook.com/KostyantynDzhohun" target="_blank">
                            <img src="{{ Vite::asset('resources/images/icons/socials/facebook.svg') }}"
                                class="size-7 opacity-70 hover:opacity-100 transition-opacity duration-250 drop-shadow-xl"
                                alt="" />
                        </a>
                    @endisset

                    @isset($settings->socials['pinterest'])
                        <a href="https://www.facebook.com/KostyantynDzhohun" target="_blank">
                            <img src="{{ Vite::asset('resources/images/icons/socials/pinterest.svg') }}"
                                class="size-7 opacity-70 hover:opacity-100 transition-opacity duration-250 drop-shadow-xl"
                                alt="" />
                        </a>
                    @endisset

                    @isset($settings->socials['viber'])
                        <a href="https://www.facebook.com/KostyantynDzhohun" target="_blank">
                            <img src="{{ Vite::asset('resources/images/icons/socials/viber.svg') }}"
                                class="size-7 opacity-70 hover:opacity-100 transition-opacity duration-250 drop-shadow-xl"
                                alt="" />
                        </a>
                    @endisset

                    @isset($settings->socials['telegram'])
                        <a href="https://www.facebook.com/KostyantynDzhohun" target="_blank">
                            <img src="{{ Vite::asset('resources/images/icons/socials/telegram.svg') }}"
                                class="size-7 opacity-70 hover:opacity-100 transition-opacity duration-250 drop-shadow-xl"
                                alt="" />
                        </a>
                    @endisset

                    @isset($settings->socials['whatsapp'])
                        <a href="https://www.facebook.com/KostyantynDzhohun" target="_blank">
                            <img src="{{ Vite::asset('resources/images/icons/socials/whatsapp.svg') }}"
                                class="size-7 opacity-70 hover:opacity-100 transition-opacity duration-250 drop-shadow-xl"
                                alt="" />
                        </a>
                    @endisset
                </div>
            </div>

            {{-- МЕНЮ --}}
            <x-nav.footer class="order-2 items-center lg:items-start">
                <x-nav.footer.item route="polityka-vidshkoduvannia">
                    <x-lucide-wallet class="size-4" />
                    Політика відшкодування
                </x-nav.footer.item>

                <x-nav.footer.item route="polityka-konfidentsiinosti">
                    <x-lucide-shield-check class="size-4" />
                    Політика конфіденційності
                </x-nav.footer.item>

                <x-nav.footer.item route="umovy-vykorystannia">
                    <x-lucide-file-text class="size-4 " />
                    Умови використання
                </x-nav.footer.item>

                <x-nav.footer.item route="polityka-dostavky">
                    <x-lucide-truck class="size-4" />
                    Політика доставки
                </x-nav.footer.item>
            </x-nav.footer>

            {{-- ПІДПИСКА --}}
            <div class="flex flex-col items-center lg:items-start order-1 lg:order-3 w-full max-w-md">
                <h2 class="font-[Oswald] text-3xl font-semibold text-gray-700">
                    Підписка
                </h2>
                <div class="max-w-sm text-sm text-zinc-500 mt-2.5 text-center lg:text-left">
                    Дізнавайтесь про нові товари та їхні огляди, отримуйте вигідні та спецільні пропозиції.
                </div>
                <div class="mt-5 w-full">
                    <livewire:subscriber />
                </div>
            </div>
        </div>

        <div class="flex justify-center items-center py-2.5 border-t bg-zinc-100 border-zinc-200/40 h-10">
            <span class="text-xs text-gray-400">&copy;</span>
            <span class="text-xs text-gray-400">{{ now()->format('Y') }}&nbsp;</span>
            <span class="text-xs text-gray-500">{{ env('APP_NAME') }}.&nbsp;</span>
            <span class="text-xs text-gray-400">Всі права застережено.&nbsp;</span><br>
            <span class="text-xs text-gray-400">
                Розробка <a href="https://portolio-beta-rose.vercel.app/" class="text-orange-400"
                    target="_blank">LEV</a>
            </span>
        </div>
    </footer>

    @livewireScripts
</body>

</html>
