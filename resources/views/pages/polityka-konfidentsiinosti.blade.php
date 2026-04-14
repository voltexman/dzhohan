<?php
use function Laravel\Folio\name;
name('polityka-konfidentsiinosti');
?>

@section('header')
    <x-header :image="Vite::asset('resources/images/header.png')">
        <x-slot:title>Політика<br>Конфіденційності</x-slot:title>
    </x-header>
@endsection

<x-layouts::app>
    <x-section sidebar-position="right">
        <div class="prose prose-lg max-w-none">
            <p class="text-gray-600 mb-8">Я, Джоган Костянтин, майстер-ножар, поважаю вашу приватність і захищаю
                ваші персональні дані. Ця політика пояснює, яку інформацію я збираю і як я нею користуюся.</p>

            <h2 class="text-2xl font-semibold mt-10 mb-4">Які дані я збираю</h2>
            <ul class="list-disc pl-6 space-y-2">
                <li>Ім’я та прізвище</li>
                <li>Адреса електронної пошти</li>
                <li>Номер телефону</li>
                <li>Адреса доставки та відділення Нової Пошти</li>
                <li>Інформація про ваше замовлення (модель ножа, матеріали, гравірування)</li>
                <li>Технічні дані: IP-адреса, тип браузера, файли cookie</li>
            </ul>

            <h2 class="text-2xl font-semibold mt-12 mb-4">Для чого я використовую ці дані</h2>
            <ul class="list-disc pl-6 space-y-2">
                <li>Щоб обробити ваше замовлення і виготовити ніж</li>
                <li>Щоб повідомити вас про статус замовлення</li>
                <li>Щоб відповісти на ваші питання і надати підтримку</li>
                <li>Щоб покращувати сайт і робити його зручнішим</li>
                <li>Для виконання вимог українського законодавства</li>
            </ul>

            <h2 class="text-2xl font-semibold mt-12 mb-4">Як я захищаю ваші дані</h2>
            <p>Я не продаю, не здаю в оренду і не передаю ваші персональні дані третім особам, окрім служб
                доставки (Нова Пошта) та платіжних систем, які потрібні для виконання замовлення. Всі дані
                зберігаються на захищених серверах.</p>

            <h2 class="text-2xl font-semibold mt-12 mb-4">Ваші права</h2>
            <p>Ви в будь-який момент можете:</p>
            <ul class="list-disc pl-6 space-y-2">
                <li>Запитати, які саме дані я про вас маю</li>
                <li>Попросити виправити або видалити ваші дані</li>
                <li>Відкликати згоду на обробку даних</li>
                <li>Скаржитися до державних органів</li>
            </ul>
            <p class="mt-6">Для цього просто напишіть мені на email: <strong>{{ $settings->email }}</strong></p>

            <h2 class="text-2xl font-semibold mt-12 mb-4">Файли cookie</h2>
            <p>Сайт використовує cookie для коректної роботи. Ви можете вимкнути їх у налаштуваннях браузера,
                але деякі функції сайту можуть перестати працювати.</p>

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
