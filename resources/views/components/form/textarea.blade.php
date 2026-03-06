@props(['color' => 'light'])

@php
    $colors = [
        'dark' =>
            'bg-stone-50 border-stone-200 text-stone-900 placeholder:text-stone-400 focus:bg-white focus:ring-stone-950/5',
        'light' =>
            'bg-zinc-100 border-zinc-200 text-zinc-700 placeholder:text-zinc-400 focus:bg-zinc-50/80 focus:ring-zinc-200/50 hover:bg-zinc-100',
    ];

    $currentColor = $colors[$color] ?? $colors['soft'];

    $baseClass =
        'font-medium text-sm border rounded-md w-full transition-all duration-250 focus:outline-none focus:ring-4 focus:ring-offset-0 px-4 py-4 resize-none placeholder:text-sm disabled:opacity-50 disabled:cursor-not-allowed';
@endphp

<textarea {{ $attributes->merge([
    'class' => "$baseClass $currentColor",
]) }}></textarea>
