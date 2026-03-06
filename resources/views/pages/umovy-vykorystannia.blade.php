<?php

use function Laravel\Folio\name;
name('umovy-vykorystannia');
?>

@section('header')
    <x-header :image="Vite::asset('resources/images/header.png')">
        <x-slot:title>
            Умови використання
        </x-slot:title>
    </x-header>
@endsection

<x-layouts::app>
    <x-section sidebar-position="right">
        <p class="text-gray-700 text-lg mb-6">
            Ласкаво просимо на сайт <strong>{{ env('APP_NAME') }}</strong>. Використовуючи наш сайт і послуги, ви
            погоджуєтесь дотримуватись цих Умов використання. Ці правила регулюють продаж ножів різних типів: тактичних,
            туристичних, кухонних та для щоденного використання, а також порядок замовлень, доставки та повернень.
        </p>

        <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">1. Загальні положення</h2>
        <ul class="list-disc list-inside text-gray-700 space-y-2">
            <li>Всі матеріали на сайті (тексти, зображення, дизайн) належать <strong>{{ env('APP_NAME') }}</strong>.
            </li>
            <li>Користувач зобов’язується використовувати сайт законно та не порушувати права третіх осіб.</li>
            <li>Всі покупки здійснюються відповідно до чинного законодавства України.</li>
        </ul>

        <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">2. Використання сайту</h2>
        <ul class="list-disc list-inside text-gray-700 space-y-2">
            <li>Ви погоджуєтесь надавати точні та правдиві дані під час оформлення замовлення.</li>
            <li>Забороняється розміщувати неправдиву або незаконну інформацію у формі зворотного зв’язку або підписки.
            </li>
            <li>Використання будь-яких автоматизованих засобів для збору даних з сайту без нашої згоди заборонено.</li>
        </ul>

        <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">3. Замовлення та оплата</h2>
        <ul class="list-disc list-inside text-gray-700 space-y-2">
            <li>Усі замовлення ножів обробляються після підтвердження платежу.</li>
            <li>Ми залишаємо за собою право відмовити у замовленні у випадках підозрілих або незаконних дій.</li>
            <li>Оплата здійснюється лише через дозволені платіжні системи.</li>
        </ul>

        <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">4. Відповідальність</h2>
        <p class="text-gray-700 mb-4">
            Ми не несемо відповідальності за прямі або непрямі збитки, пов’язані з неправильним використанням ножів.
            Користувач зобов’язується дотримуватись правил безпечного використання та зберігання продукції.
        </p>

        <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">5. Політика повернення та доставки</h2>
        <p class="text-gray-700 mb-4">
            Повернення товару та доставка здійснюються відповідно до наших <a
                href="{{ route('polityka-vidshkoduvannia') }}" class="text-blue-600 hover:underline">Політики
                відшкодування</a> та <a href="{{ route('polityka-dostavky') }}"
                class="text-blue-600 hover:underline">Політики доставки</a>. Просимо уважно ознайомитися з цими
            документами перед замовленням.
        </p>

        <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">6. Зміни умов</h2>
        <p class="text-gray-700 mb-4">
            Ми залишаємо за собою право змінювати ці Умови використання у будь-який час. Оновлені умови будуть
            опубліковані на цій сторінці. Рекомендуємо регулярно перевіряти зміни.
        </p>

        <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">7. Контактна інформація</h2>
        <p class="text-gray-700 mb-2">
            Якщо у вас є питання щодо Умов використання, звертайтеся на електронну пошту:
            <strong>{{ env('ADMIN_EMAIL') }}</strong>
        </p>
        <p class="text-gray-700 mb-4">
            Або телефонуйте за номером: <strong>+380 (63) 951 88 42</strong>. Ми завжди готові надати роз’яснення та
            допомогти користувачам.
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
