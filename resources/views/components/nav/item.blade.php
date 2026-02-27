@props(['label', 'url', 'icon' => null])

<a href="{{ route($url) }}"
    {{ $attributes->class('group-data-[variant=offcanvas]/nav:text-gray-800 group-data-[variant=inline]/nav:text-gray-100 flex-none text-lg lg:text-base uppercase font-normal lg:font-medium font-[Oswald] hover:group-data-[variant=inline]/nav:text-gray-300 hover:group-data-[variant=offcanvas]/nav:text-black data-current:text-orange-500 transition-colors duration-300') }}
    wire:navigate>
    @if ($icon)
        <x-dynamic-component :component="'lucide-' . $icon" class="inline size-4.5 mr-0.5 -mt-1" />
    @endif
    {{ $label }}
</a>
