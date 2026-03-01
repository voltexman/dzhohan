@props(['text'])

<div {{ $attributes->class('text-gray-800 font-semibold text-sm') }}>
    {{ $text ?? $slot }}
</div>
