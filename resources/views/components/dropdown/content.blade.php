<div x-show="open" x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 scale-95 translate-y-1"
    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
    class="absolute left-0 mt-2.5 z-20 space-y-1.5 w-full md:w-fit p-1.5 bg-white border border-stone-100 rounded-md shadow-lg shadow-stone-400/50 outline-none"
    x-cloak>
    {{ $slot }}
</div>
