<?php

use function Laravel\Folio\name;
name('home');
?>

@section('header')
    <header class="relative h-screen w-full bg-cover bg-center bg-no-repeat"
        style="background-image: url('{{ Vite::asset('resources/images/header.png') }}')">

        {{-- затемнення --}}
        <div class="absolute inset-0 bg-black/40"></div>

        {{-- контент поверх --}}
        <div class="relative z-10 flex flex-col items-center justify-center h-full">
            <img src="{{ Vite::asset('resources/images/logo_light.svg') }}" class="size-50 lg:size-70 drop-shadow-xl"
                alt="">

            <h1
                class="text-zinc-100 text-4xl md:text-6xl font-bold uppercase text-center max-w-lg font-[Russo_One] drop-shadow-xl">
                Продаж та <br> замовлення <br> <span class="text-orange-500/90">ножів</span>
            </h1>

            <x-marquee :items="['Тактичні', 'Кухонні', 'Для полювання', 'На кожен день', 'Для походів']" />
        </div>

        <a href="#main-section" class="absolute bottom-5 left-1/2 -translate-x-1/2 animate-bounce">
            <img src="{{ Vite::asset('resources/images/header-knife.svg') }}" class="size-12 -rotate-32" alt="">
        </a>
    </header>
@endsection

<x-layouts::app>
    <section class="grid lg:grid-cols-2 mt-20">
        <div class="bg-zinc-800"></div>

        <div class="grid lg:grid-cols-2 lg:col-span2">
            @foreach (App\Enums\ProductCategory::cases() as $category)
                <div class="relative h-80 lg:h-110 p-6 lg:p-8 bg-center bg-cover bg-no-repeat flex flex-col justify-between first:col-span-full overflow-hidden group"
                    style="background-image: url('{{ Vite::asset('resources/images/' . $category->images()) }}')">
                    {{-- затемнення знизу → прозоро зверху (старт з 5%) --}}
                    <div class="absolute inset-0 bg-linear-to-t from-black/75 from-20% via-black/30 to-transparent">
                    </div>

                    {{-- текст --}}
                    <div class="relative mt-auto z-10 text-white text-2xl lg:text-3xl font-[Oswald] font-semibold">
                        {{ $category->label() }}
                    </div>

                    {{-- опис --}}
                    <div class="relative z-10 text-white font-[SN_Pro] leading-5 mt-2.5 max-w-xs">
                        {{ $category->description() }}
                    </div>

                    <a href="{{ $category->url() }}"
                        class="font-medium text-orange-500 mt-2.5 gap-1.5 w-fit flex justify-center items-center relative z-10"
                        wire:navigate>
                        Переглянути
                        <x-lucide-arrow-right
                            class="size-4 group-hover:translate-x-1.5 transition-transform duration-250" />
                    </a>
                </div>
            @endforeach
        </div>
    </section>
</x-layouts::app>
