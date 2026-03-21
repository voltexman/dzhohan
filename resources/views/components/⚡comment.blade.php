<?php

use Livewire\Component;
use App\Models\Comment;
use Livewire\Attributes\Validate;

new class extends Component {
    public Comment $comment;

    public bool $showReplyForm = false;

    public int $level = 0;

    #[Validate('required|min:3|max:2000')]
    public string $replyBody = '';

    public function mount(Comment $comment, int $level = 0)
    {
        $this->comment = $comment;
        $this->level = $level;
    }

    public function toggleLike()
    {
        $this->comment->isLiked() ? $this->comment->unlike() : $this->comment->like();

        $this->comment->loadCount('likes');
    }

    public function toggleReply()
    {
        $this->showReplyForm = !$this->showReplyForm;

        $this->comment->loadCount('likes');

        if (!$this->showReplyForm) {
            $this->reset('replyBody');
        }
    }

    public function sendReply()
    {
        $this->validateOnly('replyBody');

        $this->comment->replies()->create([
            'commentable_id' => $this->comment->commentable_id,
            'commentable_type' => $this->comment->commentable_type,
            'parent_id' => $this->comment->id,
            'body' => $this->replyBody,
            'author_name' => Auth::user()?->name ?? 'Гість',
            'user_id' => Auth::id(),
            'ip_address' => request()->ip(),
        ]);

        $this->reset('replyBody', 'showReplyForm');
        $this->comment->loadCount('likes');
        $this->comment->load('replies'); // одразу показуємо нову відповідь
        // $this->dispatch('comment-added'); // якщо хочеш оновити головний список
    }
};
?>

<div wire:key="comment-{{ $comment->id }}" class="border-b border-zinc-100 pb-5 last:border-b-0" wire:transition>

    <!-- Автор + дата -->
    <div class="flex justify-between items-start gap-4">
        <div class="flex items-center gap-2 font-medium text-zinc-700">
            <x-lucide-user-round class="size-4" />
            {{ $comment->author_name ?: 'Гість' }}
            @if ($comment->user?->hasRole('admin'))
                <span class="text-xs bg-orange-600 text-white px-1.5 py-0.5 rounded">Майстер</span>
            @endif
        </div>

        <div class="flex items-center gap-1 text-xs text-zinc-400 whitespace-nowrap">
            @if ($comment->created_at->gt(now()->subDays(1)))
                {{-- Свіжий коментар: іконка годинника --}}
                <x-lucide-clock class="size-3.5 shrink-0 mb-0.5 fill-zinc-100 stroke-zinc-500" />
                <span>{{ $comment->created_at->diffForHumans() }}</span>
            @else
                {{-- Старий коментар: іконка календаря --}}
                <x-lucide-calendar-days class="size-3.5 shrink-0 mb-0.5 fill-zinc-100 stroke-zinc-500" />
                <span>{{ $comment->created_at->format('d.m.Y') }}</span>
            @endif
        </div>
    </div>

    <!-- Текст -->
    <div class="mt-2 text-zinc-800 leading-relaxed">
        {{ $comment->body }}
    </div>

    <!-- Кнопки: відповісти + лайк -->
    <div class="mt-5 flex items-center gap-2.5 text-xs">
        <button @click="$wire.toggleReply()"
            class="flex items-center gap-1.5 cursor-pointer text-zinc-500 hover:text-zinc-700 transition">
            <x-lucide-reply class="size-4" />
            Відповісти
        </button>
        <button wire:click="toggleLike" @class([
            'flex items-center gap-1.5 cursor-pointer transition-colors',
            'text-red-500' => $comment->isLiked(),
            'text-zinc-500' => !$comment->isLiked(),
        ])>
            <x-lucide-heart @class([
                'size-4 transition-all',
                'fill-red-500 stroke-red-500' => $comment->isLiked(),
                'fill-zinc-100 stroke-zinc-500' => !$comment->isLiked(),
            ]) />
            <span>Подобається</span>
        </button>

        @if ($comment->replies->isNotEmpty())
            <div class="flex items-center gap-1.5 ms-auto">
                <x-lucide-messages-square class="size-4 fill-zinc-100 stroke-zinc-500" />
                <span class="text-zinc-500">{{ $comment->replies_count }}</span>
            </div>
        @endif

        @if ($comment->likes_count > 0 && $comment->replies->isNotEmpty())
            <span class="size-1 shrink-0 rounded-full bg-zinc-300"></span>
        @endif

        @if ($comment->likes_count > 0)
            <div @class([
                'flex items-center gap-1 font-medium transition-colors duration-200',
                $comment->isLiked() ? 'text-red-500' : 'text-zinc-400',
            ])>
                <x-lucide-heart @class([
                    'size-3.5 shrink-0 mb-0.5 transition-all',
                    'fill-red-500 stroke-red-500 scale-110' => $comment->isLiked(),
                    'fill-zinc-100 stroke-zinc-500' => !$comment->isLiked(),
                ]) />
                <span class="text-xs">{{ $comment->likes_count }}</span>
            </div>
        @endif
    </div>

    <div wire:show="showReplyForm" class="flex flex-col gap-5 mt-5 group" wire:transition x-cloak>
        <textarea wire:model="replyBody" x-ref="replyText" rows="1"
            @input="$el.style.height = '32px'; $el.style.height = $el.scrollHeight + 'px'" placeholder="Введіть відповідь..."
            class="w-full bg-transparent overflow-y-hidden border-0 border-b-2 border-zinc-200 py-0 px-0 text-sm leading-8 focus:ring-0 focus:border-black transition-colors duration-300 resize-none placeholder:text-zinc-500 outline-none box-border"
            style="height: 32px; min-height: 32px;"></textarea>

        <div class="flex justify-end gap-1.5">
            <button wire:click="toggleReply" type="button"
                class="px-5 py-2 text-sm font-medium text-zinc-600 hover:bg-zinc-100 rounded-md transition cursor-pointer">
                Скасувати
            </button>

            <button type="button" wire:click="sendReply" wire:loading.attr="disabled" wire:target="sendReply"
                class="px-5 py-2 text-sm font-medium text-white bg-zinc-900 hover:bg-zinc-800 rounded-md transition disabled:opacity-30 disabled:cursor-not-allowed cursor-pointer">
                <span wire:loading.remove wire:target="sendReply">Відповісти</span>
                <span wire:loading wire:target="sendReply">Надсилаю...</span>
            </button>
        </div>
    </div>

    @if ($comment->replies->isNotEmpty())
        <div class="mt-5 space-y-5 {{ $level === 0 ? 'ml-10' : 'ml-0' }}">
            @foreach ($comment->replies as $reply)
                <livewire:comment :comment="$reply" :level="$level + 1" wire:key="comment-{{ $reply->id }}" />
            @endforeach
        </div>
    @endif
</div>
