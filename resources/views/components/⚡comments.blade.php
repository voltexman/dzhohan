<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use App\Models\Comment;

new class extends Component {
    use WithPagination;

    public Model $model; // Product | Article | etc

    #[
        Validate(
            'min:2|max:50',
            message: [
                'min' => 'Занадто мало символів',
                'max' => 'Занадто багато символів',
            ],
        ),
    ]
    public $author_name = '';

    #[
        Validate(
            'required|min:3|max:2000',
            message: [
                'required' => 'Напишіть коментар',
                'min' => 'Занадто короткий коментар',
                'max' => 'Занадто довгий коментар',
            ],
        ),
    ]
    public $body = '';

    public $replyTo = null;

    protected $paginationTheme = 'tailwind';

    public function mount(Model $model)
    {
        $this->model = $model;
    }

    public function send()
    {
        $this->validate();

        $this->model->comments()->create([
            'author_name' => $this->author_name,
            'parent_id' => $this->replyTo,
            'body' => $this->body,
            'ip_address' => request()->ip(),
        ]);

        $this->reset(['author_name', 'replyTo', 'body']);
        $this->gotoPage(1, 'commentsPage');
    }

    public function setReply($id)
    {
        $this->replyTo = $this->replyTo === $id ? null : $id;
    }

    #[Computed]
    public function comments()
    {
        return $this->model
            ->comments()
            ->whereNull('parent_id')
            ->with(['replies.user', 'replies.parent.user', 'likes', 'replies.likes'])
            ->withCount('likes')
            ->latest()
            ->paginate(10, ['*'], 'commentsPage');
    }
};
?>

<div class="space-y-5 pb-10">
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

        <x-button type="submit" size="md">
            <span wire:loading.remove wire:target="send">Надіслати</span>
            <span wire:loading wire:target="send">Відправка</span>
            <x-lucide-send wire:loading.remove wire:target="send" class="size-4 ms-1.5" />
            <x-lucide-loader-circle wire:loading wire:target="send" class="size-4 ms-1.5 animate-spin" />
        </x-button>
    </form>

    @island('comment-list', lazy: true, always: true)
        @placeholder
            @include('partials.placeholders.comments')
        @endplaceholder

        <div class="space-y-5" wire:poll.15s.visible>
            @forelse ($this->comments as $comment)
                <livewire:comment :$comment wire:key="comment-item-{{ $comment->id }}" />
            @empty
                <div class="text-center py-10 text-zinc-500">
                    Немає коментарів
                </div>
            @endforelse
        </div>

        @if ($this->comments->hasPages())
            <nav class="flex justify-center items-center gap-2.5">
                {{-- Кнопка Назад --}}
                <button wire:click="previousPage('commentsPage')" wire:loading.attr="disabled" {{-- Використовуємо звичайний Blade для disabled --}}
                    @if ($this->comments->onFirstPage()) disabled @endif @class([
                        'bg-zinc-200/60 hover:bg-zinc-200/80 size-9 flex items-center justify-center rounded-full text-xs font-bold transition-all cursor-pointer',
                        'opacity-50 pointer-events-none' => $this->comments->onFirstPage(),
                    ])>
                    <x-lucide-chevron-left class="size-5 stroke-zinc-600" />
                </button>

                <div class="flex items-center gap-1.5">
                    @foreach ($this->comments->getUrlRange(1, $this->comments->lastPage()) as $page => $url)
                        {{-- Ваш код циклу сторінок залишається без змін --}}
                        @if ($page == 1 || $page == $this->comments->lastPage() || abs($page - $this->comments->currentPage()) <= 1)
                            <button wire:click="gotoPage({{ $page }}, 'commentsPage')" wire:loading.attr="disabled"
                                @class([
                                    'size-9 flex items-center justify-center rounded-full text-xs font-bold transition-all cursor-pointer',
                                    'bg-zinc-900 text-white' => $page == $this->comments->currentPage(),
                                    'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900' =>
                                        $page != $this->comments->currentPage(),
                                ])>
                                {{ $page }}
                            </button>
                        @elseif ($page == 2 || $page == $this->comments->lastPage() - 1)
                            <span class="px-1 text-zinc-300 text-xs">...</span>
                        @endif
                    @endforeach
                </div>

                {{-- Кнопка Вперед --}}
                <button wire:click="nextPage('commentsPage')" wire:loading.attr="disabled" {{-- Використовуємо звичайний Blade для disabled --}}
                    @if (!$this->comments->hasMorePages()) disabled @endif @class([
                        'bg-zinc-200/60 hover:bg-zinc-200/80 size-9 flex items-center justify-center rounded-full text-xs font-bold transition-all cursor-pointer',
                        'opacity-50 pointer-events-none' => !$this->comments->hasMorePages(),
                    ])>
                    <x-lucide-chevron-right class="size-5 stroke-zinc-600" />
                </button>
            </nav>
        @endif
    @endisland
</div>
