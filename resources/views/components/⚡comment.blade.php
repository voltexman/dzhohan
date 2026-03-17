<?php

use Livewire\Component;
use App\Models\Comment;
use Livewire\Attributes\Validate;

new class extends Component {
    public Comment $comment;

    public bool $isLiked = false;

    #[Validate('required|min:3|max:2000')]
    public string $replyText = ''; // Окрема змінна для тексту відповіді

    public function mount(Comment $comment)
    {
        $this->comment = $comment;
        $this->isLiked = $comment->isLiked();
    }

    public function like()
    {
        $this->isLiked ? $this->comment->like() : $this->comment->unlike();
    }

    public function sendReply()
    {
        $this->validateOnly('replyText');

        $user = auth()->user();

        // Створюємо відповідь через зв'язок replies()
        $this->comment->replies()->create([
            'commentable_id' => $this->comment->commentable_id,
            'commentable_type' => $this->comment->commentable_type,
            'body' => $this->replyText,
            'user_id' => $user?->id,
            'ip_address' => request()->ip(),
            'author_name' => $user?->hasRole('admin') ? 'Адміністратор' : $user?->name ?? 'Гість',
        ]);

        $this->reset('replyText');

        // Подія для батьківського компонента, щоб він оновив список (якщо потрібно)
        $this->dispatch('comment-added');
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
    },
    replyBlock: false
}" class="border-b border-zinc-50 pb-5 last:border-b-0" wire:transition x-cloak>

    <div class="flex items-start justify-between gap-4">
        {{-- Ім'я автора --}}
        <div class="flex items-center gap-1.5 font-semibold text-sm text-zinc-700 min-w-0">
            <x-lucide-user-round class="size-4 shrink-0" />
            <span class="truncate">{{ $comment->author_name ?: 'Гість' }}</span>
        </div>

        {{-- Мета-дані (Лайки та Час) --}}
        <div class="flex items-center gap-2.5 shrink-0">
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

    <div class="flex items-center justify-between gap-x-4 mt-2.5">
        {{-- Кнопка Відповісти --}}
        <button @click="replyBlock = true" :class="{ 'animate-pulse text-black font-bold': replyBlock }"
            class="flex items-center gap-1.5 text-xs font-medium text-zinc-500 hover:text-zinc-800 cursor-pointer transition-all duration-300 focus:outline-none">
            <x-lucide-reply class="size-4" />
            <span>Відповісти</span>
        </button>

        {{-- Кнопка Подобається --}}
        <button type="button" @click="handleLike()"
            class="me-auto flex items-center gap-1.5 text-xs font-medium transition-colors duration-250 cursor-pointer focus:outline-none"
            x-bind:class="active ? 'text-red-600 hover:text-red-800' : 'text-zinc-500 hover:text-zinc-800'">
            <x-lucide-heart class="size-4 transition-all duration-300"
                x-bind:class="active ? 'fill-red-600 stroke-red-600 scale-110' : 'stroke-zinc-500'" />
            <span>Подобається</span>
        </button>

        {{-- Блок Лайків --}}
        <div class="ms-auto flex items-center gap-2.5">
            {{-- Блок відповідей --}}
            @if ($comment->replies->isNotEmpty())
                <div class="flex items-center gap-0.5">
                    <x-lucide-message-circle class="size-3.5 shrink-0 fill-zinc-100 stroke-zinc-500 mb-0.5" />
                    <span class="text-xs font-medium text-zinc-400">{{ $comment->replies->count() }}</span>
                </div>
            @endif

            {{-- Роздільник: показуємо тільки якщо є і лайки, і відповіді --}}
            @if ($comment->likes_count > 0 && $comment->replies->isNotEmpty())
                <span class="size-1 shrink-0 rounded-full bg-zinc-300"></span>
            @endif

            {{-- Блок лайків --}}
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
    </div>

    <div x-data="{
        text: @entangle('replyText'),
        submit() {
            if (this.text.trim().length < 3) return;
    
            $wire.sendReply().then(() => {
                this.replyBlock = false;
                // Скидаємо висоту після відправки
                this.$refs.replyText.style.height = '32px';
            });
        }
    }" x-show="replyBlock" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2" class="mt-5" wire:ignore>

        <div class="flex flex-col gap-5 group">
            <!-- Поле вводу: залишаємо тільки x-model='text' -->
            <textarea x-model="text" x-ref="replyText" rows="1"
                @input="$el.style.height = '32px'; $el.style.height = $el.scrollHeight + 'px'" placeholder="Введіть відповідь..."
                class="w-full bg-transparent overflow-y-hidden border-0 border-b-2 border-zinc-200 py-0 px-0 text-sm leading-8 focus:ring-0 focus:border-black transition-colors duration-300 resize-none placeholder:text-zinc-500 outline-none box-border"
                style="height: 32px; min-height: 32px;"></textarea>

            <!-- Кнопки керування -->
            <div class="flex justify-end gap-1.5">
                <button @click="replyBlock = false; text = ''; $refs.replyText.style.height = '32px'" type="button"
                    class="px-5 py-2 text-sm font-medium text-zinc-600 hover:bg-zinc-100 rounded-sm transition cursor-pointer">
                    Скасувати
                </button>

                <button type="button" @click="submit()" :disabled="text.trim().length < 3" wire:loading.attr="disabled"
                    wire:target="sendReply"
                    class="px-5 py-2 text-sm font-medium text-white bg-zinc-900 hover:bg-zinc-800 rounded-sm transition disabled:opacity-30 disabled:cursor-not-allowed cursor-pointer">
                    <span wire:loading.remove wire:target="sendReply">Відповісти</span>
                    <span wire:loading wire:target="sendReply">Надсилаю...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ПЛОСКИЙ СПИСОК ВСІХ ВІДПОВІДЕЙ --}}
    <div class="mt-5 space-y-2.5">
        @foreach ($comment->getAllReplies() as $reply)
            @php
                $isAdmin = $reply->user?->hasRole('admin');
            @endphp

            <div wire:key="reply-{{ $reply->id }}" @class([
                'ml-6 px-4 py-3 border-s-2 text-sm relative transition-all group mb-2',
                // Колір фону та рамки для Адміна
                'bg-linear-to-r from-orange-50/80 to-transparent border-orange-500' => $isAdmin,
                // Колір фону та рамки для Гостя
                'bg-linear-to-r from-zinc-50 to-transparent border-zinc-300' => !$isAdmin,
            ]) x-cloak>

                {{-- Контекст: кому саме відповідають --}}
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
                        {{ $reply->parent?->user?->name ?? ($reply->parent?->author_name ?: 'Гість') }}
                    </div>
                    <div class="ms-auto text-[10px] opacity-60 font-medium whitespace-nowrap">
                        {{ $reply->created_at->diffForHumans() }}
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

                        <span>{{ $reply->user?->name ?? ($reply->author_name ?: 'Гість') }}</span>

                        @if ($isAdmin)
                            <span
                                class="ms-1 px-2 py-0.5 text-[9px] uppercase tracking-widest bg-orange-600 text-white rounded-sm font-black">
                                Майстер
                            </span>
                        @endif
                    </div>
                </div>

                <div @class([
                    'mt-2 leading-relaxed text-[13px]',
                    'text-orange-900/80' => $isAdmin,
                    'text-zinc-600' => !$isAdmin,
                ])>
                    {{ $reply->body }}
                </div>

                {{-- Кнопка відповіді --}}
                <button wire:click="setReply({{ $reply->id }})" @class([
                    'text-[10px] font-bold mt-3 transition-colors cursor-pointer flex items-center gap-1.5 uppercase tracking-wider',
                    'text-orange-500 hover:text-orange-800' => $isAdmin,
                    'text-zinc-400 hover:text-zinc-800' => !$isAdmin,
                ])>
                    <x-lucide-reply class="size-3" />
                    Відповісти
                </button>
            </div>
        @endforeach
    </div>
</div>
