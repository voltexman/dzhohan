{{-- Визначаємо відступ: тільки для першого рівня (level 1) робимо зсув --}}
<div @class([
    'mt-3 px-4 py-3 rounded-r-xl border-s-2 text-sm relative transition-all shadow-sm',
    // Зсув тільки для першого рівня відповідей, далі всі в один ряд
    $level == 1 ? 'ml-5' : 'ml-2 mt-2 border-zinc-300',
    'bg-blue-50/50 border-blue-500 shadow-blue-100/50' => $reply->is_admin,
    'bg-zinc-50 border-zinc-200 shadow-zinc-100/50' => !$reply->is_admin,
]) wire:key="reply-{{ $reply->id }}">

    {{-- Якщо це відповідь на відповідь (level > 1), показуємо кому саме --}}
    @if ($level > 1)
        <div class="flex items-center gap-1.5 mb-1 text-[10px] text-zinc-400 font-medium">
            <x-lucide-corner-down-right class="size-3 stroke-zinc-400" />
            <span>відповідь для</span>
            <span class="text-zinc-600 font-bold italic">{{ $reply->parent->author_name ?? 'Гість' }}</span>
        </div>
    @endif

    <div class="flex items-center justify-between gap-2">
        <div @class([
            'font-semibold flex items-center gap-1.5',
            'text-blue-900' => $reply->is_admin,
            'text-zinc-700' => !$reply->is_admin,
        ])>
            @if ($reply->is_admin)
                <x-lucide-shield-check class="size-4 shrink-0 stroke-blue-600 fill-blue-100" />
            @else
                <x-lucide-user-round class="size-4 shrink-0 stroke-zinc-500" />
            @endif

            <span>{{ $reply->author_name ?: 'Гість' }}</span>

            @if ($reply->is_admin)
                <span
                    class="ms-1 px-1.5 py-0.5 text-[10px] uppercase tracking-wider bg-blue-600 text-white rounded font-bold">
                    Адмін
                </span>
            @endif
        </div>

        <div class="text-[10px] text-zinc-400 font-medium">
            {{ $reply->created_at->diffForHumans() }}
        </div>
    </div>

    <div @class([
        'mt-2 leading-relaxed',
        'text-blue-800/90' => $reply->is_admin,
        'text-zinc-600' => !$reply->is_admin,
    ])>
        {{ $reply->body }}
    </div>

    <button wire:click="setReply({{ $reply->id }})"
        class="text-[10px] font-medium text-zinc-400 mt-2 hover:text-zinc-800 transition-colors cursor-pointer flex items-center gap-1">
        <x-lucide-reply class="size-3" />
        Відповісти
    </button>

    {{-- РЕКУРСІЯ --}}
    @if ($reply->replies && $reply->replies->count() > 0)
        <div class="space-y-1">
            @foreach ($reply->replies as $subReply)
                @include('parts.comment-reply', ['reply' => $subReply, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div>
