@php
    // Використовуємо $comment як універсальну змінну для головного і для репліїв
    $isAdmin = $comment->user?->hasRole('admin');
    $isReply = $isReply ?? false;
@endphp

<div x-data="{
    active: @entangle('isLiked'),
    {{-- Використовуємо унікальну сутність для кожного рівня --}}
    count: {{ $comment->likes_count ?? 0 }},
    handleLike() {
        this.active = !this.active;
        this.active ? this.count++ : this.count--;
        $wire.like();
        {{-- Переконайтеся, що в Livewire компоненті лайк прив'язаний до ID --}}
    },
    replyBlock: false
}" {{-- Важливо для коректної роботи Livewire при рекурсії --}} wire:key="comment-{{ $comment->id }}" @class([
    'pb-5 last:border-b-0 transition-all duration-300',
    // Зсув та стилі для вкладених відповідей
    'ml-6 px-4 py-3 border-s-2 mb-2 bg-linear-to-r from-zinc-50 to-transparent border-zinc-300 text-sm' =>
        $isReply && !$isAdmin,
    'ml-6 px-4 py-3 border-s-2 mb-2 bg-linear-to-r from-orange-50/80 to-transparent border-orange-500 text-sm' =>
        $isReply && $isAdmin,
    // Стиль для головного коментаря
    'border-b border-zinc-100' => !$isReply,
])
    x-cloak>

    <div class="flex items-start justify-between gap-4">
        <div class="flex flex-col gap-1 min-w-0">
            {{-- Якщо це відповідь, показуємо кому --}}
            @if ($isReply)
                <div class="flex items-center gap-1.5 text-[10px] font-medium uppercase text-zinc-400">
                    <x-lucide-corner-down-right class="size-3" />
                    <span>відповідь для</span>
                    <span class="font-bold italic truncate">{{ $comment->parent?->author_name ?: 'Гість' }}</span>
                </div>
            @endif

            <div class="flex items-center gap-1.5 font-semibold text-sm text-zinc-700">
                @if ($isAdmin)
                    <x-lucide-shield-check class="size-4 shrink-0 stroke-orange-600 fill-orange-100" />
                @else
                    <x-lucide-user-round class="size-4 shrink-0" />
                @endif
                <span class="truncate">{{ $comment->author_name ?: 'Гість' }}</span>
            </div>
        </div>

        {{-- Час --}}
        <div class="flex items-center gap-1 text-xs text-zinc-400 shrink-0">
            @if ($comment->created_at->gt(now()->subDay()))
                <x-lucide-clock class="size-3.5 fill-zinc-100 stroke-zinc-500" />
                <span>{{ $comment->created_at->diffForHumans() }}</span>
            @else
                <x-lucide-calendar-days class="size-3.5 fill-zinc-100 stroke-zinc-500" />
                <span>{{ $comment->created_at->format('d.m.Y') }}</span>
            @endif
        </div>
    </div>

    <div class="mt-2.5 text-gray-800">{{ $comment->body }}</div>

    <div class="flex items-center justify-between gap-x-4 mt-2.5">
        <div class="flex items-center gap-4">
            <button @click="replyBlock = true; $nextTick(() => $refs.replyText.focus())"
                class="flex items-center gap-1.5 text-xs font-medium text-zinc-500 hover:text-zinc-800 cursor-pointer transition-all">
                <x-lucide-reply class="size-4" />
                <span>Відповісти</span>
            </button>

            <button type="button" @click="handleLike()"
                class="flex items-center gap-1.5 text-xs font-medium transition-colors cursor-pointer"
                x-bind:class="active ? 'text-red-600' : 'text-zinc-500 hover:text-zinc-800'">
                <x-lucide-heart class="size-4 transition-all"
                    x-bind:class="active ? 'fill-red-600 stroke-red-600 scale-110' : 'stroke-zinc-500'" />
                <span x-text="count > 0 ? count : 'Подобається'"></span>
            </button>
        </div>
    </div>

    {{-- Форма відповіді --}}
    <div x-data="{
        text: @entangle('replyText'),
        submit() {
            if (this.text.trim().length < 3) return;
            $wire.sendReply({{ $comment->id }}).then(() => {
                this.replyBlock = false;
                this.text = '';
            });
        }
    }" x-show="replyBlock" class="mt-5" wire:ignore>
        <textarea x-model="text" x-ref="replyText" rows="1"
            @input="$el.style.height = '32px'; $el.style.height = $el.scrollHeight + 'px'" placeholder="Введіть відповідь..."
            class="w-full bg-transparent border-0 border-b-2 border-zinc-200 py-2 focus:ring-0 focus:border-black transition-all resize-none outline-none"
            style="height: 32px;"></textarea>
        <div class="flex justify-end gap-1.5 mt-2">
            <button @click="replyBlock = false" class="px-4 py-1.5 text-xs text-zinc-500">Скасувати</button>
            <button @click="submit()" class="px-4 py-1.5 text-xs bg-zinc-900 text-white rounded-sm">Відповісти</button>
        </div>
    </div>

    {{-- РЕКУРСИВНИЙ ВИКЛИК --}}
    @if ($comment->replies->isNotEmpty())
        <div class="mt-5 space-y-2.5">
            @foreach ($comment->replies as $reply)
                @include('partials.comment.item', ['comment' => $reply, 'isReply' => true])
            @endforeach
        </div>
    @endif
</div>
