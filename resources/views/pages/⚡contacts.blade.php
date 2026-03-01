<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

@section('header')
    <x-header :image="Vite::asset('resources/images/header.png')">
        <x-slot:title>
            Контакти
        </x-slot:title>
    </x-header>
@endsection

<div class="max-w-5xl mx-auto px-6 lg:px-0 py-20">
    <div class="max-w-4xl mx-auto grid lg:grid-cols-4 divide-y lg:divide-x lg:divide-y-0 divide-zinc-200/70">
        <div
            class="flex lg:flex-col items-center gap-x-5 p-5 bg-linear-to-t lg:bg-linear-to-l from-gray-50 to-transparent hover:from-gray-100 transition-colors duration-300">
            <div class="flex lg:flex-col justify-center items-center gap-x-5">
                <x-lucide-circle-user-round class="size-8 shrink-0 stroke-orange-500" stroke-width="1.5" />

                <div class="lg:text-center lg:mt-5">
                    <div class="font-[Oswald] text-xl font-medium text-gray-800 uppercase">
                        Контакт
                    </div>
                    <div class="font-[SN_Pro] font-medium text-orange-600 mt-1.5">
                        Джоган Констянтин
                    </div>
                </div>
            </div>
        </div>

        <div
            class="flex lg:flex-col items-center gap-x-5 p-5 bg-gray-50 hover:bg-gray-100 transition-colors duration-300">
            <div class="flex lg:flex-col justify-center items-center gap-x-5">
                <x-lucide-phone class="size-8 shrink-0 stroke-orange-500" stroke-width="1.5" />

                <div class="lg:text-center lg:mt-5">
                    <div class="font-[Oswald] text-xl font-medium text-gray-800 uppercase">
                        Телефон
                    </div>
                    <div class="font-[SN_Pro] font-medium text-orange-600 mt-1.5">
                        +380 (63) 951 88 42
                    </div>
                </div>
            </div>
        </div>

        <div
            class="flex lg:flex-col items-center gap-x-5 p-5 bg-gray-50 hover:bg-gray-100 transition-colors duration-300">
            <x-lucide-mail class="size-8 shrink-0 stroke-orange-500" stroke-width="1.5" />
            <div class="lg:text-center lg:mt-5">
                <div class="font-[Oswald] text-xl font-medium text-gray-800 uppercase">E-Mail</div>
                <div class="font-[SN_Pro] font-medium text-orange-600 mt-1.5">voltexman@gmail.com</div>
            </div>
        </div>

        <div
            class="flex lg:flex-col items-center gap-x-5 p-5 bg-linear-to-b lg:bg-linear-to-r from-gray-50 to-transparent hover:from-gray-100 transition-colors duration-300">
            <x-lucide-map-pin class="size-8 shrink-0 stroke-orange-500" stroke-width="1.5" />
            <div class="lg:text-center lg:mt-5">
                <div class="font-[Oswald] text-xl font-medium text-gray-800 uppercase">Локація</div>
                <div class="font-[SN_Pro] font-medium text-orange-600 mt-1.5">м. Вінниця</div>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto">
        <h2 class="text-center font-[Oswald] text-3xl font-semibold mt-10 text-gray-700">Зв'яжіться зі мною</h2>
        <div class="text-sm text-gray-600 max-w-md text-center mx-auto mt-5">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi, saepe hic ullam, accusantium architecto
            tempora exercitationem.
        </div>

        <div class="grid lg:grid-cols-2 gap-y-10 pt-10">
            <div class="order-2 lg:order-1 flex flex-col gap-y-5">
                <div>
                    <x-form.label class="mb-1.5">Передзвонити вам?</x-form.label>
                    <div class="text-sm text-gray-600 mb-2.5 lg:max-w-sm">
                        Якщо немаєте можливості подзвонити, вкажіть ваш номер телефону і я вам зателефоную
                    </div>
                    <livewire:callback />
                </div>

                <div>
                    <x-form.label class="mb-1.5">Я в соціальних мережах:</x-form.label>
                    <div class="flex gap-2.5">
                        <x-button color="dark" size="md" icon>
                            <x-lucide-facebook class="size-5" />
                        </x-button>
                        <x-button color="dark" size="md" icon>
                            <x-lucide-instagram class="size-5" />
                        </x-button>
                    </div>
                </div>
            </div>

            <div class="order-1 lg:order-2">
                <livewire:feedback />
            </div>
        </div>
    </div>
</div>
