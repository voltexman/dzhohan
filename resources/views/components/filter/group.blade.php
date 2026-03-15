@props([
    'title',
    'icon' => 'layers', // іконка за замовчуванням
    'model' => null, // модель для кнопки "очистити"
    'persist' => null, // ключ для запам'ятовування стану
])

<div class="group/section space-y-2.5" x-data="{ expanded: @if ($persist) $persist(true).as('{{ $persist }}') @else true @endif }"
    @if ($model) wire:loading.class="opacity-50 pointer-events-none" wire:target="{{ $model }}" @endif
    x-cloak>

    <div class="flex items-center justify-between w-full group outline-none">
        <div class="flex items-center gap-2 text-sm font-black uppercase text-zinc-800 font-[Oswald] tracking-wider">
            <x-dynamic-component :component="'lucide-' . $icon" class="size-4 stroke-[2.5px] text-orange-500" />
            {{ $title }}
        </div>

        <div class="flex items-center gap-2">
            @if ($model && count($this->{$model} ?? []))
                <button wire:click="$set('{{ $model }}', [])" type="button"
                    class="flex items-center text-xs font-medium tracking-tighter text-zinc-400 hover:text-orange-600 transition-colors cursor-pointer">
                    <x-lucide-x-circle class="size-4 border border-neutral-200 rounded-full flex-none me-0.5" />
                    очистити
                </button>
            @endif

            <button @click="expanded = !expanded" type="button"
                class="text-zinc-400 cursor-pointer transition-[opacity,colors] duration-250 hover:text-zinc-600"
                x-bind:class="expanded ? 'lg:opacity-0 lg:group-hover/section:opacity-100' : 'opacity-100 text-orange-600'">
                <x-lucide-chevron-down class="size-4 transition-transform duration-300"
                    x-bind:class="expanded ? 'rotate-180' : ''" />
            </button>
        </div>
    </div>

    <div x-show="expanded" x-collapse>
        {{ $slot }}
    </div>
</div>
