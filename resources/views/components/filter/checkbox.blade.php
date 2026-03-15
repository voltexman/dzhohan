@props(['value', 'label', 'model'])

<label class="group flex items-center gap-x-1.5 cursor-pointer py-1 rounded-lg transition-all duration-300">
    <div class="relative flex items-center justify-center">
        <input type="checkbox" value="{{ $value }}" wire:model.live="{{ $model }}"
            class="peer appearance-none size-5 border border-stone-300 rounded-sm checked:bg-stone-900 checked:border-stone-900 transition-all duration-300 cursor-pointer">

        <x-lucide-check
            class="absolute size-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"
            stroke-width="4" />
    </div>

    <span
        class="text-sm font-semibold transition-all duration-300 text-neutral-500 group-hover:text-stone-900 group-has-checked:text-black tracking-tight">
        {{ $label }}
    </span>
</label>
