@props(['text'])

<div class="max-w-sm mx-auto text-center text-gray-700 text-sm">
    {{ $text ?? $slot }}
</div>
