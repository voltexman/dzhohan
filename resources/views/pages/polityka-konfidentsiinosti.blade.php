<?php

use function Laravel\Folio\name;
name('polityka-konfidentsiinosti');
?>

@section('header')
    <x-header :image="Vite::asset('resources/images/header.png')">
        <x-slot:title>
            Політика Конфіденційності
        </x-slot:title>
    </x-header>
@endsection

<x-layouts::app>
    <x-section sidebar-position="right">
        <p class="text-gray-700 text-lg mb-6">
            У <strong>{{ env('APP_NAME') }}</strong> ми поважаємо вашу конфіденційність і дбаємо про захист персональних
            даних. Ця політика пояснює, які дані ми збираємо, як їх використовуємо та як захищаємо вашу інформацію під
            час покупки та замовлення ножів будь-якого типу: тактичних, туристичних, кухонних або для щоденного
            використання.
        </p>

        <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">1. Збір персональних даних</h2>
        <ul class="list-disc list-inside text-gray-700 space-y-2">
            <li>Під час оформлення замовлення ми збираємо: ім’я, електронну пошту, номер телефону та адресу доставки.
            </li>
            <li>Ми також можемо збирати дані для підписки на новини та спеціальні пропозиції.</li>
            <li>Усі дані збираються добровільно та виключно для обробки замовлень і поліпшення сервісу.</li>
        </ul>

        <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">2. Використання персональних даних</h2>
        <ul class="list-disc list-inside text-gray-700 space-y-2">
            <li>Обробка замовлень і доставка товарів.</li>
            <li>Зв’язок з клієнтом щодо підтвердження замовлення або важливих оновлень.</li>
            <li>Надсилання інформаційних та рекламних матеріалів (лише за вашою згодою).</li>
            <li>Поліпшення якості сервісу та аналіз користувацького досвіду.</li>
        </ul>

        <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">3. Передача даних третім сторонам</h2>
        <p class="text-gray-700 mb-4">
            Ми не передаємо ваші персональні дані третім особам для маркетингових цілей без вашої згоди. Дані можуть
            передаватися лише:
        </p>
        <ul class="list-disc list-inside text-gray-700 space-y-2">
            <li>Кур’єрським службам для доставки замовлення.</li>
            <li>Платіжним системам для обробки платежів.</li>
            <li>Юридичним органам у випадку вимоги законом.</li>
        </ul>

        <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">4. Захист персональних даних</h2>
        <p class="text-gray-700 mb-4">
            Ми застосовуємо сучасні технології захисту даних, включаючи шифрування та безпечні сервери, щоб ваші дані
            залишалися конфіденційними та не потрапили до третіх осіб.
        </p>

        <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">5. Використання файлів cookie</h2>
        <p class="text-gray-700 mb-4">
            На нашому сайті використовуються cookie для покращення роботи та зручності користувачів, аналізу статистики
            та персоналізації контенту. Ви можете відключити cookie у налаштуваннях браузера, але це може обмежити
            функціональність сайту.
        </p>

        <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">6. Ваші права</h2>
        <ul class="list-disc list-inside text-gray-700 space-y-2">
            <li>Отримувати інформацію про те, які дані ми зберігаємо про вас.</li>
            <li>Виправляти або видаляти ваші персональні дані.</li>
            <li>Скасувати згоду на обробку персональних даних для маркетингових цілей.</li>
        </ul>

        <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">7. Контактна інформація</h2>
        <p class="text-gray-700 mb-2">
            Якщо у вас є запитання щодо обробки персональних даних, звертайтеся:
            <strong>{{ env('ADMIN_EMAIL') }}</strong>
        </p>
        <p class="text-gray-700 mb-4">
            Або за телефоном: <strong>+380 (63) 951 88 42</strong>. Ми завжди готові надати детальну інформацію та
            забезпечити дотримання ваших прав.
        </p>

        <x-slot:sidebar>
            <x-nav.sidebar class="lg:sticky lg:top-25 h-screen">
                <x-nav.sidebar.item route="polityka-vidshkoduvannia">
                    <x-lucide-rotate-ccw class="size-4" />
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
