<div x-show="$store.loader.show" x-cloak x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-zinc-50/95 backdrop-blur-xl z-[999] flex items-center justify-center transition-opacity duration-300">
    <div class="flex flex-col items-center">
        <div class="size-15 border-2 border-orange-500/30 border-t-orange-500 rounded-full animate-spin mb-10"></div>
        <div class="text-orange-500 font-[Russo_One] text-3xl font-bold tracking-wide">{{ env('APP_NAME') }}</div>
        <div class="text-orange-500 font-[Oswald] font-medium">Загартовуємо сталь...</div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        if (!Alpine.store('loader')) {
            Alpine.store('loader', {
                show: false
            });
        }

        const store = Alpine.store('loader');

        document.addEventListener('livewire:navigate', () => {
            store.show = true;
        });

        document.addEventListener('livewire:navigated', () => {
            setTimeout(() => {
                store.show = false;
            }, 150);
        });

        Livewire.hook('commit.start', () => {
            store.show = true;
        });

        Livewire.hook('commit', ({
            succeed,
            fail
        }) => {
            succeed(() => store.show = false);
            fail(() => store.show = false);
        });
    });
</script>
