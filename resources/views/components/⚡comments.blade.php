<?php

use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\WithPagination;
use App\Models\Comment;

new class extends Component {
    use WithPagination;

    public $model; // Product | Article | etc

    #[Validate('min:2', message: 'Занадто мало символів')]
    #[Validate('max:50', message: 'Занадто багато символів')]
    public $author_name = '';

    #[Validate('required', message: 'Напишіть коментар')]
    #[Validate('min:3', message: 'Занадто короткий коментар')]
    #[Validate('max:2000', message: 'Занадто довгий коментар')]
    public $body = '';

    public $replyTo = null;

    public function mount($model)
    {
        $this->model = $model;
    }

    public function send()
    {
        $this->validate();

        $this->model->comments()->create([
            'author_name' => $this->author_name,
            'body' => $this->body,
            'parent_id' => $this->replyTo,
        ]);

        $this->reset(['author_name', 'body', 'replyTo']);
    }

    public function setReply($id)
    {
        $this->replyTo = $id;
    }

    public function like($commentId)
    {
        $comment = $this->model->comments()->find($commentId);

        if ($comment) {
            $comment->isLiked() ? $comment->unlike() : $comment->like();
        }
    }

    public function getCommentsProperty()
    {
        return $this->model
            ->comments()
            ->with('replies')
            ->withCount('likes')
            ->latest()
            ->paginate(10, ['*'], 'commentsPage');
    }
};
?>

