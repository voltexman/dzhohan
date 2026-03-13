@props(['trigger'])

<div x-data="{ open: false }" @click.away="open = false" {{ $attributes->class('relative w-fit') }}>
    <!-- Кнопка-тригер -->
    <div @click="open = !open">
        {{ $trigger }}
    </div>

    {{ $slot }}
</div>
