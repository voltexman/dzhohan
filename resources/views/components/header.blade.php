@props(['title', 'description', 'image'])

<header class="relative top-0 h-[45vh] bg-cover bg-center bg-no-repeat"
    style="background-image: url('{{ $image }}')">

    <!-- затемнення -->
    <div class="absolute inset-0 bg-black/50 z-0"></div>

    <!-- Swiper container -->
    <div class="relative z-10 size-full">
        <div class="flex flex-col items-center justify-center size-full px-6 lg:px-0 text-center">
            <h1
                {{ $title->attributes->class('text-zinc-200 text-2xl md:text-5xl max-w-lg font-[Russo_One] drop-shadow-xl mt-5') }}>
                {{ $title }}
            </h1>
            <div
                {{ $description->attributes->class('text-white drop-shadow-xl font-[SN_Pro] max-w-sm lg:max-w-md mt-2.5 text-balance') }}>
                {{ $description }}
            </div>
        </div>
    </div>
</header>
