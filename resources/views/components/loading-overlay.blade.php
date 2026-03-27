<div x-data x-init="Alpine.store('loader', { show: false });

// Переходи по wire:navigate
document.addEventListener('livewire:navigate', () => {
    Alpine.store('loader').show = true;
});

document.addEventListener('livewire:navigated', () => {
    setTimeout(() => {
        Alpine.store('loader').show = false;
    }, 100);
});

// Звичайні Livewire запити (wire:click, форми тощо)
Livewire.hook('commit.start', () => {
    Alpine.store('loader').show = true;
});

Livewire.hook('commit', ({ succeed, fail }) => {
    succeed(() => Alpine.store('loader').show = false);
    fail(() => Alpine.store('loader').show = false);
});" x-show="$store.loader.show" x-cloak
    class="fixed inset-0 bg-zinc-950/90 backdrop-blur-sm z-[999] flex items-center justify-center transition-opacity duration-300">
    <div class="flex flex-col items-center">
        <div class="size-15 border-2 border-orange-500/30 border-t-orange-500 rounded-full animate-spin mb-5"></div>
        <div class="text-orange-500 font-[Russo_One] text-3xl font-bold tracking-wide">{{ env('APP_NAME') }}</div>
        <div class="text-orange-500 font-[Oswald] font-semibold">Загартовуємо сталь...</div>
    </div>
</div>
