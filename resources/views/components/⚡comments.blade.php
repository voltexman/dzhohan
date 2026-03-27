<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Livewire\Forms\CommentForm;
use App\Models\Comment;

new class extends Component {
    use WithPagination;

    public Model $model;

    public CommentForm $form;

    public function mount(Model $model)
    {
        $this->model = $model;
    }

    public function send()
    {
        $validated = $this->form->validate();

        $this->model->comments()->create($validated);

        $this->form->reset(['body']);

        unset($this->comments);

        $this->gotoPage(1, 'commentsPage');

        $this->dispatch('comment-added');
    }

    #[Computed]
    public function comments()
    {
        return $this->model
            ->comments()
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->withCount(['likes', 'descendants as descendants_count'])
            ->with([
                'likes',
                'user',
                'descendants' => function ($q) {
                    $q->where('is_active', true)->with(['likes', 'user']);
                },
            ])
            ->orderByDesc('descendants_count')
            ->orderByDesc('likes_count')
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'commentsPage');
    }
};
?>

<div class="space-y-10 pb-10">
    <form class="space-y-5" wire:submit="send">
        <div class="flex justify-between">
            <h3 class="text-lg font-semibold font-[SN_Pro]">
                Залишити коментар
                @if ($this->comments->count() > 0)
                    <div
                        class="size-8 inline-flex justify-center items-center rounded-full bg-orange-50 border border-orange-100 text-orange-500 text-xs">
                        {{ $this->comments->count() }}
                    </div>
                @endif
            </h3>

            <x-button variant="ghost" color="light" size="sm" wire:click="$refresh" wire:island="comment-list"
                wire:loading.attr="disabled" wire:target="$refresh" class="cursor-pointer" icon>
                <x-lucide-refresh-cw wire:loading.class="animate-spin" wire:target="$refresh"
                    class="size-5 stroke-gray-800" />
            </x-button>
        </div>

        @guest
            <x-form.input color="soft" type="text" wire:model.trim="form.author_name" placeholder="Ім’я"
                maxlength="80" />
        @endguest

        <div>
            <x-form.textarea id="comment-body" wire:model.trim="form.body" rows="5" placeholder="Ваш відгук..."
                maxlength="500" required icons />
            @error('form.body')
                <x-form.error>{{ $message }}</x-form.error>
            @enderror
        </div>

        <x-button type="submit" size="md">
            <span wire:loading.remove wire:target="send">Надіслати</span>
            <span wire:loading wire:target="send">Відправка</span>
            <x-lucide-send wire:loading.remove wire:target="send" class="size-4 ms-1.5" />
            <x-lucide-loader-circle wire:loading wire:target="send" class="size-4 ms-1.5 animate-spin" />
        </x-button>
    </form>

    @island(name: 'comment-list', lazy: true, always: true)
        @placeholder
            @include('partials.placeholders.comments')
        @endplaceholder

        <div class="space-y-5" wire:poll.30s.visible wire:on.comment-added="$refresh">
            @forelse ($this->comments as $comment)
                <livewire:comment :comment="$comment" wire:key="comment-{{ $comment->id }}" />
            @empty
                <div class="flex flex-col items-center justify-center py-10 px-5">
                    <div class="relative mb-5">
                        <div class="absolute inset-0 scale-150 bg-orange-100/50 rounded-full blur-2xl"></div>
                        <div
                            class="relative size-15 flex items-center justify-center rounded-full bg-white border border-zinc-100 text-zinc-400">
                            <x-lucide-messages-square class="size-7 stroke-[1.5px]" />
                        </div>
                    </div>
                    <div class="text-center space-y-1.5">
                        <h4 class="font-[Oswald] text-sm font-bold text-zinc-900 uppercase tracking-tight">
                            Тут поки що тихо
                        </h4>
                        <p class="text-xs text-zinc-500 leading-relaxed mx-auto">
                            Будьте першим, хто<br>поділиться своєю думкою!
                        </p>
                    </div>

                    <button
                        @click="const el = document.getElementById('comment-body'); el.scrollIntoView({ behavior: 'smooth', block: 'center' }); el.focus();"
                        class="mt-5 text-sm font-semibold tracking-wide text-orange-600 hover:text-orange-700 transition-colors cursor-pointer">
                        Написати коментар
                    </button>
                </div>
            @endforelse
        </div>

        @if ($this->comments->hasPages())
            <nav class="flex justify-center items-center gap-2.5">
                {{-- Кнопка Назад --}}
                <button wire:click="previousPage('commentsPage')" wire:loading.attr="disabled"
                    @if ($this->comments->onFirstPage()) disabled @endif @class([
                        'bg-zinc-200/60 hover:bg-zinc-200/80 size-9 flex items-center justify-center rounded-full text-xs font-bold transition-all cursor-pointer',
                        'opacity-50 pointer-events-none' => $this->comments->onFirstPage(),
                    ])>
                    <x-lucide-chevron-left class="size-5 stroke-zinc-600" />
                </button>

                <div class="flex items-center gap-1.5">
                    @foreach ($this->comments->getUrlRange(1, $this->comments->lastPage()) as $page => $url)
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
                <button wire:click="nextPage('commentsPage')" wire:loading.attr="disabled"
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
