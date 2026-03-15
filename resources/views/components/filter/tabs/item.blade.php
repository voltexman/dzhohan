@props(['value', 'current', 'model'])

@php
    $active = $current === $value;
@endphp

<button type="button" wire:click="$set('{{ $model }}', '{{ $value }}')" wire:loading.attr="disabled"
    @class([
        'py-2.5 px-3 text-[11px] font-bold uppercase tracking-wider rounded-md transition-all duration-300 cursor-pointer focus:outline-none',
        'bg-zinc-900 text-white shadow-md' => $active,
        'text-zinc-500 hover:bg-white/50 hover:text-zinc-800' => !$active,
    ])>
    <div class="relative flex items-center justify-center">
        <span wire:loading.remove wire:target="$set('{{ $model }}', '{{ $value }}')">
            {{ $slot }}
        </span>
        <x-lucide-loader-circle wire:loading wire:target="$set('{{ $model }}', '{{ $value }}')"
            class="size-3 animate-spin" />
    </div>
</button>
