<div class="hidden lg:grid lg:grid-cols-2 gap-2.5 mt10">
    @foreach ($collections as $collection)
        @php
            $count = $this->categoryCounts[$collection->value] ?? 0;
        @endphp
        <a href="{{ $collection->url() }}"
            class="first:col-span-full rounded-sm flex-none relative block overflow-hidden aspect-video group transition-all duration-700"
            wire:navigate>

            <!-- Зображення -->
            <img src="{{ Vite::asset('resources/images/' . $collection->images()) }}" alt="{{ $collection->getLabel() }}"
                class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">

            <!-- Градієнтне затемнення -->
            <div
                class="absolute inset-0 bg-linear-to-t from-15% from-black/90 via-black/30 to-transparent opacity-60 transition-opacity duration-500 group-hover:opacity-80">
            </div>

            <div class="absolute top-8 left-8">
                <span class="text-2xl font-[Oswald] text-neutral-100/70 font-black">
                    {{ $count }}
                    <span class="text-base">
                        {{ trans_choice('товар|товари|товарів', $count, [], 'uk') }}
                    </span>
                </span>
            </div>

            <!-- Контент -->
            <div class="absolute inset-0 flex flex-col justify-end p-8">
                <div class="flex flex-col gap-1.5">

                    <!-- Заголовок -->
                    <h3
                        class="text-white text-xl md:text-2xl font-black uppercase tracking-wide leading-tight font-[Oswald]">
                        {{ $collection->getLabel() }}
                    </h3>
                    <!-- Опис (спочатку невидимий) -->
                    <p
                        class="text-white/70 text-xs md:text-sm font-medium leading-relaxed line-clamp-2 opacity-0 max-h-0 overflow-hidden transition-all duration-500 group-hover:opacity-100 group-hover:max-h-20">
                        {{ $collection->description() }}
                    </p>

                    <!-- Кнопка "Переглянути" -->
                    <div class="">
                        <span
                            class="inline-flex items-center gap-2.5 text-xs font-bold uppercase text-orange-500 group-hover:text-amber-400 transition-colors">
                            Переглянути
                            <x-lucide-arrow-right
                                class="size-3 transition-transform duration-300 group-hover:translate-x-1" />
                        </span>
                    </div>
                </div>
            </div>
        </a>
    @endforeach
</div>
