@props([
    'icon' => null,
    'tag' => 'h3',
])

<{{ $tag }}
    {{ $attributes->merge(['class' => 'text-xl font-[Oswald] font-bold text-zinc-900 flex items-center gap-2.5']) }}>
    @if ($icon)
        <x-dynamic-component :component="'lucide-' . $icon" class="size-5.5 text-orange-500 shrink-0" />
    @endif

    <span>{{ $slot }}</span>

    @if (isset($badge))
        <span class="px-2.5 py-0.5 bg-zinc-100 text-zinc-400 text-sm rounded-full font-sans font-medium shrink-0">
            {{ $badge }}
        </span>
    @endif
    </{{ $tag }}>
