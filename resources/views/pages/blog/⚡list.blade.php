<?php

use App\Enums\PostType;
use Livewire\Attributes\Session;
use Livewire\Component;
use App\Models\Post;
use App\Models\Tag;

new class extends Component {
    #[Session]
    public string $search = '';

    #[Session]
    public array $tags = [];

    public function with()
    {
        return [
            'posts' => Post::query()
                ->whereIn('type', [PostType::ARTICLE, PostType::REVIEW])
                ->withCount(['likes', 'comments'])
                ->with('tags')
                ->latest()
                ->get(),

            'tags' => Tag::whereHas('posts')->get(),
        ];
    }
};
?>

@section('header')
    <x-header :image="Vite::asset('resources/images/blog-header-bg.png')">
        <x-slot:title>Мій блог</x-slot:title>
        <x-slot:description>
            Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quas, tenetur animi voluptas
            veniam repellat eius.
        </x-slot:description>
    </x-header>
@endsection

<x-section sidebar-position="right">
    <x-slot:sidebar>
        <div class="sticky top-16 h-[calc(100vh-4rem)] flex flex-col pt-10">
            <x-form.input wire:model="search" icon="search" />

            <div class="flex flex-wrap gap-1.5 my-5">
                @foreach ($tags as $tag)
                    <span
                        class="text-xs px-1.5 py-0.5 bg-neutral-200 rounded-md font-medium border border-neutral-100 text-neutral-600">
                        <x-lucide-tag class="size-3 inline-flex" />
                        {{ $tag->name }}
                    </span>
                @endforeach
            </div>

            <input type="text" id="input">

        </div>
    </x-slot:sidebar>

    <div class="lg:grid lg:grid-cols-2 lg:gap-10 mb-10">
        <div class="font-[Oswald] text-5xl font-black text-gray-800 uppercase text-nowrap">
            <span class="text-orange-500">Огляди</span> та <span class="text-orange-500">статті</span> <br>
            про <span class="text-orange-500">ножі</span> та інше
        </div>
        <div class="text-sm text-gray-600">
            Майстерність виготовлення ножів, корисні поради для колекціонерів і
            ентузіастів. Про все, що зазвичай приховане від очей покупців.
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

        @forelse ($posts as $post)
            <article @class([
                'group relative overflow-hidden border border-zinc-200 bg-zinc-100',
                'aspect-[4/3]',
                'first:col-span-full first:aspect-[21/12]',
            ])>

                {{-- Тип матеріалу (зверху зліва) --}}
                <div
                    class="absolute z-20 top-3 left-3 px-2 py-1.5 rounded-sm bg-black/50 backdrop-blur flex items-center gap-1.5">
                    @if ($post->type === PostType::ARTICLE)
                        <x-lucide-file-text class="size-6 text-white" stroke-width="1.5" />
                    @elseif($post->type === PostType::REVIEW)
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

                    <h3 class="text-lg font-bold tracking-tight group-first:text-2xl">
                        {{ $post->name }}
                    </h3>

                    @isset($post->excerpt)
                        <p class="mt-1 text-sm text-white/80 line-clamp-2 group-first:text-base">
                            {{ $post->excerpt }}
                        </p>
                    @endisset

                    <div
                        class="mt-2.5 text-xs uppercase tracking-wider font-semibold text-orange-500 group-hover:text-white transition">
                        Детальніше →
                    </div>

                </div>
            </article>

        @empty
            <div class="col-span-full text-center py-16 text-zinc-400">
                Немає записів
            </div>
        @endforelse
    </div>
</x-section>

<script>
    document.addEventListener('livewire:load', () => {

    })
</script>
