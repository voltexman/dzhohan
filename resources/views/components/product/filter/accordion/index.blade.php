@props(['title'])

<div x-data="{ expanded: false }" class="rounded-2xl borde border-zinc-100 bg-white overflow-hidden"
    x-bind:class="expanded ? 'ring1 ring-zinc-200 shadowsm' : ''">
    <button type="button" @click="expanded = !expanded"
        class="flex w-full items-center justify-between py-2.5 outline-none transition hover:bg-zinc-50/50">
        <div class="flex items-center gap-3 text-left">
            <div class="flex size-8 items-center justify-center rounded-xl bg-zinc-100 text-zinc-500">
                <x-lucide-shield-check class="size-4" />
            </div>
            <div>
                <h4 class="text-sm font-semibold font-[SN_Pro] uppercas text-zinc-800 leading-none">
                    {{ $title }}
                </h4>

                <span class="text-xs text-zinc-500 mt-1.5 block tracking-tight">
                    {{-- {{ count((array) $tempSteel) > 0 ? 'Обрано: ' . count((array) $tempSteel) : 'Всі варіанти' }} --}}
                </span>
            </div>
        </div>
        <x-lucide-chevron-down class="size-4 text-zinc-300 transition-transform duration-300"
            x-bind:class="expanded ? 'rotate-180' : ''" />
    </button>

    <div x-show="expanded" x-collapse x-cloak>
        <div class="filter-scroll max-h-52 overflow-y-auto border-t border-zinc-50 p-2 space-y-1.5">
            {{ $slot }}
        </div>
    </div>
</div>
