@props(['active' => false])

<button
    {{ $attributes->merge([
        'type' => 'button',
        'class' =>
            'w-full flex items-center justify-between text-nowrap px-5 py-2.5 rounded-md text-sm font-medium tracking-tight transition group disabled:opacity-70 cursor-pointer ' .
            ($active ? 'bg-neutral-100 text-black' : 'text-neutral-500 hover:bg-neutral-50'),
    ]) }}>

    <div class="flex items-center gap-2.5">
        {{ $slot }}
    </div>

    <div class="relative size-5 flex items-center justify-center ms-1.5">
        <div x-show="loading" x-cloak>
            <x-lucide-loader-circle class="size-4 animate-spin text-zinc-500" />
        </div>

        <!-- Показуємо крапку тільки якщо НЕ завантажується -->
        <div x-show="!loading">
            <div
                class="size-4 rounded-full border-2 flex items-center justify-center transition-all 
                {{ $active ? 'border-stone-900 bg-stone-900' : 'border-zinc-300' }}">
                <div class="size-1.5 rounded-full bg-white transition-transform {{ $active ? 'scale-100' : 'scale-0' }}">
                </div>
            </div>
        </div>
    </div>
</button>
