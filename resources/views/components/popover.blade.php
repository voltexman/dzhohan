@props(['icon' => 'info', 'title'])

<div x-data="{
    open: false,

    // 'hover focus', 'click'
    trigger: 'hover focus',
}" class="relative inline-block">
    <button x-on:mouseenter="(trigger === 'hover focus') ? open = true : null"
        x-on:mouseleave="(trigger === 'hover focus') ? open = false : null"
        x-on:focus="(trigger === 'hover focus') ? open = true : null"
        x-on:blur="(trigger === 'hover focus') ? open = false : null"
        x-on:click="(trigger === 'click') ? open = !open : null" type="button"
        class="inline-flex items-center justify-center self-center cursor-pointer -mb-1.5">
        <x-dynamic-component :component="'lucide-' . $icon" class="size-4 fill-zinc-100 stroke-zinc-600 shrink-0 inline-flex" />
    </button>
    <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-10"
        class="absolute bottom-full start-1/2 z-10 -ms-36 flex w-72 origin-bottom flex-col items-center justify-center pb-0.5 will-change-transform">
        <div class="overflow-hidden rounded-md border border-zinc-200 bg-white text-start text-sm">
            @isset($title)
                <h4 {{ $title->attributes->class('border-b border-b-zinc-100 bg-zinc-50 px-3 py-2.5 font-medium') }}>
                    {{ $title }}
                </h4>
            @endisset
            <p class="p-2.5 text-zinc-600 text-xs">
                {{ $slot }}
            </p>
        </div>
        <div class="relative z-10 -mt-px h-0 w-0 flex-none border-e-8 border-s-8 border-t-8 border-e-transparent border-s-transparent border-t-white"
            aria-hidden="true"></div>
        <div class="relative z-0 -mt-[7px] h-0 w-0 flex-none border-e-8 border-s-8 border-t-8 border-e-transparent border-s-transparent border-t-zinc-300"
            aria-hidden="true"></div>
    </div>
</div>
