<?php

use App\Enums\PostType;
use Livewire\Attributes\Session;
use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Models\Post;
use App\Models\Tag;

new class extends Component {
    #[Session]
    public string $search = '';

    // #[Session]
    // public array $tags = [];

    #[Computed]
    public function posts()
    {
        return Post::query()
            ->withCount(['likes', 'comments'])
            ->with('tags')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('excerpt', 'like', "%{$this->search}%")
                        ->orWhereHas('tags', fn($tag) => $tag->where('name', 'like', "%{$this->search}%"));
                });
            })
            ->latest()
            ->get();
    }
};
?>

<x-slot name="title">
    Блог про ножі — поради, огляди, вибір ножів
</x-slot>

<x-slot name="description">
    Читайте блог про ножі: як обрати ніж, догляд за лезом, огляди матеріалів та поради від майстра. Корисна
    інформація для новачків і професіоналів.
</x-slot>

@section('header')
    <x-header class="h-[50vh]!" :image="Vite::asset('resources/images/blog-header-bg.png')">
        <x-slot:title>Мій блог</x-slot:title>

        @if ($this->posts->isNotEmpty())
            <x-slot:description>
                Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quas, tenetur animi voluptas
                veniam repellat eius.
            </x-slot:description>
        @endif
    </x-header>
@endsection

<x-section sidebar-position="right">
    <x-slot:sidebar class="h-screen">
        <x-form.input wire:model.trim.live="search" icon="search" />

        {{-- <div class="flex flex-wrap gap-1.5 my-5">
            @foreach ($this->posts->tags as $tag)
                <span
                    class="text-xs px-1.5 py-0.5 bg-neutral-200 rounded-md font-medium border border-neutral-100 text-neutral-600">
                    <x-lucide-tag class="size-3 inline-flex" />
                    {{ $tag->name }}
                </span>
            @endforeach
        </div> --}}

        <input type="text" id="input">
    </x-slot:sidebar>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        @forelse ($this->posts as $post)
            <article @class([
                'group relative rounded-md overflow-hidden border border-zinc-200 bg-zinc-100',
                'aspect-[4/3]',
                'first:col-span-full first:aspect-[21/12] last:col-span-full last:aspect-[21/12]',
            ])>

                {{-- Тип матеріалу (зверху зліва) --}}
                <div
                    class="absolute z-20 top-3 left-3 px-2 py-1.5 rounded-sm bg-black/50 backdrop-blur flex items-center gap-1.5">
                    @if ($post->type === PostType::ARTICLE)
                        <x-lucide-file-text class="size-6 text-white" stroke-width="1.5" />
                    @elseif($post->type === PostType::NEWS)
                        <x-lucide-circle-play class="size-6 stroke-white" stroke-width="1.5" />
                    @endif
                </div>

                {{-- Фонове зображення --}}
                <img src="{{ Vite::asset('resources/images/header.png') }}" alt="{{ $post->name }}"
                    class="absolute inset-0 size-full object-cover transition duration-500 group-hover:scale-105">

                {{-- затемнення / градієнт --}}
                <div class="absolute inset-0 bg-linear-to-t from-black/80 via-black/40 to-transparent"></div>

                {{-- meta (лайки / коментарі) --}}
                <div class="absolute top-3 right-3 flex gap-2 text-white text-xs font-medium">

                    @if ($post->likes_count)
                        <div class="flex items-center gap-1 px-2 py-1 rounded-full bg-black/50 backdrop-blur">
                            <x-lucide-heart class="size-3.5" />
                            {{ $post->likes_count }}
                        </div>
                    @endif

                    @if ($post->comments_count)
                        <div class="flex items-center gap-1 px-2 py-1 rounded-full bg-black/50 backdrop-blur">
                            <x-lucide-message-circle class="size-3.5" />
                            {{ $post->comments_count }}
                        </div>
                    @endif

                </div>

                {{-- контент внизу --}}
                <div class="absolute bottom-0 p-5 text-white">

                    <h3 class="font-semibold tracking-tight group-first:text-2xl">
                        {{ $post->name }}
                    </h3>

                    @isset($post->excerpt)
                        <p class="mt-1 text-sm text-white/80 line-clamp-2 group-first:text-base">
                            {{ $post->excerpt }}
                        </p>
                    @endisset

                    <a href="{{ route('blog.show', $post) }}" wire:navigate
                        class="flex items-center gap-1 mt-2.5 text-xs uppercase tracking-wider font-semibold text-orange-500 group-hover:text-white transition">
                        Детальніше
                        <x-lucide-move-right class="size-3.5 inline-flex" />
                    </a>

                </div>
            </article>

        @empty
            <div class="col-span-full text-center py-16 text-zinc-400">
                Немає записів
            </div>
        @endforelse
    </div>
</x-section>
