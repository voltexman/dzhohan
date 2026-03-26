@props(['attribute', 'value', 'filters'])

@php
    $isSelected = isset($filters[$attribute->slug]) && in_array($value->id, (array) $filters[$attribute->slug]);
@endphp

<button type="button" wire:click="toggleFilter('{{ $attribute->slug }}', {{ $value->id }})"
    wire:loading.attr="disabled" wire:target="toggleFilter('{{ $attribute->slug }}', {{ $value->id }})"
    {{ $attributes->merge([
        'class' =>
            'px-2.5 py-1.5 border rounded-sm text-xs font-semibold tracking-tight transition cursor-pointer ' .
            ($isSelected
                ? 'bg-zinc-800 text-white border-zinc-800'
                : 'bg-white border-zinc-200 text-zinc-700 hover:bg-zinc-100'),
    ]) }}>
    {{ $value->value }}
</button>
