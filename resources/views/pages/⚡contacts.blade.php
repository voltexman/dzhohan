<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

@section('header')
    <x-header :image="Vite::asset('resources/images/contact-header-bg.png')">
        <x-slot:title>
            Контакти
        </x-slot:title>
    </x-header>
@endsection

<div class="max-w-5xl mx-auto px-5 lg:px-0 py-10 lg:py-20">
    <div class="max-w-4xl mx-auto grid lg:grid-cols-4 divide-y lg:divide-x lg:divide-y-0 divide-zinc-200/70">
        <div
            class="flex lg:flex-col items-center gap-x-5 p-5 bg-linear-to-t lg:bg-linear-to-l from-gray-50 to-transparent hover:from-gray-100 transition-colors duration-300">
            <div class="flex lg:flex-col justify-center items-center gap-x-5">
                <x-lucide-circle-user-round class="size-8 shrink-0 stroke-orange-500" stroke-width="1.5" />

                <div class="lg:text-center lg:mt-5">
                    <div class="font-[Oswald] text-xl font-medium text-gray-800 uppercase">Контакт</div>
                    <div class="font-[SN_Pro] font-medium text-orange-600 mt-1.5">{{ $settings->contact }}</div>
                </div>
            </div>
        </div>

        <div
            class="flex lg:flex-col items-center gap-x-5 p-5 bg-gray-50 hover:bg-gray-100 transition-colors duration-300">
            <div class="flex lg:flex-col justify-center items-center gap-x-5">
                <x-lucide-phone class="size-8 shrink-0 stroke-orange-500" stroke-width="1.5" />

                <div class="lg:text-center lg:mt-5">
                    <div class="font-[Oswald] text-xl font-medium text-gray-800 uppercase">Телефон</div>
                    <div class="font-[SN_Pro] font-medium text-orange-600 mt-1.5">{{ $settings->phone }}</div>
                </div>
            </div>
        </div>

        <div
            class="flex lg:flex-col items-center gap-x-5 p-5 bg-gray-50 hover:bg-gray-100 transition-colors duration-300">
            <x-lucide-mail class="size-8 shrink-0 stroke-orange-500" stroke-width="1.5" />
            <div class="lg:text-center lg:mt-5">
                <div class="font-[Oswald] text-xl font-medium text-gray-800 uppercase">E-Mail</div>
                <div class="font-[SN_Pro] font-medium text-orange-600 mt-1.5">{{ $settings->email }}</div>
            </div>
        </div>

        <div
            class="flex lg:flex-col items-center gap-x-5 p-5 bg-linear-to-b lg:bg-linear-to-r from-gray-50 to-transparent hover:from-gray-100 transition-colors duration-300">
            <x-lucide-map-pin class="size-8 shrink-0 stroke-orange-500" stroke-width="1.5" />
            <div class="lg:text-center lg:mt-5">
                <div class="font-[Oswald] text-xl font-medium text-gray-800 uppercase">Локація</div>
                <div class="font-[SN_Pro] font-medium text-orange-600 mt-1.5">{{ $settings->location }}</div>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto">
        <h2 class="text-center font-[Oswald] text-3xl font-semibold mt-10 text-gray-700">Зв'яжіться зі мною</h2>
        <div class="text-sm text-gray-600 max-w-md text-center mx-auto mt-5">
            Маєте цікаву ідею, пропозицію або будь-яке питання? Не вагайтесь – напишіть мені листа і я обов'язково
            ознайомлюсь!
        </div>

        <div class="grid lg:grid-cols-2 gap-y-10 pt-10">
            <div class="order-2 lg:order-1 flex flex-col gap-y-7.5">
                <div>
                    <x-form.label class="mb-1.5">Передзвонити вам?</x-form.label>
                    <div class="text-sm text-gray-600 mb-2.5 lg:max-w-sm">
                        Якщо ви не можете подзвонити, будь ласка, вкажіть номер телефону, і я вам зателефоную.
                    </div>
                    <livewire:callback />
                </div>

                <div>
                    <x-form.label class="mb-1.5">Я в соціальних мережах:</x-form.label>
                    <div class="flex gap-2.5">
                        @if ($settings->socials['instagram'])
                            <a href="{{ $settings->socials['instagram'] }}" target="_blank"
                                class="size-10 flex justify-center items-center rounded-sm bg-zinc-100 border border-zinc-200">
                                <img src="{{ Vite::asset('resources/images/icons/socials/instagram.svg') }}"
                                    class="size-7 opacity-70" alt="" />
                            </a>
                        @endif

                        @if ($settings->socials['facebook'])
                            <a href="{{ $settings->socials['facebook'] }}" target="_blank"
                                class="size-10 flex justify-center items-center rounded-sm bg-zinc-100 border border-zinc-200">
                                <img src="{{ Vite::asset('resources/images/icons/socials/facebook.svg') }}"
                                    class="size-7 opacity-70" alt="" />
                            </a>
                        @endif

                        @if ($settings->socials['pinterest'])
                            <a href="{{ $settings->socials['pinterest'] }}" target="_blank"
                                class="size-10 flex justify-center items-center rounded-sm bg-zinc-100 border border-zinc-200">
                                <img src="{{ Vite::asset('resources/images/icons/socials/pinterest.svg') }}"
                                    class="size-7 opacity-70" alt="" />
                            </a>
                        @endif

                        @if ($settings->socials['viber'])
                            <a href="viber://chat?number={{ preg_replace('/\D/', '', $settings->socials['viber']) }}"
                                class="size-10 flex justify-center items-center rounded-sm bg-zinc-100 border border-zinc-200">
                                <img src="{{ Vite::asset('resources/images/icons/socials/viber.svg') }}"
                                    class="size-7 opacity-70" alt="" />
                            </a>
                        @endif

                        @if ($settings->socials['telegram'])
                            @php
                                $tgValue = preg_replace('/\D/', '', $settings->socials['telegram']);
                                // Якщо в полі цифри (номер телефону)
                                if (is_numeric($tgValue) && strlen($tgValue) > 5) {
                                    $tgLink = 'tg://msg?to=+' . $tgValue;
                                } else {
                                    // Якщо в полі нікнейм
                                    $tgLink = 'https://t.me/' . ltrim($settings->socials['telegram'], '@');
                                }
                            @endphp
                            <a href="{{ $tgLink }}"
                                class="size-10 flex justify-center items-center rounded-sm bg-zinc-100 border border-zinc-200">
                                <img src="{{ Vite::asset('resources/images/icons/socials/telegram.svg') }}"
                                    class="size-7 opacity-70" alt="" />
                            </a>
                        @endif

                        @if ($settings->socials['whatsapp'])
                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $settings->socials['whatsapp']) }}"
                                target="_blank"
                                class="size-10 flex justify-center items-center rounded-sm bg-zinc-100 border border-zinc-200">
                                <img src="{{ Vite::asset('resources/images/icons/socials/whatsapp.svg') }}"
                                    class="size-7 opacity-70" alt="" />
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="order-1 lg:order-2">
                <livewire:feedback />
            </div>
        </div>
    </div>
</div>
