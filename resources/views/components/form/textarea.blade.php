@props(['color' => 'light', 'required' => false])

@php
    $colors = [
        'dark' =>
            'bg-stone-50 border-stone-200 text-stone-900 placeholder:text-stone-400 focus:bg-white focus:ring-stone-950/5',
        'light' =>
            'bg-zinc-100 border-zinc-200 text-zinc-700 placeholder:text-zinc-400 focus:bg-zinc-50/80 focus:ring-zinc-200/50 hover:bg-zinc-100',
    ];

    $currentColor = $colors[$color] ?? $colors['light'];
    $max = $attributes->get('maxlength');
    $model = $attributes->wire('model')->value();

    $baseClass =
        'font-medium text-sm border rounded-md w-full transition-all duration-250 focus:outline-none focus:ring-4 focus:ring-offset-0 px-4 py-4 resize-none placeholder:text-sm disabled:opacity-50 disabled:cursor-not-allowed';
@endphp

<div class="relative w-full" x-data="{
    count: 0,
    showPicker: false,
    focused: false,
    init() {
        this.count = $wire.get('{{ $model }}')?.length || 0;
        $watch('$wire.{{ $model }}', value => this.count = value?.length || 0);
    },
    clear() {
        $wire.set('{{ $model }}', '', false);
        this.count = 0;
        this.showPicker = false;
        $refs.input.focus();
    },
    insertEmoji(emoji) {
        const el = $refs.input;
        const start = el.selectionStart;
        const end = el.selectionEnd;
        const text = el.value;
        const newText = text.substring(0, start) + emoji + text.substring(end);

        $wire.set('{{ $model }}', newText, false);
        this.count = newText.length;

        $nextTick(() => {
            el.focus();
            el.setSelectionRange(start + emoji.length, start + emoji.length);
        });
    }
}" @click.away="showPicker = false">

    @if ($required)
        <div class="absolute top-3 right-3 size-1.5 rounded-full bg-red-500 z-10"></div>
    @endif

    <textarea x-ref="input" {{ $attributes->merge(['class' => "$baseClass $currentColor"]) }}
        @input="count = $el.value.length" @focus="focused = true" @blur="focused = false"></textarea>

    {{-- Панель інструментів --}}
    <div class="absolute bottom-3 right-3 flex items-center gap-2.5 pointer-events-none">

        {{-- 3. Лічильник (Тільки безпосередньо при фокусі поля) --}}
        @if ($max)
            <div x-show="focused" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-x-2" x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-2"
                class="flex items-center gap-2">

                <div class="text-[10px] font-bold tracking-tighter text-zinc-400">
                    <span :class="count >= {{ $max }} ? 'text-orange-600' : ''"
                        x-text="count"></span>/{{ $max }}
                </div>

                <div x-show="count > 0" class="h-3 w-px bg-zinc-300"></div>
            </div>
        @endif

        {{-- 2. Кнопка Очистити (Тільки якщо є текст) --}}
        <button type="button" x-show="count > 0" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            @click.prevent="clear()"
            class="pointer-events-auto text-[11px] font-bold text-zinc-400 hover:text-orange-600 transition-colors cursor-pointer">
            Очистити
        </button>

        {{-- 1. Кнопка Emoji (Видна завжди) --}}
        <button type="button" @mousedown.prevent="showPicker = !showPicker"
            class="pointer-events-auto text-zinc-400 hover:text-orange-500 transition-colors cursor-pointer focus:outline-none">
            <x-lucide-smile class="size-5" />
        </button>
    </div>

    {{-- Попап зі смайлами --}}
    <div x-show="showPicker" x-cloak x-transition
        class="absolute bottom-12 right-0 z-50 bg-white border border-zinc-200 rounded-md p-2.5 grid grid-cols-6 gap-0.5 pointer-events-auto">
        @foreach (['😀', '😂', '😍', '👍', '🔥', '🙌', '✨', '🙏', '❤️', '😎', '🤔', '👏'] as $emoji)
            <button type="button" @mousedown.prevent="insertEmoji('{{ $emoji }}')"
                class="hover:bg-zinc-100 p-1 rounded text-lg transition-transform active:scale-90">{{ $emoji }}</button>
        @endforeach
    </div>
</div>
