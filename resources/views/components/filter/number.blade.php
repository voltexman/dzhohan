@props(['model', 'min' => 0, 'max' => 1000, 'step' => 1, 'label' => ''])

<div class="flex-1 bg-white border border-zinc-200 rounded-md flex items-center overflow-hidden h-10"
    x-data="{
        val: @entangle($model).live,
        min: {{ (float) $min }},
        max: {{ (float) $max }},
        step: {{ (float) $step }},
        update(diff) {
            let next = parseFloat((Number(this.val) + diff).toFixed(2));
            if (next >= this.min && next <= this.max) {
                this.val = next;
            }
        }
    }">
    {{-- Кнопка МІНУС --}}
    <button type="button" @click="update(-step)"
        class="px-2 h-full bg-zinc-100 text-zinc-500 hover:bg-zinc-50 hover:text-orange-500 transition-colors border-e border-zinc-200 cursor-pointer disabled:opacity-40"
        :disabled="val <= min">
        <x-lucide-minus class="size-3" />
    </button>

    {{-- ПОЛЕ ВВОДУ --}}
    <input type="number" x-model.number="val" @change="if(val < min) val = min; if(val > max) val = max;"
        class="w-full text-center bg-transparent border-0 text-sm font-bold focus:outline-none focus:ring-0 p-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">

    {{-- Кнопка ПЛЮС --}}
    <button type="button" @click="update(step)"
        class="px-2 h-full bg-zinc-100 text-zinc-500 hover:bg-zinc-50 hover:text-orange-500 transition-colors border-s border-zinc-200 cursor-pointer disabled:opacity-40"
        :disabled="val >= max">
        <x-lucide-plus class="size-3" />
    </button>
</div>
