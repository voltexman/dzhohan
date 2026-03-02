@props(['label' => null, 'id' => uniqid('checkbox-')])

<label for="{{ $id }}" x-data="{ checked: @entangle($attributes->wire('model')) }"
    class="group flex items-center gap-1.5 mt-1.5 cursor-pointer select-none">

    <div class="relative flex items-center justify-center">
        <!-- Реальний інпут, який тепер прив'язаний до Alpine -->
        <input type="checkbox" id="{{ $id }}" x-model="checked"
            {{ $attributes->whereDoesntStartWith('wire:model')->merge([
                'class' => 'peer sr-only',
            ]) }}>

        <!-- Кастомний квадрат -->
        <div :class="checked ? 'bg-zinc-800 border-zinc-800' : 'bg-white/95 border-zinc-200'"
            class="size-6 border rounded-md transition-all duration-200 
                    group-hover:border-zinc-400
                    peer-focus:ring-2 peer-focus:ring-neutral-500 peer-focus:ring-offset-2">
        </div>

        <!-- Іконка галочки -->
        <div x-show="checked" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-50" x-transition:enter-end="opacity-100 scale-100"
            class="absolute inset-0 flex items-center justify-center pointer-events-none">
            <x-lucide-check class="size-4 text-white stroke-[3px]" />
        </div>
    </div>

    @if ($label)
        <span class="font-[SN_Pro] text-sm font-medium text-gray-800 group-hover:text-zinc-900 transition-colors">
            {{ $label }}
        </span>
    @endif
</label>
