@props(['title', 'caption', 'image'])

<div class="col-span-full my-10 p-5 lg:p-10 relative bg-cover bg-center bg-no-repeat"
    style="background-image: url('{{ $image }}')">

    <!-- затемнення -->
    <div class="absolute inset-0 bg-black/35 z-0"></div>

    <div class="border relative z-10 size-full border-zinc-100">
        <div class="max-w-3xl p-5 lg:p-10 space-y-5 text-zinc-100">

            <h2 class="text-2xl md:text-4xl font-[Oswald] uppercase font-black tracking-wide">
                {{ $title }}
            </h2>

            <p class="lg:text-lg text-zinc-300 leading-tight font-[SN_Pro]">
                {{ $caption }}
            </p>

            {{ $slot }}

        </div>
    </div>
</div>
