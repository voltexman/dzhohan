@props(['trigger'])

<div x-data="{ open: false }" @click.away="open = false" {{ $attributes->class('relative w-fit') }}>
    <!-- Кнопка-тригер -->
    <x-button variant="soft" color="light" size="lg" @click="open = !open" type="button" class="gap-x-2.5">
        {{ $trigger }}
    </x-button>

    {{ $slot }}
</div>
