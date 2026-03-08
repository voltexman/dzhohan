<a href="{{ route('products', ['filters[tags][]' => $tag->id]) }}"
    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-sm bg-zinc-100 hover:bg-zinc-200/80 border border-zinc-200 transition-colors group">

    <x-lucide-tag class="size-3.5 text-zinc-500 group-hover:text-zinc-900 transition-colors" />

    <span class="text-xs font-medium text-zinc-700 group-hover:text-zinc-900">
        {{ $tag->name }}
    </span>
</a>
