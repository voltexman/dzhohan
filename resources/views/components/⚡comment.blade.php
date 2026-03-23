<?php

use Livewire\Component;
use App\Models\Comment;
use App\Livewire\Forms\CommentForm;

new class extends Component {
    public Comment $comment;

    public CommentForm $form;

    public bool $showReplyForm = false;
    public bool $expanded = false;

    public int $level = 0;

    public function mount(Comment $comment, int $level = 0)
    {
        $this->comment = $comment;
        $this->level = $level;

        $this->comment->load([
            'replies' => fn($q) => $q
                ->where('is_active', true)
                ->with(['likes', 'user'])
                ->popular(),
        ]);

        $this->comment->loadCount(['likes', 'replies', 'descendants as descendants_count' => fn($q) => $q->where('is_active', true)]);
    }

    public function toggleLike()
    {
        $this->comment->isLiked() ? $this->comment->unlike() : $this->comment->like();

        $this->comment->loadCount(['likes', 'replies', 'descendants as descendants_count' => fn($q) => $q->where('is_active', true)]);
    }

    public function toggleReply()
    {
        $this->showReplyForm = !$this->showReplyForm;

        $this->comment->loadCount(['likes', 'replies', 'descendants as descendants_count' => fn($q) => $q->where('is_active', true)]);

        if (!$this->showReplyForm) {
            $this->form->reset('replyBody');
        }
    }

    public function toggleExpanded()
    {
        $this->expanded = !$this->expanded;
    }

    public function sendReply()
    {
        $this->form->validateOnly('replyBody');

        $this->comment->replies()->create([
            'commentable_id' => $this->comment->commentable_id,
            'commentable_type' => $this->comment->commentable_type,
            'parent_id' => $this->comment->id,
            'body' => $this->form->replyBody,
            'author_name' => Auth::user()?->name ?? (filled($this->form->author_name) ? trim($this->form->author_name) : null),
            'user_id' => Auth::id(),
            'ip_address' => request()->ip(),
        ]);

        $this->reset('form.replyBody', 'showReplyForm');

        $this->comment->load([
            'replies' => fn($q) => $q
                ->where('is_active', true)
                ->with(['likes', 'replies', 'user'])
                ->popular(),
            'likes',
        ]);

        $this->comment->loadCount(['likes', 'replies', 'descendants as descendants_count' => fn($q) => $q->where('is_active', true)]);
    }
};
?>

