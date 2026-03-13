<?php

use Livewire\Component;
use App\Models\Comment;

new class extends Component {
    public Comment $comment;

    public bool $isLiked = false;

    public function mount(Comment $comment)
    {
        $this->comment = $comment;
        $this->isLiked = $comment->isLiked();
    }

    public function like()
    {
        $this->isLiked ? $this->comment->like() : $this->comment->unlike();
    }
};
?>

<div x-data="{
    active: @entangle('isLiked'),
    count: {{ $comment->likes_count ?? 0 }},
    handleLike() {
        this.active = !this.active;
        this.active ? this.count++ : this.count--;
        $wire.like();
    }
}" class="border-b border-zinc-50 pb-5 last:border-b-0" wire:transition>

    <div class="flex items-start justify-between gap-4">
        {{-- Ім'я автора --}}
        <div class="flex items-center gap-1.5 font-semibold text-sm text-zinc-700 min-w-0">
            <x-lucide-user-round class="size-4 shrink-0" />
            <span class="truncate">{{ $comment->author_name ?: 'Гість' }}</span>
        </div>

        {{-- Мета-дані (Лайки та Час) --}}
        <div class="flex items-center gap-2.5 shrink-0">
            {{-- Блок Лайків --}}
            {{-- Блок Лайків зверху --}}
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

                {{-- Роздільник --}}
                <span class="size-1 rounded-full bg-zinc-300"></span>
            @endif

            {{-- Блок Часу --}}
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
    </div>

    <div class="mt-2.5 text-gray-800">{{ $comment->body }}</div>

    <button wire:click="setReply({{ $comment->id }})"
        class="text-xs font-medium text-zinc-500 mt-2.5 hover:text-zinc-800 transition-colors duration-250 cursor-pointer">
        <x-lucide-reply class="size-4 inline-flex mb-1" />
        Відповісти
    </button>

    <button type="button" @click="handleLike()"
        class="text-xs font-medium mt-2.5 ms-2.5 transition-colors duration-250 cursor-pointer focus:outline-none flex items-center gap-1.5"
        x-bind:class="active ? 'text-red-600 hover:text-red-800' : 'text-zinc-500 hover:text-zinc-800'">
        <x-lucide-heart class="size-4 transition-all duration-300"
            x-bind:class="active ? 'fill-red-600 stroke-red-600 scale-110' : 'stroke-zinc-500'" />
        <span>Подобається</span>
    </button>

    {{-- ПЛОСКИЙ СПИСОК ВСІХ ВІДПОВІДЕЙ --}}
    <div class="mt-2.5 space-y-2.5">
        @foreach ($comment->getAllReplies() as $reply)
            <div wire:key="reply-{{ $reply->id }}" @class([
                'ml-6 px-4 py-3 border-s-2 text-sm relative transition-all group',
                // Перевірка ролі через Spatie прямо в Blade
                'bg-orange-50/50 border-orange-500' => $reply->user?->hasRole('admin'),
                'bg-zinc-50 border-zinc-200' => !$reply->user?->hasRole('admin'),
            ]) x-cloak>
                {{-- Контекст: кому саме відповідають --}}
                <div class="flex items-center gap-1.5 mb-1.5 text-[11px] font-medium text-zinc-400">
                    <x-lucide-corner-down-right class="size-3 stroke-zinc-400" />
                    <div class="shrink-0">відповідь для</div>
                    <div class="text-zinc-600 font-bold italic line-clamp-1">
                        {{-- Беремо ім'я батьківського юзера або ім'я гостя --}}
                        {{ $reply->parent?->user?->name ?? ($reply->parent?->author_name ?: 'Гість') }}
                    </div>
                </div>

                <div class="flex items-center justify-between gap-2">
                    <div @class([
                        'font-semibold flex items-center gap-1.5',
                        'text-orange-900' => $reply->user?->hasRole('admin'),
                        'text-zinc-700' => !$reply->user?->hasRole('admin'),
                    ])>
                        @if ($reply->user?->hasRole('admin'))
                            <x-lucide-shield-check class="size-4 shrink-0 stroke-orange-600 fill-orange-100" />
                        @else
                            <x-lucide-user-round class="size-4 shrink-0 stroke-zinc-500" />
                        @endif

                        {{-- Пріоритет: ім'я зареєстрованого юзера -> ім'я гостя -> "Гість" --}}
                        <span>{{ $reply->user?->name ?? ($reply->author_name ?: 'Гість') }}</span>

                        @if ($reply->user?->hasRole('admin'))
                            <span
                                class="ms-1 px-1.5 py-0.5 text-[10px] uppercase tracking-wider bg-orange-600/15 text-orange-600 rounded-full font-bold">
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
                    'text-orange-800/90' => $reply->user?->hasRole('admin'),
                    'text-zinc-600' => !$reply->user?->hasRole('admin'),
                ])>
                    {{ $reply->body }}
                </div>

                {{-- Кнопка відповіді --}}
                <button wire:click="setReply({{ $reply->id }})"
                    class="text-[10px] font-medium text-zinc-400 mt-2.5 hover:text-zinc-800 transition-colors cursor-pointer flex items-center gap-1">
                    <x-lucide-reply class="size-3" />
                    Відповісти
                </button>
            </div>
        @endforeach
    </div>
</div>
