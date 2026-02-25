@props(['color' => 'soft'])

@php
    $colors = [
        'dark' =>
            'bg-stone-50 border-stone-200 text-stone-900 placeholder:text-stone-400 focus:bg-white focus:ring-stone-950/5',
        'light' =>
            'bg-white/10 border-white/20 text-white placeholder:text-white/40 backdrop-blur-md focus:bg-white/20 focus:ring-white/10',
        'soft' =>
            'bg-stone-50 border-stone-200 text-stone-700 placeholder:text-stone-400 focus:bg-stone-50/50 focus:ring-stone-200/50 hover:bg-stone-100',
    ];

    $currentColor = $colors[$color] ?? $colors['soft'];

    $baseClass =
        'font-[SN_Pro] border rounded-md w-full transition-all duration-250 focus:outline-none focus:ring-4 focus:ring-offset-0 px-6 py-4 resize-none placeholder:text-sm';
@endphp

<textarea {{ $attributes->merge([
    'class' => "$baseClass $currentColor",
]) }}></textarea>
