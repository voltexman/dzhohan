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
        <p class="text-gray-700 text-lg mb-6">
            Ми в <strong>{{ env('APP_NAME') }}</strong> прагнемо забезпечити повне задоволення від покупки ножів
            будь-якого типу: тактичних, туристичних, кухонних або для щоденного використання.
            Якщо ваш товар не відповідає очікуванням або має дефекти, ми пропонуємо прозору та чесну політику
            відшкодування.
        </p>

        <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">1. Умови повернення</h2>
        <ul class="list-disc list-inside text-gray-700 space-y-2">
            <li>Товари можна повернути протягом <strong>14 днів</strong> після отримання.</li>
            <li>Продукт повинен бути в оригінальному стані та упаковці, з усіма етикетками та інструкціями.</li>
            <li>Ножі, виготовлені на замовлення або кастомні, не підлягають поверненню, якщо інше не погоджено
                заздалегідь.</li>
            <li>Товари, що використовувалися неналежним чином або пошкоджені після отримання, не приймаються.</li>
        </ul>

        <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">2. Процедура повернення</h2>
        <ol class="list-decimal list-inside text-gray-700 space-y-2">
            <li>Зв’яжіться з нашою службою підтримки через електронну пошту
                <strong>{{ env('ADMIN_EMAIL') }}</strong> або телефон <strong>+380 (63) 951 88 42</strong>, щоб
                повідомити про повернення.
            </li>
            <li>Отримайте від нашого менеджера інструкції щодо відправки товару назад.</li>
            <li>Упакуйте товар у оригінальну упаковку та надішліть на адресу, яку надасть менеджер.</li>
        </ol>

        <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">3. Відшкодування коштів</h2>
        <p class="text-gray-700 mb-4">
            Після отримання та перевірки товару, ми обробимо повернення коштів протягом <strong>5–10 робочих
                днів</strong> на той самий спосіб оплати, який ви використовували при покупці.
        </p>
        <p class="text-gray-700 mb-4">
            Вартість доставки при поверненні оплачується покупцем, якщо тільки товар не має виробничого дефекту.
        </p>

        <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">4. Відшкодування за дефекти</h2>
        <p class="text-gray-700 mb-4">
            Якщо ножі мають дефекти матеріалів або виробництва, ми забезпечуємо повне відшкодування або обмін без
            додаткових витрат.
            Важливо надіслати фотографії дефекту разом із заявкою на повернення для прискорення обробки.
        </p>

        <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">5. Контактна інформація</h2>
        <p class="text-gray-700 mb-2">
            Усі питання щодо повернення та відшкодування можна надсилати на електронну пошту:
            <strong>{{ env('ADMIN_EMAIL') }}</strong>
        </p>
        <p class="text-gray-700">
            Або зателефонувати за номером: <strong>+380 (63) 951 88 42</strong>.
            Ми завжди готові допомогти та зробити процес повернення максимально зручним.
        </p>

        <x-slot:sidebar>
            <x-nav.sidebar class="lg:sticky lg:top-25 lg:h-screen">
                <x-nav.sidebar.item route="polityka-vidshkoduvannia">
                    <x-lucide-wallet class="size-5" />
                    Політика відшкодування
                </x-nav.sidebar.item>

                <x-nav.sidebar.item route="polityka-dostavky">
                    <x-lucide-truck class="size-5" />
                    Політика доставки
                </x-nav.sidebar.item>

                <x-nav.sidebar.item route="polityka-konfidentsiinosti">
                    <x-lucide-shield-check class="size-5" />
                    Політика конфіденційності
                </x-nav.sidebar.item>

                <x-nav.sidebar.item route="umovy-vykorystannia">
                    <x-lucide-file-text class="size-5" />
                    Умови використання
                </x-nav.sidebar.item>
            </x-nav.sidebar>
        </x-slot:sidebar>
    </x-section>
</x-layouts::app>
