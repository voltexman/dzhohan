<?php

use App\Enums\ProductCategory;
use function Laravel\Folio\name;
name('home');
?>

<x-slot name="title">
    Авторські ножі ручної роботи — майстер ножів | Купити ніж в Україні
</x-slot>
<x-slot name="description">
    Виготовлення авторських ножів ручної роботи на замовлення. Унікальний дизайн, якісна сталь, доставка по Україні.
    Оберіть свій ідеальний ніж.
</x-slot>

@section('header')
    <header class="relative h-screen w-full bg-cover bg-center bg-no-repeat animate-ricochet lg:animate-none"
        style="background-image: url('{{ Vite::asset('resources/images/header.png') }}')">

        {{-- затемнення --}}
        <div class="absolute inset-0 bg-black/40"></div>

        {{-- контент поверх --}}
        <div class="relative z-10 flex flex-col items-center justify-center h-full">
            <img src="{{ Vite::asset('resources/images/logo_light.svg') }}"
                class="size-50 lg:size-70 drop-shadow-xl logo will-change-transform z-1000!" alt="">

            <h1
                class="text-zinc-100 text-4xl md:text-6xl font-bold uppercase text-center max-w-lg font-[Russo_One] drop-shadow-xl">
                Продаж та <br> замовлення <br> <span class="text-orange-500/90">ножів</span>
            </h1>

            <div
                class="flex gap-1.5 mt-5 text-white/70 flex-wrap mx-auto justify-center items-center max-w-3xs w-full leading-4">
                <span class="text-xs">Тактичні</span>
                <div class="size-1.5 rounded-full bg-white flex-none"></div>
                <span class="text-xs">Кухонні</span>
                <div class="size-1.5 rounded-full bg-white flex-none"></div>
                <span class="text-xs">Мисливські</span><br>
                {{-- <div class="size-1.5 rounded-full bg-white flex-none"></div> --}}
                <span class="text-xs">Щоденні</span>
                <div class="size-1.5 rounded-full bg-white flex-none"></div>
                <span class="text-xs">Туристичні</span>
            </div>
        </div>

        <a href="#about-me" class="absolute z-10 bottom-5 left-1/2 -translate-x-1/2 animate-bounce">
            <img src="{{ Vite::asset('resources/images/header-knife.svg') }}" class="size-12 -rotate-32" alt="">
        </a>
    </header>
@endsection

