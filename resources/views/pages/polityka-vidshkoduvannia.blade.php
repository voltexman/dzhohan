<?php
use function Laravel\Folio\name;
name('polityka-vidshkoduvannia');
?>

@section('header')
    <x-header :image="Vite::asset('resources/images/header.png')">
        <x-slot:title>Політика<br>відшкодування</x-slot:title>
    </x-header>
@endsection

<x-layouts::app>
    <x-section sidebar-position="right">
        <div class="prose prose-lg max-w-none">
            <p class="text-gray-600 mb-8">Я, Джоган Костянтин, майстер з виготовлення ножів ручної роботи, хочу,
                щоб ви були повністю задоволені моєю роботою. Нижче я детально описую, в яких випадках ви можете
                повернути товар і отримати відшкодування.</p>

            <h2 class="text-2xl font-semibold mt-12 mb-4">1. Повернення товару належної якості</h2>
            <p>Згідно зі ст. 9 Закону України «Про захист прав споживачів» ви маєте право протягом <strong>14
                    календарних днів</strong> з дня отримання ножа (не рахуючи день покупки) повернути товар,
                якщо він вам не підійшов за формою, розміром, кольором, дизайном або з будь-яких інших
                суб’єктивних причин.</p>
            <p class="mt-4"><strong>Важливі умови:</strong></p>
            <ul class="list-disc pl-6 space-y-2">
                <li>Ніж не повинен мати слідів використання;</li>
                <li>Збережений товарний вигляд, оригінальна упаковка, бирки та пломби;</li>
                <li>Збережений розрахунковий документ (чек або квитанція).</li>
            </ul>
            <p class="text-red-600 font-medium mt-6">Виняток: ножі, які я виготовляю на індивідуальне замовлення
                за вашими параметрами (довжина клинка, матеріал рукояті, гравірування тощо), поверненню не
                підлягають. Це прямо передбачено законом для товарів, зроблених за індивідуальними вимогами
                споживача.</p>

            <h2 class="text-2xl font-semibold mt-12 mb-4">2. Повернення товару неналежної якості</h2>
            <p>Якщо ви виявили прихований дефект, тріщину, шлюб або будь-яку іншу проблему, яка виникла не з
                вашої вини, ви можете:</p>
            <ul class="list-disc pl-6 space-y-2">
                <li>Попросити мене безкоштовно усунути недолік;</li>
                <li>Обміняти ніж на інший;</li>
                <li>Зменшити ціну;</li>
                <li>Повернути товар і отримати повне відшкодування коштів.</li>
            </ul>
            <p>Я розглядаю таку претензію протягом 14 днів з моменту отримання товару.</p>

            <h2 class="text-2xl font-semibold mt-12 mb-4">3. Як повернути товар</h2>
            <ol class="list-decimal pl-6 space-y-4">
                <li>Напишіть мені на email або через форму зворотного зв’язку на сайті;</li>
                <li>Вкажіть причину повернення та номер замовлення;</li>
                <li>Я надішлю вам інструкцію та адресу для відправки;</li>
                <li>Відправте ніж Новою Поштою (або іншою службою) у тому вигляді, в якому отримали;</li>
                <li>Після отримання я перевірю товар і протягом 30 днів поверну кошти.</li>
            </ol>
            <p>Витрати на доставку товару назад до мене зазвичай покриває покупець (якщо інше не було попередньо
                погоджено).</p>

            <h2 class="text-2xl font-semibold mt-12 mb-4">4. Повернення коштів</h2>
            <p>Кошти повертаються на ту саму банківську картку або рахунок, з якого ви оплачували замовлення.
                Термін — до 30 календарних днів з моменту отримання мною товару та вашої заяви.</p>

            <div class="bg-amber-50 border border-amber-200 p-8 rounded-2xl mt-12">
                <p class="text-amber-800 font-medium">Я завжди намагаюся вирішувати всі питання мирно і в
                    інтересах клієнта. Якщо у вас є сумніви — краще напишіть мені до покупки, і ми все
                    обговоримо.</p>
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
