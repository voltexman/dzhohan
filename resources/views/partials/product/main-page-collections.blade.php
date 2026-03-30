@use('App\Models\Product')

<a href="{{ $collection->url() }}" wire:navigate
    class="product-collection relative p-5 lg:p-10 rounded-sm flex flex-col justify-end overflow-hidden group col-span-1 first:col-span-2 lg:[&:nth-child(1)]:col-span-2 lg:[&:nth-child(1)]:row-span-2 lg:[&:nth-child(2)]:row-span-2 lg:[&:nth-child(n+3)]:min-h-[250px]">

    <div class="absolute inset-0 bg-center bg-cover bg-no-repeat transition-transform duration-700 ease-out group-hover:scale-110"
        style="background-image: url('{{ Vite::asset('resources/images/' . $collection->images()) }}')">
    </div>

    <div
        class="absolute inset-0 bg-linear-to-t from-black/80 via-black/30 to-transparent transition-opacity duration-300 group-hover:from-black/90">
    </div>

    <div class="absolute right-3 top-3 flex items-center gap-1.5">
        <img src="{{ Vite::asset('resources/images/header-knife.svg') }}" alt="Collection Icon" class="size-6 opacity-75">
        <span class="text-white opacity-75 text-xl font-[Oswald] font-semibold">
            {{ Product::countByCollection($collection) }}
        </span>
    </div>

    <div class="relative z-10 text-white">
        <div class="text-xl lg:text-2xl font-[Oswald] font-semibold tracking-wide">
            {{ $collection->getShortLabel() }}
        </div>

        <div class="text-sm mt-1 opacity-90 max-w-sm hidden lg:block line-clamp-2">
            {{ $collection->description() }}
        </div>

        <div class="mt-1.5 inline-flex items-center gap-1 text-orange-400 font-medium text-sm lg:text-base">
            <span>Переглянути</span>
            <x-lucide-arrow-right class="size-4 group-hover:translate-x-2 transition-transform duration-300" />
        </div>
    </div>
</a>
