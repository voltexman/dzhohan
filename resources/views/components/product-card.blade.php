@props(['product', 'view', 'category'])

<a href="{{ route('product.show', $product) }}" @class([
    'relative transition group overflow-hidden',
    'bg-white border border-zinc-200/50 hover:border-zinc-200' =>
        $view === 'grid',
    'flex gap-5 items-center' => $view === 'list',
    'h-[400px] md:h-[400px]' => $view === 'cards',
    'opacity-80 grayscale-50' => !$product->hasStock(),
])
    wire:loading.class="animate-pulse pointer-events-none" wire:navigate>

    <!-- Зображення -->
    <div @class([
        'relative overflow-hidden transition duration-500',
        'w-full h-48 lg:h-72' => $view === 'grid',
        'size-32 flex-shrink-0' => $view === 'list',
        'absolute inset-0 size-full' => $view === 'cards',
    ])>
        <img src="{{ $product->getFirstMediaUrl('images', 'thumb') }}" alt="{{ $product->name }}"
            class="size-full object-cover group-hover:scale-105 transition duration-500">

        <!-- Кнопка Серце -->
        <x-button type="button" variant="soft" color="light" size="md" icon
            class="absolute top-2.5 right-2.5 z-30 size-8! rounded-sm bg-white/10 backdrop-blur-xs hover:bg-white border-white/10">
            <x-lucide-heart @class([
                'size-5',
                'fill-red-500 stroke-red-500' => $product->isLiked(),
                'stroke-white' => !$product->isLiked(),
            ]) />
        </x-button>

        {{-- Показуємо плашку лише якщо товару НЕМАЄ в наявності --}}
        @if (!$product->hasStock())
            <div @class([
                'absolute z-20 flex items-center justify-center',
                // Якщо режим 'cards' — зліва вгорі, інакше — справа внизу (як було)
                'top-3 left-3' => $view === 'cards',
                'bottom-2.5 right-2.5' => $view !== 'cards',
            ])>
                <span class="bg-white/25 text-white px-2.5 py-1.5 roundedfull text-xs shadow-xl">
                    Продано
                </span>
            </div>
        @endif
    </div>

    <!-- Контент -->
    <div @class([
        'flex-1 flex flex-col',
        'p-4' => $view === 'grid',
        'py-2' => $view === 'list',
        'absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-black/90 via-black/40 to-transparent' =>
            $view === 'cards',
    ])>
        <h3 @class([
            'font-semibold font-[SN_Pro] transition line-clamp-1 drop-shadow-xl',
            'text-xl text-gray-800 group-hover:text-orange-600' => $view !== 'grid',
            'text-xl text-gray-800 group-hover:text-orange-600' => $view !== 'list',
            'text-white text-xl md:text-2xl' => $view === 'cards',
        ])>
            {{ $product->name }}
        </h3>

        @if (!$category)
            <div @class([
                'font-[Oswald] tracking-wide w-full',
                'text-gray-600' => $view !== 'cards',
                'text-gray-400' => $view === 'cards',
            ])>
                {{ $product->category->label() }}
            </div>
        @endif

        {{-- Теги --}}
        <div @class([
            'w-full text-xs font-medium text-nowrap line-clamp-1 flex items-center justifycenter gap-1.5 mt-0.5',
            'text-zinc-500' => in_array($view, ['grid', 'list']),
            'text-zinc-300' => $view === 'cards',
        ])>
            @foreach ($product->tags->take(2) as $tag)
                <span class="flex items-center gap-1 whitespace-nowrap">
                    <x-lucide-tag class="size-3.5 shrink-0 fill-zinc-100 stroke-zinc-500" />
                    {{ $tag->name }}
                </span>
            @endforeach
        </div>

        <div class="flex items-center justify-between mt-0.5 w-full">
            <div class="flex justify-between w-full">
                {{-- Ціна --}}
                <div @class([
                    'w-full font-[Oswald] text-orange-500 font-semibold text-nowrap',
                    'text-lg' => $view === 'grid',
                    'text-base' => $view === 'list',
                    'text-xl' => $view === 'cards',
                ])>
                    {{ number_format($product->price, 0, '.', ' ') }} <span class="text-xs uppercase ml-0.5">грн</span>
                </div>

                {{-- Метрики (Лайки / Коментарі) --}}
                @if ($product->likes_count || $product->comments_count)
                    <div @class([
                        'flex items-center gap-1.5',
                        // 'text-zinc-300' => $view === 'grid',
                        // 'text-zinc-300' => $view === 'list',
                        // 'text-zinc-300' => $view === 'cards',
                    ])>
                        @if ($product->comments_count)
                            <div @class([
                                'flex items-center gap-0.5 text-xs font-medium transition-colors duration-200',
                                'text-zinc-500' => in_array($view, ['grid', 'list', 'cards']),
                            ])>
                                <x-lucide-message-circle
                                    class="size-3.5 mb-0.5 shrink-0 fill-zinc-100 stroke-zinc-500" />

                                <span>{{ $product->comments_count }}</span>
                            </div>
                        @endif

                        @if ($product->likes_count)
                            <div @class([
                                'flex items-center gap-0.5 text-xs font-medium transition-colors duration-200',
                                // Основний колір для всіх режимів перегляду (якщо НЕ лайкнуто)
                                'text-zinc-500' =>
                                    !$product->isLiked() && in_array($view, ['grid', 'list', 'cards']),
                                // Червоний колір, якщо лайкнуто
                                'text-red-500' => $product->isLiked(),
                            ])>
                                <x-lucide-heart @class([
                                    'size-3.5 mb-0.5 transition-all',
                                    'fill-red-500 stroke-red-500 scale-110' => $product->isLiked(),
                                    'fill-zinc-100 stroke-zinc-500' => !$product->isLiked(),
                                ]) />

                                <span>{{ $product->likes_count }}</span>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- Відображаємо опис для Списку та Карток --}}
        @if ($view === 'list' || $view === 'cards')
            <p @class([
                'mt-2.5 line-clamp-2',
                'text-zinc-700 text-sm max-w-lg' => $view === 'list',
                'text-zinc-300 text-base mt-2.5 max-w-xl' => $view === 'cards',
            ])>
                {{ $product->description }}
            </p>
        @endif
    </div>
</a>
