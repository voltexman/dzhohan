<div class="h-[calc(100vh-120px)] lg:h-[calc(100vh-4rem)] lg:mt-4 lg:pr-8 flex flex-col justify-between">
    <x-scrollbar class="space-y-10 pt-4">
        <!-- Пошук -->
        <x-form.input size="sm" wire:model.trim.live.debounce.300ms="search" placeholder="Що шукаємо?"
            icon="search" />

        <!-- Категорії (Тип посту) -->
        <x-filter.tabs>
            <x-filter.tabs.item model="type" value="all" :current="$type">Всі</x-filter.tabs.item>
            @foreach (App\Enums\PostType::cases() as $postType)
                <x-filter.tabs.item model="type" :value="$postType->value" :current="$type">
                    {{ $postType->label() }}
                </x-filter.tabs.item>
            @endforeach
        </x-filter.tabs>

        <!-- Теги -->
        @if ($this->tags->isNotEmpty())
            <x-filter.group title="Теги" icon="tag" model="selectedTags" persist="filter-blog-tags">
                <div class="flex flex-wrap gap-2.5">
                    @foreach ($this->tags as $tag)
                        <x-filter.badge :value="$tag->slug" :label="$tag->name" model="selectedTags" :active="in_array($tag->slug, $selectedTags)" />
                    @endforeach
                </div>
            </x-filter.group>
        @endif
    </x-scrollbar>
</div>
