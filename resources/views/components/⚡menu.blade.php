<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<x-offcanvas class="lg:hidden">
    <x-slot:trigger>
        <x-lucide-menu class="size-6" />
    </x-slot:trigger>

    <div class="flex flex-none items-center mx-auto font-[Russo_One] text-xl">
        <span class="font-thin text-zinc-600">Dzhohan</span>
        <span class="font-black text-zinc-900">Knives</span>
    </div>

    <x-nav variant="offcanvas" class="flex-1 overflow-y-auto">
        <x-nav.item label="Головна" url="home" icon="home" />
        <x-nav.item label="Ножі" url="knives" icon="package" />
        <x-nav.item label="Матеріали" url="materials" icon="package" />
        <x-nav.item label="Галерея" url="gallery" icon="images" />
        <x-nav.item label="Блог" url="blog" icon="newspaper" />
        <x-nav.item label="Замовлення" url="order" icon="package-open" />
        <x-nav.item label="Контакти" url="contacts" icon="notebook-text" />
    </x-nav>

    <div class="mt-auto font-[Oswald] text-xl text-zinc-700 mx-auto">
        {{ $settings->phone }}
    </div>

    <div class="flex gap-2.5 mx-auto py-5">
        @isset($settings->socials['instagram'])
            <a href="https://www.instagram.com/dzhohan_knives" target="_blank">
                <img src="{{ Vite::asset('resources/images/icons/socials/instagram.svg') }}"
                    class="size-6 opacity-70 hover:opacity-100 transition-opacity duration-250 drop-shadow-xl"
                    alt="" />
            </a>
        @endisset

        @isset($settings->socials['facebook'])
            <a href="https://www.facebook.com/KostyantynDzhohun" target="_blank">
                <img src="{{ Vite::asset('resources/images/icons/socials/facebook.svg') }}"
                    class="size-6 opacity-70 hover:opacity-100 transition-opacity duration-250 drop-shadow-xl"
                    alt="" />
            </a>
        @endisset

        @isset($settings->socials['pinterest'])
            <a href="https://www.facebook.com/KostyantynDzhohun" target="_blank">
                <img src="{{ Vite::asset('resources/images/icons/socials/pinterest.svg') }}"
                    class="size-6 opacity-70 hover:opacity-100 transition-opacity duration-250 drop-shadow-xl"
                    alt="" />
            </a>
        @endisset

        @isset($settings->socials['viber'])
            <a href="https://www.facebook.com/KostyantynDzhohun" target="_blank">
                <img src="{{ Vite::asset('resources/images/icons/socials/viber.svg') }}"
                    class="size-6 opacity-70 hover:opacity-100 transition-opacity duration-250 drop-shadow-xl"
                    alt="" />
            </a>
        @endisset

        @isset($settings->socials['telegram'])
            <a href="https://www.facebook.com/KostyantynDzhohun" target="_blank">
                <img src="{{ Vite::asset('resources/images/icons/socials/telegram.svg') }}"
                    class="size-6 opacity-70 hover:opacity-100 transition-opacity duration-250 drop-shadow-xl"
                    alt="" />
            </a>
        @endisset

        @isset($settings->socials['whatsapp'])
            <a href="https://www.facebook.com/KostyantynDzhohun" target="_blank">
                <img src="{{ Vite::asset('resources/images/icons/socials/whatsapp.svg') }}"
                    class="size-6 opacity-70 hover:opacity-100 transition-opacity duration-250 drop-shadow-xl"
                    alt="" />
            </a>
        @endisset
    </div>
</x-offcanvas>
