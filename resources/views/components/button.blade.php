@props([
    'color' => 'dark', // палітра
    'size' => 'lg', // sm, md, lg
    'variant' => 'default', // default, outline, ghost, soft
    'icon' => false, // чи кнопка-іконка
    'disabled' => false, // стан disabled
])

@php
    /*
|--------------------------------------------------------------------------
| Base classes
|--------------------------------------------------------------------------
*/
    $base =
        'inline-flex items-center justify-center font-medium transition rounded-full border duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer';

    /*
|--------------------------------------------------------------------------
| Sizes
|--------------------------------------------------------------------------
*/
    $sizes = [
        'sm' => 'py-2 px-3 text-sm',
        'md' => 'py-2.5 px-4 text-sm',
        'lg' => 'py-3.5 px-6 text-sm',
    ];

    $iconSizes = [
        'sm' => 'size-9 text-sm',
        'md' => 'size-10 text-base',
        'lg' => 'size-12 text-lg',
    ];

    $shape = $icon ? $iconSizes[$size] : $sizes[$size];

    /*
|--------------------------------------------------------------------------
| Color palettes
|--------------------------------------------------------------------------
*/
    $colors = [
        'dark' => [
            'default' => 'bg-black text-white hover:bg-gray-900 focus-visible:ring-black',
            'outline' => 'border border-black text-black hover:bg-black hover:text-white focus-visible:ring-black',
            'ghost' => 'text-black hover:bg-black/10 focus-visible:ring-black',
            'soft' => 'bg-black/10 text-black hover:bg-black/20 focus-visible:ring-black',
            'link' => 'bg-transparent text-black underline-offset-4 hover:underline focus-visible:ring-black',
        ],
        'light' => [
            'default' => 'bg-zinc-200 text-black border-zinc-300 hover:bg-zinc-300/90 focus-visible:ring-gray-400',
            'outline' => 'border border-gray-300 text-black hover:bg-gray-200 focus-visible:ring-gray-400',
            'ghost' => 'text-black hover:bg-gray-200/60 focus-visible:ring-gray-400',
            'soft' => 'bg-stone-200/60 text-gray-700 border-stone-200 hover:bg-stone-200 focus-visible:ring-gray-400',
            'link' => 'bg-transparent text-white underline-offset-4 hover:underline focus-visible:ring-gray-400',
        ],
    ];

    $variantClass = $colors[$color][$variant] ?? $colors[$color]['default'];

    /*
|--------------------------------------------------------------------------
| Disabled
|--------------------------------------------------------------------------
*/
    if ($disabled) {
        $variantClass .= ' opacity-50 cursor-not-allowed pointer-events-none';
    }
@endphp

<button {{ $attributes->merge(['class' => "$base $shape $variantClass"]) }}
    @if ($disabled) disabled @endif>
    {{ $slot }}
</button>
