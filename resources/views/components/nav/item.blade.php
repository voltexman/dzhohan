@props(['label', 'url', 'icon' => null])

<a href="{{ route($url) }}"
    {{ $attributes->class('text-gray-800 flex-none text-lg lg:text-base uppercase font-normal font-[Oswald] hover:text-gray-300 lg:font-medium data-current:text-orange-500 transition-colors duration-300') }}
    wire:navigate>
    @if ($icon)
        <x-dynamic-component :component="'lucide-' . $icon" class="inline size-4.5 mr-0.5 -mt-1" />
    @endif
    {{ $label }}
</a>
