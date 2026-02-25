@props(['value', 'label', 'property', 'active' => false])

<button type="button" wire:click="toggleFilter('{{ $property }}', '{{ $value }}')"
    class="flex w-full items-center justify-between rounded-full px-3 py-2.5 text-xs font-medium transition-all {{ $active ? 'bg-zinc-100 text-black border-zinc-200' : 'text-zinc-500 hover:bg-zinc-50 border-transparent border' }}">

    <span>{{ $label }}</span>

    @if ($active)
        <x-lucide-check class="size-3.5 text-black" stroke-width="3" />
    @endif
</button>
