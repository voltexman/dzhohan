<?php

use App\Models\Post;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Support\Str;

new class extends Component {
    public Post $post;

    public function mount($post)
    {
        $this->post = $post;
    }

    #[Computed]
    public function readingTime()
    {
        $words = Str::wordCount(strip_tags($this->post->text));
        $minutes = ceil($words / 200);
        return $minutes;
    }

    #[Computed]
    public function relatedPosts()
    {
        return Post::where('id', '!=', $this->post->id)
            ->where(function ($q) {
                $q->where('type', $this->post->type)->orWhereHas('tags', fn($tag) => $tag->whereIn('tags.id', $this->post->tags->pluck('id')));
            })
            ->withCount(['likes', 'comments'])
            ->latest()
            ->take(12)
            ->get();
    }

    public function toggleLike()
    {
        if ($this->post->isLiked()) {
            $this->post->unlike();
        } else {
            $this->post->like();
        }

        $this->post->loadCount('likes');
    }
};
?>

<x-slot name="title">
    {{ $post->name }} — Блог Dzhohan
</x-slot>
<x-slot name="description">
    {{ Str::limit(strip_tags($post->excerpt ?? $post->text), 160) }}
</x-slot>

@section('header')
    <x-header class="h-[65vh]!" :image="Vite::asset('resources/images/header.png')">
        <x-slot:title class="text-2xl! md:text-4xl! lg:text-5xl! max-w-4xl mx-auto leading-tight">
            {{ $post->name }}
        </x-slot:title>

        <x-slot:description>
            <div class="flex flex-wrap items-center justify-center gap-4 md:gap-8 mt-6 text-sm font-medium text-white/80">
                <div class="flex items-center gap-2">
                    <x-lucide-calendar class="size-4" />
                    {{ $post->created_at->format('d.m.Y') }}
                </div>
                <div class="flex items-center gap-2">
                    <x-lucide-clock class="size-4" />
                    {{ $this->readingTime }} хв читання
                </div>
                <div class="flex items-center gap-2">
                    <x-lucide-layers class="size-4" />
                    {{ $post->type->label() }}
                </div>
            </div>
        </x-slot:description>
    </x-header>
@endsection

