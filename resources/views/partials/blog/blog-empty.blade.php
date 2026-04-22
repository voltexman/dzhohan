<div class="col-span-full py-24 text-center">
    <div class="inline-flex items-center justify-center size-16 rounded-full bg-zinc-50 text-zinc-300 mb-4">
        <x-lucide-search-x class="size-8" />
    </div>
    <h3 class="text-xl font-bold text-zinc-900 mb-2 font-[Oswald] uppercase tracking-wider">Нічого не знайдено</h3>
    <p class="text-zinc-500 mb-8 max-w-xs mx-auto">
        Спробуйте змінити параметри пошуку або скинути фільтри.
    </p>
    <x-button wire:click="resetFilters" color="dark" variant="soft" size="md">
        Скинути всі фільтри
    </x-button>
</div>
