@props(['route'])

<a {{ $attributes->class('inline-flex items-center gap-x-1.5 px-2.5 py-2.5 text-gray-600 rounded-md data-current:bg-orange-100 data-current:text-orange-800 hover:bg-gray-50 hover:text-gray-700') }}
    href="{{ route($route) }}" wire:navigate>
    {{ $slot }}
</a>
