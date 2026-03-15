@props(['min', 'max', 'fromModel', 'toModel', 'resetMethod' => 'resetPrice'])

<div x-data="{
    minL: @js((int) $min),
    maxL: @js((int) $max),
    from: @entangle($fromModel),
    to: @entangle($toModel),
    get left() { return ((this.from - this.minL) / (this.maxL - this.minL)) * 100 },
    get right() { return 100 - ((this.to - this.minL) / (this.maxL - this.minL)) * 100 }
}" class="space-y-5">

    <!-- Ряд з ціною та кнопкою скидання -->
    <div class="flex justify-between items-end">
        <div class="text-2xl font-light tracking-tighter text-stone-950">
            <span x-text="Number(from).toLocaleString()"></span> —
            <span x-text="Number(to).toLocaleString()"></span>
            <span class="text-xs align-top ml-1 font-bold text-zinc-400 uppercase">грн</span>
        </div>

        @if ($this->{$fromModel} !== (int) $min || $this->{$toModel} !== (int) $max)
            <button type="button" wire:click="{{ $resetMethod }}"
                class="p-1.5 rounded-full bg-white text-stone-500 hover:text-stone-800 hover:bg-zinc-100 transition-all border border-zinc-200 cursor-pointer">
                <x-lucide-rotate-ccw class="size-3.5" />
            </button>
        @endif
    </div>

    <!-- Повзунки -->
    <div class="relative py-1.5">
        <div class="relative h-2 w-full rounded-full bg-zinc-200">
            {{-- Активна полоса --}}
            <div class="absolute h-full rounded-full bg-zinc-950" :style="'left: ' + left + '%; right: ' + right + '%'">
            </div>

            {{-- Лівий повзунок --}}
            <input type="range" :min="minL" :max="maxL" x-model.number="from"
                @change="$wire.set('{{ $fromModel }}', from)" @input="if(from > to) from = to"
                class="cursor-pointer pointer-events-none absolute -top-2.5 z-30 h-7 w-full appearance-none bg-transparent 
                [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:size-6 [&::-webkit-slider-thumb]:appearance-none 
                [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:border-[1.5px] 
                [&::-webkit-slider-thumb]:border-stone-900 [&::-webkit-slider-thumb]:shadow-xl
                [&::-moz-range-thumb]:pointer-events-auto [&::-moz-range-thumb]:size-6 [&::-moz-range-thumb]:appearance-none 
                [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:bg-white [&::-moz-range-thumb]:border-[1.5px] 
                [&::-moz-range-thumb]:border-stone-900 [&::-moz-range-thumb]:shadow-xl">

            {{-- Правий повзунок --}}
            <input type="range" :min="minL" :max="maxL" x-model.number="to"
                @change="$wire.set('{{ $toModel }}', to)" @input="if(to < from) to = from"
                class="cursor-pointer pointer-events-none absolute -top-2.5 z-30 h-7 w-full appearance-none bg-transparent 
                [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:size-6 [&::-webkit-slider-thumb]:appearance-none 
                [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:border-[1.5px] 
                [&::-webkit-slider-thumb]:border-stone-900 [&::-webkit-slider-thumb]:shadow-xl
                [&::-moz-range-thumb]:pointer-events-auto [&::-moz-range-thumb]:size-6 [&::-moz-range-thumb]:appearance-none 
                [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:bg-white [&::-moz-range-thumb]:border-[1.5px] 
                [&::-moz-range-thumb]:border-stone-900 [&::-moz-range-thumb]:shadow-xl">
        </div>
    </div>
</div>
