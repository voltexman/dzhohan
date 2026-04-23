@php
    // Динамічні пропорції для різноманітності
    $aspectRatio = match($loop->iteration % 3) {
        0 => 'aspect-[4/5]',   // Високе
        1 => 'aspect-square',  // Квадратне
        default => 'aspect-[16/10]', // Стандартне
    };
@endphp

<article class="break-inside-avoid group flex flex-col overflow-hidden rounded-lg hover:shadow-2xl transition-all duration-300 mb-10 border border-zinc-700/30">
    {{-- Зображення з контентом над ним --}}
    <div @class([
        'relative overflow-hidden bg-zinc-900',
        $aspectRatio
    ])>
        {{-- Фонове зображення --}}
        <img src="{{ Vite::asset('resources/images/header.png') }}" 
             alt="{{ $post->name }}"
             class="absolute inset-0 size-full object-cover transition duration-500 group-hover:scale-110">
        
        {{-- Затемнення грядієнт (знизу вгору) --}}
        <div class="absolute inset-0 bg-gradient-to-t from-zinc-900/95 via-zinc-900/50 to-transparent"></div>

        {{-- Категорія в лівому верхньому куті --}}
        <div class="absolute top-3 left-3 z-10">
            <span class="px-3 py-1 bg-orange-600/80 backdrop-blur-sm text-[10px] font-bold uppercase tracking-wider text-white rounded-full font-[Oswald]">
                {{ $post->type->label() }}
            </span>
        </div>

        {{-- Мета (лайки/коментарі) в верхньому правому куті --}}
        <div class="absolute top-3 right-3 flex gap-2 z-10">
            <div class="flex items-center gap-1 px-2 py-1 rounded-full bg-white/15 backdrop-blur-sm text-[10px] font-bold font-[Oswald] text-white shadow-sm">
                <x-lucide-heart class="size-3 text-orange-400 fill-orange-400" />
                {{ $post->likes_count ?: 0 }}
            </div>
            <div class="flex items-center gap-1 px-2 py-1 rounded-full bg-white/15 backdrop-blur-sm text-[10px] font-bold font-[Oswald] text-white shadow-sm">
                <x-lucide-message-circle class="size-3 text-orange-400" />
                {{ $post->comments_count ?: 0 }}
            </div>
        </div>

        {{-- Контентна частина (над зображенням) --}}
        <div class="absolute inset-0 flex flex-col justify-end p-5 z-20">
            {{-- Дата --}}
            <div class="flex items-center gap-2 mb-3">
                <x-lucide-calendar class="size-3.5 text-orange-400" />
                <span class="text-[11px] font-bold text-orange-300 uppercase tracking-widest font-[Oswald]">
                    {{ $post->created_at->format('d.m.Y') }}
                </span>
            </div>

            {{-- Заголовок --}}
            <h3 class="text-xl font-bold text-white leading-tight mb-2 font-[SN_Pro] group-hover:text-orange-300 transition-colors line-clamp-2">
                <a href="{{ route('blog.show', $post) }}" wire:navigate>
                    {{ $post->name }}
                </a>
            </h3>

            {{-- Теги та кнопка читати --}}
            <div class="flex items-center justify-between pt-3 border-t border-white/10">
                <div class="flex gap-2">
                    @foreach ($post->tags->take(2) as $tag)
                        <span class="text-[10px] text-orange-300/80 font-medium italic font-[Oswald]">#{{ $tag->name }}</span>
                    @endforeach
                </div>

                <a href="{{ route('blog.show', $post) }}" wire:navigate
                    class="inline-flex items-center gap-1.5 text-xs font-bold text-white hover:text-orange-300 transition-all font-[Oswald] bg-white/10 hover:bg-orange-600/20 px-3 py-1 rounded-full backdrop-blur-sm">
                    Читати
                    <x-lucide-chevron-right class="size-4" />
                </a>
            </div>
        </div>
    </div>
</article>
