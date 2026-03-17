@php
    $isReply = $isReply ?? false;
    $c = $comment;
    $isAdmin = $c->user?->hasRole('admin');
@endphp

<div x-data="{
    active: @entangle('isLiked'),
    count: {{ $c->likes_count ?? 0 }},
    handleLike() {
        this.active = !this.active;
        this.active ? this.count++ : this.count--;
        $wire.like();
    },
    replyBlock: false
}" @class([
    'border-b border-zinc-50 pb-5 last:border-b-0' => !$isReply,
    'ml-6 px-4 py-3 border-s-2 text-sm relative transition-all group mb-2' => $isReply,

    'bg-linear-to-r from-orange-50/80 to-transparent border-orange-500 shadow-xs' =>
        $isReply && $isAdmin,
    'bg-linear-to-r from-zinc-50 to-transparent border-zinc-300' =>
        $isReply && !$isAdmin,
])>

    {{-- HEADER (основний) --}}
    @if (!$isReply)
        <div class="flex items-start justify-between gap-4">
            <div class="flex items-center gap-1.5 font-semibold text-sm text-zinc-700 min-w-0">
                <x-lucide-user-round class="size-4 shrink-0" />
                <span class="truncate">{{ $c->author_name ?: 'Гість' }}</span>
            </div>

            <div class="flex items-center gap-2.5 shrink-0">
                @if ($c->likes_count > 0)
                    <div @class([
                        'flex items-center gap-1 font-medium transition-colors duration-200',
                        $c->isLiked() ? 'text-red-500' : 'text-zinc-400',
                    ])>
                        <x-lucide-heart @class([
                            'size-3.5 shrink-0 mb-0.5 transition-all',
                            'fill-red-500 stroke-red-500 scale-110' => $c->isLiked(),
                            'fill-zinc-100 stroke-zinc-500' => !$c->isLiked(),
                        ]) />
                        <span class="text-xs">{{ $c->likes_count }}</span>
                    </div>

                    <span class="size-1 rounded-full bg-zinc-300"></span>
                @endif

                <div class="flex items-center gap-1 text-xs text-zinc-400 whitespace-nowrap">
                    @if ($c->created_at->gt(now()->subDays(1)))
                        <x-lucide-clock class="size-3.5 shrink-0 mb-0.5 fill-zinc-100 stroke-zinc-500" />
                        <span>{{ $c->created_at->diffForHumans() }}</span>
                    @else
                        <x-lucide-calendar-days class="size-3.5 shrink-0 mb-0.5 fill-zinc-100 stroke-zinc-500" />
                        <span>{{ $c->created_at->format('d.m.Y') }}</span>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- HEADER reply --}}
    @if ($isReply)
        <div @class([
            'flex items-center gap-1.5 mb-1.5 text-[10px] font-medium uppercase tracking-tight',
            'text-orange-400' => $isAdmin,
            'text-zinc-400' => !$isAdmin,
        ])>
            <x-lucide-corner-down-right class="size-3 stroke-current" />
            <div class="shrink-0 opacity-70">відповідь для</div>
            <div @class([
                'font-bold italic line-clamp-1',
                'text-orange-700' => $isAdmin,
                'text-zinc-600' => !$isAdmin,
            ])>
                {{ $c->parent?->user?->name ?? ($c->parent?->author_name ?: 'Гість') }}
            </div>
            <div class="ms-auto text-[10px] opacity-60 font-medium whitespace-nowrap">
                {{ $c->created_at->diffForHumans() }}
            </div>
        </div>

        <div class="flex items-center justify-between gap-2">
            <div @class([
                'font-bold flex items-center gap-1.5',
                'text-orange-900' => $isAdmin,
                'text-zinc-800' => !$isAdmin,
            ])>
                @if ($isAdmin)
                    <x-lucide-shield-check class="size-4 shrink-0 stroke-orange-600 fill-orange-100" />
                @else
                    <x-lucide-user-round class="size-4 shrink-0 stroke-zinc-400" />
                @endif

                <span>{{ $c->user?->name ?? ($c->author_name ?: 'Гість') }}</span>

                @if ($isAdmin)
                    <span
                        class="ms-1 px-2 py-0.5 text-[9px] uppercase tracking-widest bg-orange-600 text-white rounded-sm font-black">
                        Майстер
                    </span>
                @endif
            </div>
        </div>
    @endif

    {{-- BODY --}}
    <div @class([
        'mt-2.5 text-gray-800' => !$isReply,
        'mt-2 leading-relaxed text-[13px]' => $isReply,
        'text-orange-900/80' => $isReply && $isAdmin,
        'text-zinc-600' => $isReply && !$isAdmin,
    ])>
        {{ $c->body }}
    </div>

    {{-- ACTIONS --}}
    @if (!$isReply)
        <div class="flex items-center gap-x-4 mt-2.5">

            <button @click="
            replyBlock = true;
            $wire.setReply({{ $c->id }});
        "
                class="text-[10px] font-bold mt-3 transition-colors cursor-pointer flex items-center gap-1.5 uppercase tracking-wider text-zinc-400 hover:text-zinc-800">

                <x-lucide-reply class="size-3" />
                Відповісти
            </button>

            <button type="button" @click="handleLike()"
                class="flex items-center gap-1.5 text-xs font-medium transition-colors duration-250 cursor-pointer"
                x-bind:class="active ? 'text-red-600 hover:text-red-800' : 'text-zinc-500 hover:text-zinc-800'">
                <x-lucide-heart class="size-4 transition-all duration-300"
                    x-bind:class="active ? 'fill-red-600 stroke-red-600 scale-110' : 'stroke-zinc-500'" />
                <span>Подобається</span>
            </button>
        </div>
    @endif

    {{-- REPLY LIST --}}
    @if (!$isReply)
        <div class="mt-2.5 space-y-2.5">
            @foreach ($c->getAllReplies() as $reply)
                @include('partials.comment.item', [
                    'comment' => $reply,
                    'isReply' => true,
                ])
            @endforeach
        </div>
    @endif

</div>