<div @class([
    'relative',
    'border-b border-zinc-100 last:border-b-0 pb-5' => $level === 0,
    'mt-5' => $level === 0,
]) wire:transition>

    <!-- Автор + дата -->
    @if ($comment->parent_id && $level > 0)
        <div class="text-xs select-none line-clamp-1 mb-0.5 italic">
            <span class="text-zinc-400">Відповідь для: </span>
            <span class="text-zinc-600 font-semibold">{{ $comment->parent?->author_name ?: 'Гість' }}</span>
        </div>
    @endif
    <div class="flex justify-between items-start gap-2.5">
        <div class="flex justify-center items-center gap-1.5">
            {{-- Аватар / іконка --}}
            @if ($comment->user)
                {{-- Зареєстрований користувач --}}

                @if ($comment->user->hasRole('admin') && $comment->user->avatar_url)
                    {{-- Адмін --}}
                    <div class="size-7 shrink-0 rounded-full overflow-hidden border border-zinc-600">
                        <img src="{{ Storage::disk('public')->url($comment->user->avatar_url) }}" alt="Admin"
                            class="w-full h-full object-cover" />
                    </div>
                @elseif ($comment->user->avatar_url)
                    {{-- Звичайний користувач --}}
                    <div class="size-7 shrink-0 rounded-full overflow-hidden border border-zinc-200">
                        <img src="{{ Storage::disk('public')->url($comment->user->avatar_url) }}" alt="User"
                            class="w-full h-full object-cover" />
                    </div>
                @else
                    {{-- Користувач без аватарки --}}
                    <div
                        class="size-7 shrink-0 flex justify-center items-center rounded-full bg-zinc-100 border border-zinc-200">
                        <x-lucide-user-round class="size-3.5 stroke-zinc-800" />
                    </div>
                @endif
            @else
                {{-- Гість --}}

                @if (!empty($comment->author_name))
                    {{-- ✅ ТІЛЬКИ тут генерується аватар --}}
                    <div class="size-7 rounded-full overflow-hidden border border-zinc-100">
                        <img src="{{ Avatar::create($comment->author_name)->setFont(public_path('fonts/Roboto-Bold.ttf'))->toBase64() }}"
                            alt="{{ $comment->author_name }}" class="w-full h-full object-cover" />
                    </div>
                @else
                    {{-- ❌ Гість без імені — НІЯКИХ Avatar::create --}}
                    <div
                        class="size-7 shrink-0 flex justify-center items-center rounded-full bg-zinc-100 border border-zinc-200">
                        <x-lucide-user-round class="size-3.5 stroke-zinc-800" />
                    </div>
                @endif
            @endif

            {{-- Ім’я користувача / бейдж admin --}}
            <div @class([
                'line-clamp-1 px-1.5 py-0.5 rounded-sm',
                'bg-black text-xs font-medium text-white' => $comment->user?->hasRole(
                    'admin'),
                'text-sm font-semibold text-zinc-800' => !$comment->user?->hasRole('admin'),
            ])>
                {{ $comment->author_name ?: 'Гість' }}
            </div>
            @if ($comment->user?->hasRole('admin'))
                <span
                    class="inline-flex items-center gap-0.5 text-xs font-medium bg-orange-100 text-orange-600 px-1.5 py-0.5 rounded-sm">
                    <x-lucide-shield-check class="size-3.5 shrink-0" />
                    Майстер
                </span>
            @endif
        </div>

        <div class="flex justify-center items-center self-center gap-1 text-xs text-zinc-400 whitespace-nowrap">
            @if ($comment->created_at->gt(now()->subDays(1)))
                <x-lucide-clock class="size-3.5 shrink-0 fill-zinc-100 stroke-zinc-500" />
                <span>{{ $comment->created_at->diffForHumans() }}</span>
            @else
                <x-lucide-calendar-days class="size-3.5 shrink-0 fill-zinc-100 stroke-zinc-500" />
                <span>{{ $comment->created_at->format('d.m.Y') }}</span>
            @endif
        </div>
    </div>

    <!-- Текст -->
    <div class="mt-1.5 text-zinc-800 leading-relaxed">
        {{ $comment->body }}
    </div>

    <!-- Кнопки: відповісти + лайк -->
    <div class="mt-2.5 flex items-center gap-2.5 text-xs">
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
            <span>{{ $comment->isLiked() ? 'Подобається' : 'Вподобати' }}</span>
        </button>

        @if ($comment->likes_count > 0 || $comment->replies->isNotEmpty())
            <div class="ms-auto flex items-center gap-1.5">
                @if ($comment->replies->isNotEmpty())
                    <div class="flex items-center gap-1 ms-auto">
                        <x-lucide-messages-square class="size-4 fill-zinc-100 stroke-zinc-500" />
                        <span class="text-zinc-400">{{ $comment->replies_count }}</span>
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
        @endif
    </div>

    <div wire:show="showReplyForm" class="flex flex-col gap-5 mt-5 group" wire:transition x-cloak>
        <div class="text-sm">
            <span class="text-zinc-500 font-medium">Я: </span>
            <span class="text-zinc-700 font-semibold">{{ $this->form->author_name }}</span>
        </div>
        <textarea wire:model="form.replyBody" x-ref="replyText" rows="1"
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
        <div>
            @if ($comment->replies_count > 0 && $level === 0)
                <button wire:click="expanded = !expanded"
                    class="mt-2.5 flex items-center gap-1 text-xs font-semibold text-orange-600 hover:text-orange-700 cursor-pointer transition-colors">
                    <x-lucide-chevron-down class="size-4 transition-transform duration-300" ::class="$wire.expanded ? 'rotate-180' : ''" />
                    <span
                        wire:text="expanded ? 'Приховати' : 'Обговорення ({{ $comment->descendants_count }})'"></span>
                </button>
            @endif

            <div @if ($level === 0) wire:show="expanded" x-collapse @endif
                class="mt-5 space-y-5 {{ $level === 0 ? 'ml-10' : 'ml-0' }}">
                @foreach ($comment->replies as $reply)
                    <livewire:comment :comment="$reply" :level="$level + 1" wire:key="comment-{{ $reply->id }}" />
                @endforeach
            </div>
        </div>
    @endif
</div>
