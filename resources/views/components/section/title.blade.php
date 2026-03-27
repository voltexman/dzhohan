@props(['text', 'orange'])

<h2 class="font-[Russo_One] text-3xl text-center text-gray-900">
    {{ $text ?? $slot }} <span class="text-orange-500">{{ $orange }}</span>
</h2>
<div class="my-2.5 mx-auto w-22 h-1 bg-orange-500"></div>
