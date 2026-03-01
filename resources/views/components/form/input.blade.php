@props([
    'icon' => null,
    'size' => 'lg', // sm, md, lg
    'color' => 'soft', // dark (для білого фону), light (для темного/скла)
])

@php
    /*
    |--------------------------------------------------------------------------
    | Розміри (Синхронізовано з кнопками)
    |--------------------------------------------------------------------------
    */
    $sizes = [
        'sm' => [
            'input' => 'py-2 text-sm',
            'icon_pos' => 'left-3',
            'pl' => 'pl-10',
            'pr' => 'pr-4',
        ],
        'md' => [
            'input' => 'py-2.5 text-sm',
            'icon_pos' => 'left-3.5',
            'pl' => 'pl-11',
            'pr' => 'pr-5',
        ],
        'lg' => [
            'input' => 'py-3.5 text-sm',
            'icon_pos' => 'left-4',
            'pl' => 'pl-12',
            'pr' => 'pr-6',
        ],
    ];

    $currentSize = $sizes[$size] ?? $sizes['lg'];

    /*
    |--------------------------------------------------------------------------
    | Палітри кольорів
    |--------------------------------------------------------------------------
    */
    $colors = [
        'dark' => [
            'input' =>
                'bg-stone-50 border-stone-200 text-stone-900 placeholder:text-stone-400 focus:bg-white focus:ring-stone-950/5',
            'icon' => 'text-stone-300',
        ],
        'light' => [
            'input' =>
                'bg-white/10 border-white/20 text-white placeholder:text-white/40 backdrop-blur-md focus:bg-white/20 focus:ring-white/10',
            'icon' => 'text-zinc-200',
        ],
        'soft' => [
            'input' =>
                'bg-stone-50 border-stone-200 text-stone-800 placeholder:text-stone-400 focus:bg-stone-50/80 focus:ring-stone-200/50 hover:bg-stone-100',
            'icon' => 'text-stone-600',
        ],
    ];

    $currentColor = $colors[$color] ?? $colors['dark'];

    $baseClass =
        'font-medium border rounded-md w-full transition-all duration-250 focus:outline-none focus:ring-4 focus:ring-offset-0 disabled:opacity-50 disabled:cursor-not-allowed';

    $paddingClass = $icon ? $currentSize['pl'] . ' ' . $currentSize['pr'] : 'px-6';
@endphp

<div class="relative w-full">
    @if ($icon)
        <div class="absolute inset-y-0 {{ $currentSize['icon_pos'] }} flex items-center pointer-events-none">
            <x-dynamic-component :component="'lucide-' . $icon"
                class="{{ $size === 'sm' ? 'size-4' : 'size-5' }} {{ $currentColor['icon'] }} transition-colors" />
        </div>
    @endif

    <input
        {{ $attributes->merge([
            'class' => "$baseClass {$currentSize['input']} {$currentColor['input']} $paddingClass",
        ]) }}>
</div>
