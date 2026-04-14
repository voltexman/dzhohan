<?php

use function Laravel\Folio\name;
name('umovy-vykorystannia');
?>

@section('header')
    <x-header :image="Vite::asset('resources/images/header.png')">
        <x-slot:title>Умови<br>використання</x-slot:title>
    </x-header>
@endsection

<x-layouts::app>
    <x-section sidebar-position="right">
        <div class="prose prose-lg max-w-none">
            <p class="text-gray-600 mb-8">Ласкаво прошу на мій сайт! Я, Джоган Костянтин, майстер з виготовлення ножів
                ручної роботи, радий, що ви тут. Користуючись цим сайтом, ви автоматично погоджуєтесь з цими умовами.
            </p>

            <h2 class="text-2xl font-semibold mt-8 mb-4">Загальні положення</h2>
            <p>Сайт створений для ознайомлення з моїми ножами та оформлення замовлень. Весь контент (фотографії, тексти,
                відео, описи) є моєю інтелектуальною власністю і захищений авторським правом. Копіювати, поширювати чи
                використовувати матеріали без моєї письмової згоди заборонено.</p>

            <h2 class="text-2xl font-semibold mt-12 mb-4">Оформлення замовлення</h2>
            <p>Коли ви оформлюєте замовлення на сайті — це є вашою згодою (акцептом) з публічною офертою. Договір
                вважається укладеним з моменту підтвердження замовлення мною.</p>

            <h2 class="text-2xl font-semibold mt-12 mb-4">Ціни та оплата</h2>
            <p>Ціни на сайті вказані в гривнях. Я залишаю за собою право змінювати ціни без попередження, але для вже
                оформлених і оплачених замовлень ціна не змінюється.</p>

            <h2 class="text-2xl font-semibold mt-12 mb-4">Обмеження відповідальності</h2>
            <p>Я докладаю максимум зусиль, щоб інформація на сайті була точною, але не можу гарантувати 100% відсутність
                помилок. Я не несу відповідальності за тимчасові технічні збої сайту, а також за затримки доставки,
                спричинені службами перевізників.</p>

            <h2 class="text-2xl font-semibold mt-12 mb-4">Заборонені дії</h2>
            <p>Забороняється використовувати сайт для незаконних цілей, розміщувати спам, шкідливе ПЗ чи будь-яку
                інформацію, що порушує права інших осіб.</p>

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
