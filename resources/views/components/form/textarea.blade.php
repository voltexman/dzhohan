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

    $baseClass =
        'font-medium text-sm border rounded-md w-full transition-all duration-250 focus:outline-none focus:ring-4 focus:ring-offset-0 px-4 py-4 resize-none placeholder:text-sm disabled:opacity-50 disabled:cursor-not-allowed';
@endphp

<div class="relative w-full" x-data="{ count: $wire.get('{{ $attributes->wire('model')->value() }}')?.length || 0 }">
    @if ($required)
        <div class="absolute top-3 right-3 size-1.5 rounded-full bg-red-500 z-10"></div>
    @endif

    <textarea {{ $attributes->merge(['class' => "$baseClass $currentColor"]) }} @input="count = $el.value.length"></textarea>

    @if ($max)
        <div
            class="absolute bottom-3 right-4 text-[10px] font-bold tracking-tighter text-zinc-400 pointer-events-none uppercase">
            <span :class="count >= {{ $max }} ? 'text-red-500' : ''" x-text="count"></span>/{{ $max }}
        </div>
    @endif
</div>