<section class="relative pb-20">
    <div class="max-w-5xl xl:max-w-6xl mx-auto px-5">
        {{-- Головне зображення --}}
        <div class="relative -mt-32 md:-mt-48 z-10 rounded-md overflow-hidden shadow-2xl border-2 border-white">
            <img src="{{ Vite::asset('resources/images/header.png') }}" class="w-full h-auto object-cover aspect-video"
                alt="{{ $post->name }}" />
        </div>

        <div class="flex flex-col lg:flex-row gap-12 mt-12">
            {{-- Контент --}}
            <div class="flex-1 min-w-0">
                <div
                    class="prose prose-zinc prose-lg max-w-none 
                    prose-headings:font-bold prose-headings:tracking-tight 
                    prose-a:text-orange-600 prose-a:no-underline hover:prose-a:underline
                    prose-img:rounded-xl prose-img:shadow-lg">
                    {!! $post->text !!}
                </div>

                {{-- Теги --}}
                @if ($post->tags->isNotEmpty())
                    <div class="flex flex-wrap gap-2 mt-12 pt-8 border-t border-zinc-100">
                        @foreach ($post->tags as $tag)
                            <x-tag>{{ $tag->name }}</x-tag>
                        @endforeach
                    </div>
                @endif

                {{-- Взаємодія --}}
                <div class="flex items-center justify-between mt-10">
                    <div class="flex items-center gap-5">
                        <button wire:click="toggleLike" @class([
                            'flex items-center gap-1.5 group',
                            'text-orange-600' => $post->isLiked(),
                            'text-zinc-400 hover:text-zinc-600' => !$post->isLiked(),
                        ])>
                            <div @class([
                                'flex items-center justify-center',
                                'group-hover:bg-zinc-100' => !$post->isLiked(),
                            ])>
                                <x-lucide-heart @class(['size-5', 'fill-orange-600' => $post->isLiked()]) />
                            </div>
                            <span class="font-bold text-sm">{{ $post->likes_count ?: 0 }} лайків</span>
                        </button>

                        <div class="flex items-center gap-2.5 text-zinc-400">
                            <div class="flex items-center justify-center">
                                <x-lucide-message-circle class="size-5" />
                            </div>
                            <span class="font-bold text-sm">{{ $post->comments_count ?: 0 }} коментарів</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Права колонка --}}
            <aside class="w-full lg:w-80 space-y-10">
                <div class="sticky top-24 space-y-5">
                    <div class="p-5 bg-zinc-900 rounded-md text-white">
                        <h4 class="font-[Oswald] text-lg font-bold mb-2.5">Підпишіться</h4>
                        <p class="text-zinc-400 text-sm mb-5">Отримуйте сповіщення про нові статті та вироби майстерні.
                        </p>
                        <livewire:subscriber />
                    </div>

                    <a href="{{ route('blog') }}" wire:navigate
                        class="flex items-center justify-center gap-2 bg-zinc-900 w-full py-4 rounded-md text-sm font-bold text-zinc-100 hover:bg-zinc-800 transition-all duration-300">
                        <x-lucide-arrow-left class="size-4" />
                        До всіх статей
                    </a>
                </div>
            </aside>
        </div>
    </div>

    {{-- Читайте також (Повна ширина) --}}
    @if ($this->relatedPosts->isNotEmpty())
        <div class="bg-zinc-100 border-y border-zinc-200 py-10 mt-10 overflow-hidden">
            <div class="max-w-5xl xl:max-w-6xl mx-auto px-5">
                <x-block.title icon="sparkles" tag="h4" class="mb-5">Читайте також</x-block.title>
            </div>

            <div class="embla-related overflow-hidden cursor-grab active:cursor-grabbing">
                <div
                    class="embla__container flex gap-6 px-5 lg:px-[calc((100vw-1152px)/2+1.25rem)] xl:px-[calc((100vw-1152px)/2+1.25rem)]">
                    @foreach ($this->relatedPosts as $related)
                        <div class="embla__slide flex-[0_0_280px] sm:flex-[0_0_320px] min-w-0">
                            <a href="{{ route('blog.show', $related) }}" wire:navigate class="group block space-y-4">
                                <div class="aspect-video rounded-2xl overflow-hidden shadow-sm border border-zinc-200">
                                    <img src="{{ Vite::asset('resources/images/header.png') }}"
                                        alt="{{ $related->name }}"
                                        class="size-full object-cover transition duration-500 group-hover:scale-110">
                                </div>
                                <div class="space-y-2">
                                    <span class="text-[11px] font-bold text-zinc-400 uppercase tracking-widest block">
                                        {{ $related->created_at->format('d M, Y') }}
                                    </span>
                                    <h5
                                        class="font-bold text-lg text-zinc-900 group-hover:text-orange-600 transition-colors line-clamp-2 leading-snug">
                                        {{ $related->name }}
                                    </h5>
                                    <p class="text-sm text-zinc-500 line-clamp-2">
                                        {{ Str::limit(strip_tags($related->excerpt ?? $related->text), 100) }}
                                    </p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- Коментарі --}}
    <div class="max-w-5xl xl:max-w-6xl mx-auto px-5 mt-10">
        <div class="max-w-2xl w-full">
            <x-block.title icon="message-circle" class="mb-5">
                Обговорення
                <x-slot:badge>{{ $post->comments_count ?: 0 }}</x-slot:badge>
            </x-block.title>
            <livewire:comments :model="$post" />
        </div>
    </div>
</section>

@assets
    @vite('resources/js/pages/blog-show.js')
@endassets
