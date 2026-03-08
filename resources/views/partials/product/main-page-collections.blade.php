<div class="relative p-5 lg:p-10 rounded-sm bg-center bg-cover bg-no-repeat flex flex-col justify-end overflow-hidden group col-span-1 first:col-span-2 lg:[&:nth-child(1)]:col-span-2 lg:[&:nth-child(1)]:row-span-2 lg:[&:nth-child(2)]:row-span-2 lg:[&:nth-child(n+3)]:min-h-[250px]"
    style="background-image: url('{{ Vite::asset('resources/images/' . $collection->images()) }}')">

    {{-- overlay --}}
    <div class="absolute inset-0 bg-linear-to-t from-black/70 via-black/30 to-transparent"></div>

    {{-- контент --}}
    <div class="relative z-10 text-white">

        <div class="text-xl lg:text-2xl font-[Oswald] font-semibold">
            {{ $collection->getLabel() }}
        </div>

        <div class="text-sm mt-1 opacity-90 max-w-xs hidden lg:block">
            {{ $collection->description() }}
        </div>

        <a href="{{ $collection->url() }}" class="mt-3 inline-flex items-center gap-1 text-orange-400 font-medium"
            wire:navigate>
            Переглянути
            <x-lucide-arrow-right class="size-4 group-hover:translate-x-1 transition-transform duration-200" />
        </a>
    </div>
</div>
