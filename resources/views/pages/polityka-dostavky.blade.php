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
        <p class="text-gray-700 text-lg mb-6">
            У <strong>{{ env('APP_NAME') }}</strong> ми прагнемо забезпечити своєчасну та безпечну доставку ножів
            будь-якого типу: тактичних, туристичних, кухонних або для щоденного використання.
            Ознайомтеся з нашими правилами та умовами доставки, щоб отримати свій товар без затримок.
        </p>

        <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">1. Терміни доставки</h2>
        <ul class="list-disc list-inside text-gray-700 space-y-2">
            <li>Стандартний термін доставки по Україні складає <strong>2–5 робочих днів</strong>.</li>
            <li>Доставка за межі України може займати <strong>7–14 робочих днів</strong> в залежності від країни.</li>
            <li>Товари на замовлення або кастомні вироби можуть мати додатковий час виготовлення — менеджер уточнить
                термін при підтвердженні замовлення.</li>
        </ul>

        <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">2. Вартість доставки</h2>
        <p class="text-gray-700 mb-4">
            Вартість доставки залежить від обраного способу та місця доставки:
        </p>
        <ul class="list-disc list-inside text-gray-700 space-y-2">
            <li>Стандартна доставка по Україні — <strong>безкоштовно</strong> для замовлень від 2000 грн.</li>
            <li>Для замовлень меншої суми — доставка оплачується згідно тарифів кур’єрської служби.</li>
            <li>Доставка за кордон — вартість розраховується індивідуально та додається при оформленні замовлення.</li>
        </ul>

        <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">3. Способи доставки</h2>
        <ul class="list-disc list-inside text-gray-700 space-y-2">
            <li>Кур’єрська доставка службою “Нова Пошта” або обраною вами кур’єрською компанією.</li>
            <li>Самовивіз з нашого складу (за попереднім погодженням).</li>
            <li>Доставка замовлень за межі України здійснюється міжнародними кур’єрськими службами.</li>
        </ul>

        <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">4. Відповідальність за товар під час доставки</h2>
        <p class="text-gray-700 mb-4">
            Ми гарантуємо належне пакування товару для уникнення пошкоджень під час транспортування.
            Всі ножі пакуються в захисну упаковку та коробку, яка запобігає пошкодженням.
            При отриманні обов’язково перевіряйте товар на наявність пошкоджень та повідомляйте кур’єра або нашу службу
            підтримки у разі виявлення проблем.
        </p>

        <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">5. Відстеження замовлення</h2>
        <p class="text-gray-700 mb-4">
            Після відправки ви отримаєте номер відстеження, за допомогою якого можна перевіряти статус доставки на сайті
            кур’єрської служби.
            У разі затримки або проблем з доставкою наша служба підтримки допоможе вирішити питання.
        </p>

        <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">6. Контактна інформація</h2>
        <p class="text-gray-700 mb-2">
            Якщо у вас є питання щодо доставки, звертайтеся на електронну пошту:
            <strong>{{ env('ADMIN_EMAIL') }}</strong>
        </p>
        <p class="text-gray-700 mb-4">
            Або телефонуйте за номером: <strong>+380 (63) 951 88 42</strong>.
            Ми допоможемо вибрати оптимальний спосіб доставки та відповімо на всі ваші запитання.
        </p>

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
