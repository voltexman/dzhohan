<?php

use function Laravel\Folio\name;
name('polityka-dostavky');
?>

@section('header')
    <x-header :image="Vite::asset('resources/images/header.png')">
        <x-slot:title>Політика<br>доставки</x-slot:title>
    </x-header>
@endsection

<x-layouts::app>
    <x-section sidebar-position="right">
        <div class="prose prose-lg max-w-none">
            <p class="text-gray-600 mb-8">Я відправляю ножі по всій Україні і роблю все можливе, щоб ви отримали
                замовлення швидко і в безпеці.</p>

            <h2 class="text-2xl font-semibold mt-8 mb-4">Способи доставки</h2>
            <ul class="list-disc pl-6 space-y-3">
                <li>Основний спосіб — Нова Пошта (поштове відділення або поштомат);</li>
                <li>Самовивіз у м. Вінниця (за попередньою домовленістю);</li>
                <li>Міжнародна доставка — Нова Пошта Global або Укрпошта (митні платежі та податки сплачує отримувач).
                </li>
            </ul>

            <h2 class="text-2xl font-semibold mt-12 mb-4">Строки виготовлення та відправлення</h2>
            <p>Якщо ніж є в наявності — відправляю протягом 1–2 робочих днів.<br>
                Якщо ніж виготовляється на замовлення — строк від 3 до 21 робочого дня (залежить від складності). Я
                завжди повідомляю точний строк перед оплатою.</p>

            <h2 class="text-2xl font-semibold mt-12 mb-4">Строки доставки</h2>
            <p>По Україні — зазвичай 1–3 дні після відправлення (залежить від регіону). Точний строк показує Нова Пошта
                при оформленні.</p>

            <h2 class="text-2xl font-semibold mt-12 mb-4">Вартість доставки</h2>
            <p>Вартість доставки оплачує покупець згідно з тарифами перевізника. Точна сума відображається на етапі
                оформлення замовлення. При замовленні від певної суми (я вкажу на сайті) доставка може бути
                безкоштовною.</p>

            <h2 class="text-2xl font-semibold mt-12 mb-4">Важливі зауваження</h2>
            <ul class="list-disc pl-6 space-y-2">
                <li>Я пакую ножі дуже ретельно, щоб вони приїхали в ідеальному стані;</li>
                <li>Я не несу відповідальності за затримки, спричинені службою доставки;</li>
                <li>Якщо посилка загубиться або пошкодиться з вини перевізника — ми разом розв’яжемо це питання.</li>
            </ul>

            <div class="bg-gray-50 p-8 rounded-2xl mt-12">
                <p class="font-medium">Якщо у вас є особливі побажання щодо доставки — просто напишіть мені, і ми все
                    узгодимо.</p>
            </div>

            <p class="text-sm text-gray-500 mt-16 text-center">Останнє оновлення: 14 квітня 2026 року</p>
        </div>


        <x-slot:sidebar>
            <x-nav.sidebar class="lg:sticky lg:top-25 lg:h-screen">
                <x-nav.sidebar.item route="polityka-vidshkoduvannia">
                    <x-lucide-wallet class="size-4" />
                    Політика відшкодування
                </x-nav.sidebar.item>

                <x-nav.sidebar.item route="polityka-dostavky">
                    <x-lucide-truck class="size-4" />
                    Політика доставки
                </x-nav.sidebar.item>

                <x-nav.sidebar.item route="polityka-konfidentsiinosti">
                    <x-lucide-shield-check class="size-4" />
                    Політика конфіденційності
                </x-nav.sidebar.item>

                <x-nav.sidebar.item route="umovy-vykorystannia">
                    <x-lucide-file-text class="size-4" />
                    Умови використання
                </x-nav.sidebar.item>
            </x-nav.sidebar>
        </x-slot:sidebar>
    </x-section>
</x-layouts::app>
