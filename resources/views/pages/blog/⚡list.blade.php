<?php

use App\Enums\PostType;
use Illuminate\Support\Facades\Cookie;
use Livewire\Attributes\Session;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Post;
use App\Models\Tag;

new class extends Component {
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $type = 'all';

    #[Url(as: 'tag')]
    public array $selectedTags = [];

    #[Session]
    public string $view = 'grid';

    public int $perPage = 12;

    public function mount()
    {
        $this->view = Cookie::get('blog_view', 'grid');
    }

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
            ->when($this->type !== 'all', fn($q) => $q->where('type', $this->type))
            ->when(!empty($this->selectedTags), function ($query) {
                $query->whereHas('tags', fn($q) => $q->whereIn('slug', $this->selectedTags));
            })
            ->latest()
            ->paginate($this->perPage);
    }

    #[Computed]
    public function tags()
    {
        return Tag::whereHas('posts')->get();
    }

    #[Computed]
    public function activeFilters(): array
    {
        $active = [];

        if ($this->search) {
            $active[] = [
                'type' => 'search',
                'label' => 'Пошук: ' . $this->search,
            ];
        }

        if ($this->type !== 'all') {
            $active[] = [
                'type' => 'type',
                'label' => PostType::tryFrom($this->type)?->label() ?? $this->type,
            ];
        }

        if (!empty($this->selectedTags)) {
            $tagModels = Tag::whereIn('slug', $this->selectedTags)->get();
            foreach ($tagModels as $tag) {
                $active[] = [
                    'type' => 'tag',
                    'label' => '#' . $tag->name,
                    'slug' => $tag->slug,
                ];
            }
        }

        return $active;
    }

    public function removeFilter(string $type, ?string $slug = null)
    {
        match ($type) {
            'search' => ($this->search = ''),
            'type' => ($this->type = 'all'),
            'tag' => ($this->selectedTags = array_values(array_diff($this->selectedTags, [$slug]))),
            default => null,
        };
    }

    public function resetFilters()
    {
        $this->reset(['search', 'type', 'selectedTags']);
    }

    public function loadMore()
    {
        $this->perPage += 4;
    }
};
?>

<x-slot name="title">
    Блог майстерні Dzhohan — все про ножі ручної роботи
</x-slot>

<x-slot name="description">
    Корисні статті про вибір ножів, типи сталі, матеріали руків'я та догляд за інструментами. Новини майстерні та огляди
    нових робіт.
</x-slot>

@section('header')
    <x-header class="h-[45vh]!" :image="Vite::asset('resources/images/blog-header-bg.png')">
        <x-slot:title>Світ ножів {{ env('APP_NAME') }}</x-slot:title>

        <x-slot:description>
            Читайте про тонкощі ножової справи, наші новини та корисні поради для поціновувачів якісного інструменту.
        </x-slot:description>
    </x-header>
@endsection

<section class="lg:min-h-screen bg-white pb-32">
    <div x-data="{ mobileFiltersOpen: false }"
        class="max-w-5xl lg:max-w-6xl xl:max-w-7xl px-5 mx-auto lg:grid lg:grid-cols-3 lg:gap-10">
        <aside
            class="hidden lg:block shrink-0 sticky top-16 lg:top-14 z-40 lg:h-screen w-full border-b lg:border-b-0 lg:border-r border-zinc-200 bg-linear-to-b lg:bg-linear-to-r from-zinc-50 lg:from-transparent to-zinc-100">
            @include('partials.blog.filters')
        </aside>

        <main class="flex-1 lg:col-span-2 flex flex-col gap-5 lg:pt-8 pb-10">
            <div class="px-5 lg:px-0 text-center lg:text-left">
                <h2 class="text-4xl md:text-5xl font-bold text-zinc-900 mb-6 font-[Russo_One]">
                    Наш останній блог
                </h2>
                <p class="text-zinc-500 max-w-2xl leading-relaxed text-lg">
                    Ми ділимося досвідом, новинами та цікавими історіями зі світу ножів ручної роботи.
                </p>
            </div>

            <!-- Кнопка відкриття фільтрів на мобілці -->
            <div
                class="sticky lg:static top-16 z-40 px-5 py-2.5 lg:px-0 bg-white border-b lg:border-0 border-zinc-100 flex flex-col gap-0.5">
                <div class="flex justify-end">
                    <x-drawer>
                        <x-slot:trigger>
                            <x-button variant="ghost" color="dark" size="sm" icon class="border border-zinc-200">
                                <x-lucide-filter class="size-5 stroke-zinc-800" />
                            </x-button>
                        </x-slot:trigger>
                        <x-slot:header class="flex justify-between">
                            <div class="flex flex-col me-auto">
                                <div class="font-[Oswald] uppercase tracking-wider">Фільтри</div>
                            </div>
                            <x-button @click="open = false" color="dark" size="xs" class="ms-auto">
                                Показати
                            </x-button>
                        </x-slot:header>

                        @include('partials.blog.filters')
                    </x-drawer>
                </div>

                @if (!empty($this->activeFilters))
                    <div class="flex flex-wrap items-center gap-2 mt-4">
                        @foreach ($this->activeFilters as $filter)
                            <div
                                class="flex items-center gap-2 bg-zinc-50 px-3 py-1 rounded-full border border-zinc-100">
                                <span
                                    class="text-xs font-bold text-zinc-600 font-[Oswald] uppercase tracking-wider">{{ $filter['label'] }}</span>
                                <button
                                    wire:click="removeFilter('{{ $filter['type'] }}', '{{ $filter['slug'] ?? '' }}')"
                                    class="cursor-pointer">
                                    <x-lucide-x class="size-3 text-zinc-400 hover:text-red-500" />
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ХАОТИЧНА СІТКА (Masonry Layout) --}}
            <div class="px-5 lg:px-0 columns-1 md:columns-2 xl:columns-3 gap-10">
                @forelse($this->posts as $post)
                    <div class="break-inside-avoid">
                        @include('partials.blog.post-item')
                    </div>
                @empty
                    <div class="col-span-full">
                        @include('partials.blog.blog-empty')
                    </div>
                @endforelse
            </div>

            @if ($this->posts->hasMorePages())
                <div x-data x-intersect="$wire.loadMore()" class="w-full flex justify-center py-20">
                    <x-lucide-loader-circle class="size-8 text-orange-500 animate-spin" />
                </div>
            @endif
        </main>
    </div>
</section>
