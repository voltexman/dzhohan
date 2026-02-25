@props(['label' => null, 'id' => uniqid('checkbox-')])

<label for="{{ $id }}" class="group flex items-center gap-3 cursor-pointer select-none">
    <div class="relative flex items-center justify-center">
        <!-- Прихований реальний інпут -->
        <input type="checkbox" id="{{ $id }}"
            {{ $attributes->merge([
                'class' => 'peer sr-only',
            ]) }}>

        <!-- Кастомний квадрат (стилізований під ваш інпут) -->
        <div
            class="size-6 bg-white/95 border border-zinc-200 rounded-md 
                    transition-all duration-200 
                    peer-checked:bg-zinc-800 peer-checked:border-zinc-800
                    group-hover:border-zinc-400
                    peer-focus:ring-2 peer-focus:ring-neutral-500 peer-focus:ring-offset-2">
        </div>

        <!-- Іконка галочки (з'являється при фокусі/чеку) -->
        <div
            class="absolute inset-0 flex items-center justify-center opacity-0 scale-50 
                    peer-checked:opacity-100 peer-checked:scale-100 transition-all duration-200">
            <x-lucide-check class="size-4 text-white stroke-3" />
        </div>
    </div>

    <!-- Текст мітки -->
    @if ($label)
        <span class="font-[SN_Pro] text-sm font-medium text-gray-800 group-hover:text-zinc-900 transition-colors">
            {{ $label }}
        </span>
    @endif
</label>
