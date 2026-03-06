@props(['route'])

<a href="{{ route($route) }}" wire:navigate
    class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-600 hover:text-gray-900 data-current:text-orange-600 transition-colors duration-300">
    {{ $slot }}
</a>