<x-layouts::app>
    <section class="bg-zinc-50 py-20 px-5 lg:px-0 scroll-mt-15" id="about-me">
        <div class="max-w-5xl mx-auto grid lg:grid-cols-2 gap-5 lg:gap-10">
            <div class="overflow-hidden">
                <img src="{{ Vite::asset('resources/images/i-am.jpg') }}"
                    class="size-full object-cover rounded-sm border-5 border-zinc-200/90" alt="">
            </div>

            <div class="relative space-y-5">
                <img src="{{ Vite::asset('resources/images/knives-bg.svg') }}"
                    class="size-full absolute right-0 bottom-0 z-0 opacity-4" alt="">
                <h2 class="relative z-10 font-[Russo_One] text-zinc-950 text-3xl lg:text-5xl">
                    Хобі, яке стало ремеслом
                </h2>
                <div class="h-1 w-20 bg-orange-400 relative z-10"></div>

                <p class="max-w-md block text-gray-700 relative z-10">
                    Моє знайомство з ножами почалося як захоплення, але з часом переросло у справу життя. Я постійно
                    вдосконалюю свої навички, експериментую з формами, матеріалами та технологіями, щоб створювати
                    вироби, які поєднують у собі практичність, надійність і естетику. Кожен ніж — це результат уважної
                    роботи, досвіду та бажання зробити щось справді варте уваги.
                </p>

                <ul class="space-y-2.5 lg:space-y-5 text-gray-700 relative z-10">
                    <li class="flex items-start gap-2.5">
                        <span class="mt-2 h-2 w-2 rounded-full shrink-0 bg-orange-500"></span>
                        <span>Створюю ножі високої якості, з увагою навіть до дрібних деталей.</span>
                    </li>

                    <li class="flex items-start gap-2.5">
                        <span class="mt-2 h-2 w-2 rounded-full shrink-0 bg-orange-500"></span>
                        <span>Пропоную широкий вибір ножів — від класики до нестандартних моделей на замовлення.</span>
                    </li>

                    <li class="flex items-start gap-2.5">
                        <span class="mt-2 h-2 w-2 rounded-full shrink-0 bg-orange-500"></span>
                        <span>Використовую лише найкращі матеріали від перевірених постачальників.</span>
                    </li>

                    <li class="flex items-start gap-2.5">
                        <span class="mt-2 h-2 w-2 rounded-full shrink-0 bg-orange-500"></span>
                        <span>Поєдную традиційне ремесло і сучасні технології, щоб кожен ніж був унікальним.</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 max-w-5xl w-full mx-auto mt-5 lg:mt-15 gap-5 lg:gap-10">
            <div class="space-y-5">
                <div class="font-[Russo_One] text-4xl lg:text-5xl text-orange-500">12+</div>
                <div class="h-1 w-16 bg-orange-500"></div>
                <div class="text-gray-700 font-normal">Років праці, старань, навчання та експериментів</div>
            </div>
            <div class="space-y-5">
                <div class="font-[Russo_One] text-4xl lg:text-5xl text-orange-500">300+</div>
                <div class="h-1 w-16 bg-orange-500"></div>
                <div class="text-gray-700 font-normal">Задоволених клієнтів, які користуються моїми роботами</div>
            </div>
            <div class="space-y-5">
                <div class="font-[Russo_One] text-4xl lg:text-5xl text-orange-500">1000+</div>
                <div class="h-1 w-16 bg-orange-500"></div>
                <div class="text-gray-700 font-normal">Унікальних та якісно виготовлених товарів різних категорій
                </div>
            </div>
        </div>
    </section>

    <section class="bg-zinc-100 py-20 px-5 lg:px-0">
        <div class="max-w-5xl mx-auto">
            <h2 class="font-[Russo_One] text-3xl text-center text-gray-900">Мої колекції</h2>
            <div class="my-2.5 mx-auto w-22 h-1 bg-orange-500"></div>

            <div class="max-w-sm mx-auto text-center text-gray-700 text-sm">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero quam nulla fuga optio.
            </div>

            <div
                class="max-w-6xl mx-auto grid grid-cols-2 lg:grid-cols-3 gap-2.5 lg:gap-5 auto-rows-[minmax(180px,auto)] mt-10">
                @each('partials.product.main-page-collections', ProductCategory::cases(), 'collection')
            </div>
        </div>
    </section>

    {{-- <section class="bg-zinc-100 py-20">
        <div class="max-w-5xl mx-auto">
            <h2 class="font-[Russo_One] text-3xl text-center text-gray-900">Мої колекції</h2>
            <div class="my-2.5 mx-auto w-22 h-1 bg-orange-500"></div>
            <div class="max-w-sm mx-auto text-center text-gray-700 text-sm">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero quam nulla fuga optio.
            </div>

            <div class="max-w-6xl mx-auto grid grid-cols-2 lg:grid-cols-3 gap-5 auto-rows-[minmax(180px,auto)] mt-10">
                @each('partials.product.main-page-collections', ProductCategory::cases(), 'collection')
            </div>
        </div>
    </section> --}}

    <section class="bg-orange-500 py-20 px-5 lg:px-0"
        style="background-image: url('{{ Vite::asset('resources/images/bg-manufacture.svg') }}'); background-size: cover, cover; background-repeat: no-repeat;">
        <div class="max-w-5xl mx-auto grid lg:grid-cols-[2fr_1fr_1fr] items-center gap-y-10 gap-x-2.5">

            <!-- Текст -->
            <div class="text-2xl lg:text-4xl text-center lg:text-start font-semibold font-[Oswald] text-white">
                Якщо у вас є власна ідея, ескіз або особливі побажання — звертайтесь.
                Я допоможу втілити вашу задумку в унікальний ніж.
            </div>

            <!-- Ніж -->
            <div class="flex justify-center">
                <img src="{{ Vite::asset('resources/images/knive-2-bg-light.svg') }}" class="w-40" alt="">
            </div>

            <!-- Контакти -->
            <div class="flex flex-col items-center lg:items-start space-y-2.5 lg:space-y-5">
                <div class="font-[Russo_One] text-2xl text-white leading-none">
                    +380 (63) 951 88 42
                </div>
                <div class="font-[Russo_One] text-xl text-white leading-none">
                    dzhogun@gmail.com
                </div>

                <a href="#"
                    class="mt-5 font-[Oswald] text-lg font-semibold flex justify-center px-5 py-3.5 bg-white text-gray-900">
                    Замовити виготовлення
                </a>
            </div>

        </div>
    </section>

    <section class="bg-zinc-50 py-20 px-5 lg:px-0" x-data="{ open: null }">
        <div class="max-w-5xl mx-auto">
            <h2 class="font-[Russo_One] text-3xl text-center text-gray-900">Часті запитання</h2>
            <div class="my-2.5 mx-auto w-22 h-1 bg-orange-500"></div>
            <div class="max-w-sm mx-auto text-center text-gray-700 text-sm">
                Відповіді на питання, які найчастіше виникають при замовленні або купівлі ножа.
            </div>

            <div class="md:columns-2 gap-x-10 items-start mt-10" x-data="{ open: null }">
                @foreach ($settings->faqs as $faq)
                    <div class="overflow-hidden transition-all duration-300 border-b border-gray-200">
                        <button @click="open === {{ $loop->index }} ? open = null : open = {{ $loop->index }}"
                            class="w-full py-5 text-left flex justify-between items-center outline-none cursor-pointer group">
                            <span
                                class="font-semibold text-gray-900 text-lg transition-colors group-hover:text-amber-700">
                                {{ $faq['question'] }}
                            </span>
                            <span class="ml-4 shrink-0 transition-transform duration-300"
                                :class="open === {{ $loop->index }} ? 'rotate-45' : ''">
                                <x-lucide-plus class="size-6 stroke-gray-500" />
                            </span>
                        </button>
                        <div x-show="open === {{ $loop->index }}" x-collapse x-cloak
                            class="pb-5 text-gray-600 leading-relaxed text-sm">
                            {{ $faq['answer'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-20 relative bg-fixed bg-no-repeat bg-cover bg-center px-5 lg:px-0"
        style="background-image: url('{{ Vite::asset('resources/images/steel-section-bg.png') }}');">

        <!-- Напівпрозорий темний оверлей на всю секцію (опціонально) -->
        <div class="absolute inset-0 bg-black/70"></div>

        <!-- Контентний блок: тепер він має суцільний сірий колір -->
        <div class="relative max-w-2xl mx-auto text-white p-10 md:p-15 bg-zinc-50">

            <!-- Заголовок: bg-fixed створює ефект прозорості крізь літери -->
            <h2 class="text-4xl md:text-7xl font-[Russo_One] mb-5 text-center leading-none tracking-tighter uppercase 
                   bg-fixed bg-no-repeat bg-cover bg-center bg-clip-text text-transparent"
                style="background-image: url('{{ Vite::asset('resources/images/steel-section-bg.png') }}');">
                Сталь для<br>майстрів
            </h2>

            <p class="text-gray-700 max-w-md mx-auto text-center font-light leading-relaxed mb-5">
                Пропоную перевірену сталь та матеріали для створення ножів.
                Від заготовок до професійних абразивів — тільки те, що пройшло
                контроль якості в моїй майстерні.
            </p>

            <!-- Кнопка -->
            <div class="relative w-fit mx-auto group">
                <span class="absolute -inset-1 bg-white/10 rounded-sm animate-ping"></span>
                <a href="#"
                    class="relative flex items-center px-5 py-3.5 bg-zinc-200 text-zinc-900 font-[Oswald] text-lg font-semibold uppercase tracking-wider transition-all hover:bg-orange-500 hover:text-white">
                    В магазин
                    <svg xmlns="http://www.w3.org"
                        class="size-6 shrink-0 ms-3 transition-transform group-hover:translate-x-2" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>

            <!-- Декоративні куточки -->
            <div class="absolute top-4 left-4 w-10 h-10 border-t-2 border-l-2 border-black"></div>
            <div class="absolute bottom-4 right-4 w-10 h-10 border-b-2 border-r-2 border-black"></div>
        </div>
    </section>
</x-layouts::app>

@vite('resources/js/pages/main.js')
