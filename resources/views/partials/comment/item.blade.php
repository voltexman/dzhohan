@php
    $isReply = $isReply ?? false;
    $isAdmin = $comment->user?->hasRole('admin');
@endphp

<div @class([
    'border-b border-zinc-50 pb-5 last:border-b-0' => !$isReply,
    'ml-6 px-4 py-3 border-s-2 text-sm relative transition-all group mb-2' => $isReply,

    'bg-linear-to-r from-orange-50/80 to-transparent border-orange-500 shadow-xs' =>
        $isReply && $isAdmin,
    'bg-linear-to-r from-zinc-50 to-transparent border-zinc-300' =>
        $isReply && !$isAdmin,
])>

    {{-- HEADER тільки для основного коментаря --}}
    @if (!$isReply)
        <div class="flex items-start justify-between gap-4">
            <div class="flex items-center gap-1.5 font-semibold text-sm text-zinc-700 min-w-0">
                <x-lucide-user-round class="size-4 shrink-0" />
                <span class="truncate">{{ $comment->author_name ?: 'Гість' }}</span>
            </div>

            <div class="flex items-center gap-2.5 shrink-0">
                @if ($comment->likes_count > 0)
                    <div @class([
                        'flex items-center gap-1 font-medium',
                        $comment->isLiked() ? 'text-red-500' : 'text-zinc-400',
                    ])>
                        <x-lucide-heart class="size-3.5" />
                        <span class="text-xs">{{ $comment->likes_count }}</span>
                    </div>

                    <span class="size-1 rounded-full bg-zinc-300"></span>
                @endif

                <div class="flex items-center gap-1 text-xs text-zinc-400">
                    <span>{{ $comment->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
    @endif

    {{-- HEADER reply --}}
    @if ($isReply)
        <div class="flex items-center gap-1.5 mb-1.5 text-[10px] uppercase text-zinc-400">
            <x-lucide-corner-down-right class="size-3" />
            <span>відповідь для</span>
            <span class="font-bold italic">
                {{ $comment->parent?->user?->name ?? ($comment->parent?->author_name ?: 'Гість') }}
            </span>
            <span class="ms-auto">{{ $comment->created_at->diffForHumans() }}</span>
        </div>

        <div class="font-bold text-sm">
            {{ $comment->user?->name ?? ($comment->author_name ?: 'Гість') }}
        </div>
    @endif

    {{-- BODY --}}
    <div class="mt-2.5 text-gray-800 text-sm">
        {{ $comment->body }}
    </div>

    {{-- ACTIONS тільки для основного --}}
    @if (!$isReply)
        <div class="flex items-center gap-x-4 mt-2.5">
            <button @click="replyBlock = true" class="text-xs text-zinc-500 hover:text-zinc-800">
                Відповісти
            </button>

            <button type="button" @click="handleLike()" class="text-xs text-zinc-500">
                Подобається
            </button>
        </div>
    @endif

    {{-- REPLIES --}}
    @if (!$isReply && $comment->getAllReplies()->count())
        <div class="mt-2.5 space-y-2.5">
            @foreach ($comment->getAllReplies() as $reply)
                @include('livewire.comment.item', [
                    'comment' => $reply,
                    'isReply' => true,
                ])
            @endforeach
        </div>
    @endif

</div>