<div class="space-y-10">
    {{-- Форма --}}
    <form class="space-y-5" wire:submit="send">

        <div class="flex justify-between">
            <h3 class="text-lg font-semibold font-[SN_Pro]">
                Залишити коментар
                @if ($this->comments->count() > 0)
                    <span class="text-gray-500 text-sm">({{ $this->comments->count() }})</span>
                @endif
            </h3>

            <button type="button" x-on:click="$wire.$island('comment-list').$refresh()">
                <x-lucide-refresh-cw wire:loading.class="animate-spin" wire:target="$refresh"
                    class="size-5 stroke-gray-800" />
            </button>
        </div>

        <div>
            <x-form.input color="soft" type="text" wire:model.live.blur="author_name" placeholder="Ім’я" />
            @error('author_name')
                <x-form.error>{{ $message }}</x-form.error>
            @enderror
        </div>

        <div>
            <x-form.textarea wire:model.live.blur="body" rows="4" placeholder="Ваш відгук..." />
            @error('body')
                <x-form.error>{{ $message }}</x-form.error>
            @enderror
        </div>

        @if ($replyTo)
            <div class="text-xs text-zinc-500">
                Відповідь на коментар #{{ $replyTo }}
            </div>
        @endif

        <x-button type="submit">
            <span wire:loading.remove wire:target="send">Надіслати</span>
            <span wire:loading wire:target="send">Відправка</span>
            <x-lucide-send wire:loading.remove wire:target="send" class="size-4 ms-1.5" />
            <x-lucide-loader-circle wire:loading wire:target="send" class="size-4 ms-1.5 animate-spin" />
        </x-button>
    </form>

    @island('comment-list', lazy: true, always: true)
        @placeholder
            <div class="space-y-6 w-full">
                @foreach (range(1, 3) as $i)
                    <div class="border-b border-zinc-200/60 pb-5 animate-pulse">
                        <div class="flex justify-between">
                            <div class="h-4 bg-zinc-200 rounded w-1/4"></div>
                            <div class="h-3 bg-zinc-100 rounded w-1/6"></div>
                        </div>
                        <div class="mt-4 space-y-2">
                            <div class="h-3 bg-zinc-100 rounded w-full"></div>
                            <div class="h-3 bg-zinc-100 rounded w-5/6"></div>
                        </div>
                        <div class="mt-4 flex gap-4">
                            <div class="h-3 bg-zinc-100 rounded w-16"></div>
                            <div class="h-3 bg-zinc-100 rounded w-16"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endplaceholder

        <div class="space-y-5" wire:poll.15s.visible>
            @forelse ($this->comments as $comment)
                <div class="border-b border-zinc-200/60 pb-5 last:border-b-0" wire:key="comment-{{ $comment->id }}">

                    <div class="flex items-start justify-between gap-4">
                        {{-- Ім'я автора --}}
                        <div class="flex items-center gap-1.5 font-semibold text-sm text-zinc-700 min-w-0">
                            <x-lucide-user-round class="size-4 shrink-0 text-zinc-500" />
                            <span class="truncate">{{ $comment->author_name }}</span>
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
                                @if ($comment->created_at->gt(now()->subDays(3)))
                                    {{-- Свіжий коментар: іконка годинника --}}
                                    <x-lucide-clock class="size-3.5 shrink-0 mb-0.5 fill-zinc-100 stroke-zinc-500" />
                                    <span>{{ $comment->created_at->diffForHumans() }}</span>
                                @else
                                    {{-- Старий коментар: іконка календаря --}}
                                    <x-lucide-calendar-days
                                        class="size-3.5 shrink-0 mb-0.5 fill-zinc-100 stroke-zinc-500" />
                                    <span>{{ $comment->created_at->format('d.m.Y') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-2.5 text-gray-800">{{ $comment->body }}</div>

                    <button wire:click="setReply({{ $comment->id }})" class="text-xs font-medium text-zinc-500 mt-2.5">
                        <x-lucide-reply class="size-4 inline-flex mb-1" />
                        Відповісти
                    </button>

                    <button wire:click="like({{ $comment->id }})" wire:loading.class="animate-pulse pointer-events-none"
                        wire:target="like({{ $comment->id }})" wire:key="like-button-{{ $comment->id }}"
                        class="text-xs font-medium mt-2.5 ms-2.5 transition-colors duration-250 cursor-pointer 
           {{ $comment->isLiked() ? 'text-red-600 hover:text-red-800' : 'text-zinc-500 hover:text-zinc-800' }}">
                        <x-lucide-loader-circle class="size-4 inline-flex mb-1 animate-spin" wire:loading
                            wire:target="like({{ $comment->id }})" />
                        <x-lucide-heart
                            class="size-4 inline-flex mb-1 {{ $comment->isLiked() ? 'fill-red-600 stroke-red-600' : 'stroke-zinc-500' }}"
                            wire:loading.remove wire:target="like({{ $comment->id }})" />
                        Подобається
                    </button>

                    @foreach ($comment->replies as $reply)
                        <div class="ml-5 mt-5 px-4 py-2 border-s-2 border-gray-200 text-sm"
                            wire:key="reply-{{ $reply->id }}">
                            <div class="font-medium text-gray-800">
                                <x-lucide-user-round class="size-4 inline-flex stroke-gray-700 mb-0.5" />
                                {{ $reply->author_name }}
                            </div>
                            <div class="mt-2.5 text-gray-700">{{ $reply->body }}</div>
                        </div>
                    @endforeach
                </div>

            @empty
                <div class="text-center py-10 text-zinc-500">
                    Немає коментарів
                </div>
            @endforelse
        </div>

        {{-- 3. Додаємо пагінацію --}}
        <div class="mt-5 mx-auto w-fit">
            @if ($this->comments->hasPages())
                <nav class="flex items-center gap-2 mt-8">
                    {{-- Кнопка Назад --}}
                    @if ($this->comments->onFirstPage())
                        <x-button variant="circle" color="light" size="md">
                            <x-lucide-chevron-left class="size-4 stroke-gray-800" />
                        </x-button>
                    @else
                        <x-button variant="circle" color="light" size="md" wire:click="previousPage('commentsPage')">
                            <x-lucide-chevron-left class="size-4 stroke-gray-800" />
                        </x-button>
                    @endif

                    {{-- Цифри сторінок --}}
                    <div class="flex items-center gap-1">
                        {{-- Використовуємо метод getUrlRange для отримання цифр --}}
                        @foreach ($this->comments->onEachSide(1)->getUrlRange(1, $this->comments->lastPage()) as $page => $url)
                            {{-- Логіка для відображення тільки першої, останньої та сусідніх сторінок (спрощено) --}}
                            @if ($page == 1 || $page == $this->comments->lastPage() || abs($page - $this->comments->currentPage()) <= 1)
                                <x-button variant="circle" color="light" size="md"
                                    wire:click="gotoPage({{ $page }}, 'commentsPage')"
                                    class="flex items-center justify-center transition
                        {{ $page == $this->comments->currentPage() ? 'bg-zinc-900 text-white' : 'text-zinc-600 hover:bg-zinc-50' }}">
                                    {{ $page }}
                                </x-button>
                            @elseif ($page == 2 || $page == $this->comments->lastPage() - 1)
                                <span class="px-1 text-zinc-400">...</span>
                            @endif
                        @endforeach
                    </div>

                    {{-- Кнопка Вперед --}}
                    @if ($this->comments->hasMorePages())
                        <x-button variant="circle" color="light" size="md" wire:click="nextPage('commentsPage')">
                            <x-lucide-chevron-right class="size-4 stroke-white" />
                        </x-button>
                    @else
                        <x-button variant="circle" color="light" size="md" class="cursor-not-allowed">
                            <x-lucide-chevron-right class="size-4 stroke-white" />
                        </x-button>
                    @endif
                </nav>
            @endif
        </div>
    @endisland
</div>
