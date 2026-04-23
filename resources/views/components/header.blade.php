@props(['title', 'description', 'image'])

<header {{ $attributes->class('relative top-0 h-[38vh] bg-cover bg-center bg-no-repeat bg-fixed') }}
    style="background-image: url('{{ $image }}')">

    <div class="absolute inset-0 bg-black/50 z-0 backdrop-blur-sm"></div>

    <div class="relative z-10 size-full">
        <div class="flex flex-col items-center justify-center size-full px-6 lg:px-0 text-center">
            <h1
                {{ $title->attributes->class('text-zinc-200 text-2xl md:text-4xl max-w-lg font-[Russo_One] drop-shadow-xl mt-5') }}>
                {{ $title }}
            </h1>

            @isset($description)
                <div
                    {{ $description->attributes->class('text-white/70 drop-shadow-xl font[SN_Pro] text-sm max-w-sm lg:max-w-md mt-2.5 text-balance') }}>
                    {{ $description }}
                </div>
            @endisset

            {{ $slot }}
        </div>
    </div>
</header>
