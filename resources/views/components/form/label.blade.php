@props(['text'])

<div {{ $attributes->class('text-gray-700 font-semibold text-sm') }}>
    {{ $text ?? $slot }}
</div>
