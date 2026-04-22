@php
    // Повертаємо хаотичність через зміну висоти зображення, як у попередній вимозі
    $aspectRatio = match($loop->iteration % 3) {
        0 => 'aspect-[4/5]',   // Високе
        1 => 'aspect-square',  // Квадратне
        default => 'aspect-[16/10]', // Стандартне
    };
@endphp

<article class="break-inside-avoid group flex flex-col bg-zinc-100 border border-zinc-200 rounded-md overflow-hidden hover:shadow-xl transition-all duration-300 mb-10">
    {{-- Зображення з динамічними пропорціями --}}
    <div @class([
        'relative overflow-hidden bg-zinc-100',
        $aspectRatio
    ])>
        <img src="{{ Vite::asset('resources/images/header.png') }}" 
             alt="{{ $post->name }}"
             class="absolute inset-0 size-full object-cover transition duration-500 group-hover:scale-105">
        
        {{-- Категорія зверху --}}
        <div class="absolute top-3 left-3">
            <span class="px-2 py-1 bg-zinc-900/80 backdrop-blur text-[10px] font-bold uppercase tracking-wider text-white rounded-sm font-[Oswald]">
                {{ $post->type->label() }}
            </span>
        </div>

        {{-- Мета (лайки/коментарі) --}}
        <div class="absolute bottom-3 right-3 flex gap-2">
            <div class="flex items-center gap-1 px-2 py-1 rounded-sm bg-white/90 backdrop-blur text-[10px] font-bold font-[Oswald] text-zinc-800 shadow-sm">
                <x-lucide-heart class="size-3 text-orange-500 fill-orange-500" />
                {{ $post->likes_count ?: 0 }}
            </div>
            <div class="flex items-center gap-1 px-2 py-1 rounded-sm bg-white/90 backdrop-blur text-[10px] font-bold font-[Oswald] text-zinc-800 shadow-sm">
                <x-lucide-message-circle class="size-3 text-zinc-500" />
                {{ $post->comments_count ?: 0 }}
            </div>
        </div>
    </div>

    {{-- Контентна частина --}}
    <div class="p-5 flex flex-col flex-1">
        <div class="flex items-center gap-2 mb-2">
            <x-lucide-calendar class="size-3 text-zinc-400" />
            <span class="text-[11px] font-bold text-zinc-400 uppercase tracking-widest font-[Oswald]">
                {{ $post->created_at->format('d.m.Y') }}
            </span>
        </div>

        <h3 class="text-lg font-bold text-zinc-900 leading-tight mb-3 font-[SN_Pro] group-hover:text-orange-600 transition-colors">
            <a href="{{ route('blog.show', $post) }}" wire:navigate>
                {{ $post->name }}
            </a>
        </h3>

        @isset($post->excerpt)
            <p class="text-zinc-500 text-sm leading-relaxed line-clamp-3 mb-6">
                {{ $post->excerpt }}
            </p>
        @endisset

        <div class="mt-auto pt-4 border-t border-zinc-100 flex items-center justify-between">
            <div class="flex gap-2">
                @foreach ($post->tags->take(2) as $tag)
                    <span class="text-[10px] text-zinc-400 font-medium italic font-[Oswald]">#{{ $tag->name }}</span>
                @endforeach
            </div>

            <a href="{{ route('blog.show', $post) }}" wire:navigate
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-zinc-900 hover:text-orange-600 transition-all font-[Oswald]">
                Читати
                <x-lucide-chevron-right class="size-4" />
            </a>
        </div>
    </div>
</article>
