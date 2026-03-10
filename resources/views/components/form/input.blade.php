@props([
    'button' => null,
    'icon' => null,
    'size' => 'lg',
    'color' => 'light',
    'invalid' => false,
])

@php
    // 1. Автоматичне визначення імені для перевірки помилок (з wire:model або name)
    $wireModel = $attributes->whereStartsWith('wire:model')->first();
    $fieldName = $attributes->get('name') ?? $wireModel;
    $hasError = $invalid || ($fieldName && $errors->has($fieldName));

    // 2. Конфігурація розмірів
    $sizes = [
        'sm' => ['input' => 'py-2 text-sm', 'icon_pos' => 'left-3', 'pl' => 'pl-10', 'pr' => 'pr-4'],
        'md' => ['input' => 'py-2.5 text-sm', 'icon_pos' => 'left-3.5', 'pl' => 'pl-11', 'pr' => 'pr-5'],
        'lg' => ['input' => 'py-3.5 text-sm', 'icon_pos' => 'left-4', 'pl' => 'pl-12', 'pr' => 'pr-6'],
        'xl' => ['input' => 'py-4.5 text-sm', 'icon_pos' => 'left-4', 'pl' => 'pl-12', 'pr' => 'pr-6'],
    ];
    $currentSize = $sizes[$size] ?? $sizes['lg'];

    // 3. Конфігурація кольорів (звичайний стан та стан помилки)
    $colors = [
        'dark' => [
            'normal' =>
                'bg-neutral-200 border-stone-300 text-stone-900 placeholder:text-neutral-400 focus:bg-white focus:ring-stone-950/5',
            'error' => 'border-red-300 text-red-900 placeholder:text-red-300 focus:ring-red-500/10 bg-red-50',
            'icon' => 'text-stone-300',
        ],
        'light' => [
            'normal' =>
                'bg-zinc-100 border-zinc-200 text-zinc-800 placeholder:text-zinc-400 focus:bg-zinc-50/80 focus:ring-zinc-200/50 hover:bg-zinc-100',
            'error' => 'border-red-300 text-red-800 placeholder:text-red-300 focus:ring-red-200/50 bg-red-50',
            'icon' => 'text-stone-600',
        ],
    ];
    $currentColor = $colors[$color] ?? $colors['light'];

    // 4. Логіка класів
    $baseClass =
        'font-medium border rounded-md w-full transition-all duration-250 focus:outline-none focus:ring-4 focus:ring-offset-0 disabled:opacity-50 disabled:cursor-not-allowed';
    $stateClass = $hasError ? $currentColor['error'] : $currentColor['normal'];
    $iconColor = $hasError ? 'text-red-500' : $currentColor['icon'];
    $paddingClass = $icon ? $currentSize['pl'] . ' ' . $currentSize['pr'] : 'px-4';
@endphp

<div class="w-full">
    <div class="relative">
        {{-- Індикатор обов'язкового поля --}}
        @if ($attributes->has('required'))
            <span class="absolute top-1.5 right-1.5 flex h-2 w-2 z-20">
                <span
                    class="relative inline-flex rounded-full h-2 w-2 {{ $hasError ? 'bg-red-600' : 'bg-red-500' }}"></span>
            </span>
        @endif

        {{-- Іконка --}}
        @if ($icon)
            <div class="absolute inset-y-0 {{ $currentSize['icon_pos'] }} flex items-center pointer-events-none z-10">
                <x-dynamic-component :component="'lucide-' . $icon"
                    class="{{ $size === 'sm' ? 'size-4' : 'size-5' }} {{ $iconColor }} transition-colors" />
            </div>
        @endif

        {{-- Поле вводу --}}
        <input
            {{ $attributes->merge([
                'type' => 'text',
                'class' => "$baseClass {$currentSize['input']} $stateClass $paddingClass",
            ]) }}>

        @isset($button)
            {{ $button }}
        @endisset
    </div>

    {{-- Текст помилки валідації (Livewire/Laravel) --}}
    @if ($hasError && $fieldName)
        <p class="mt-1.5 text-xs text-red-500 font-medium ml-1">
            {{ $errors->first($fieldName) }}
        </p>
    @endif
</div>
