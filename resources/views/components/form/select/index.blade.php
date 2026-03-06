@props([
    'placeholder' => 'Виберіть варіант',
    'color' => 'light',
])

@php
    $colors = [
        'dark' => [
            'toggle' =>
                'bg-stone-50 border-stone-200 text-stone-900 focus:bg-white focus:ring-stone-950/5 focus:border-stone-400',
            'dropdown' => 'bg-white border-stone-200 shadow-xl',
            'scrollbar' => '[&::-webkit-scrollbar-track]:bg-stone-50 [&::-webkit-scrollbar-thumb]:bg-stone-200',
            'option' => 'text-stone-700 hover:bg-stone-50 hs-selected:bg-stone-100 hs-selected:text-stone-900',
        ],
        'light' => [
            'toggle' =>
                'bg-zinc-100 border-zinc-200 text-zinc-800 hover:bg-zinc-100 focus:bg-zinc-50/80 focus:ring-zinc-200/50 focus:border-zinc-300',
            'dropdown' => 'bg-white border-zinc-200',
            'scrollbar' => '[&::-webkit-scrollbar-track]:bg-zinc-50 [&::-webkit-scrollbar-thumb]:bg-zinc-200',
            'option' => 'text-zinc-600 hover:bg-zinc-50 hs-selected:bg-zinc-100 hs-selected:text-zinc-800',
        ],
    ];
    $c = $colors[$color] ?? $colors['soft'];
@endphp

<div {{ $attributes->merge(['class' => 'relative w-full']) }} wire:ignore x-cloak>
    <select x-data="{ selected: @entangle($attributes->wire('model')) }" x-ref="select" x-init="$watch('selected', (val) => {
        const instance = HSSelect.getInstance($refs.select, true);
        if (instance && val !== $refs.select.value) instance.setValue(val);
    });" {{ $attributes->whereDoesntStartWith('class') }}
        data-hs-select='{
            "placeholder": "{{ $placeholder }}",
            "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
            "toggleClasses": "relative w-full py-3.5 px-4 pe-10 flex text-nowrap cursor-pointer font-medium border rounded-md text-start text-sm transition-all duration-250 focus:outline-none focus:ring-4 {{ $c['toggle'] }}",
            "dropdownClasses": "mt-2 z-50 w-full border max-h-72 p-1.5 space-y-1 rounded-md overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-thumb]:rounded-md {{ $c['dropdown'] }} {{ $c['scrollbar'] }}",
            "optionClasses": "py-2.5 px-4 w-full text-sm cursor-pointer rounded-lg focus:outline-none transition-colors {{ $c['option'] }}",
            "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"hidden hs-selected:block\"><svg class=\"shrink-0 size-4\" xmlns=\"http://www.w3.org\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"20 6 9 17 4 12\"/></svg></span></div>",
            "extraMarkup": "<div class=\"absolute top-1/2 end-4 -translate-y-1/2 pointer-events-none transition-transform duration-200 [[aria-expanded=true]_&]:rotate-180\"><svg class=\"shrink-0 size-4 opacity-50\" xmlns=\"http://www.w3.org\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m6 9 6 6 6-6\"/></svg></div>"
        }'
        class="hidden">
        <option value="">{{ $placeholder }}</option>
        {{ $slot }}
    </select>
</div>
