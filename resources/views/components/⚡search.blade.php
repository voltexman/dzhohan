<?php

use Illuminate\Support\Str;
use App\Models\Product;
use App\Enums\ProductCategory;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Session;
use Livewire\Component;

new class extends Component {
    public string $position = '';

    #[Session]
    public string $search = '';

    #[Computed]
    public function results()
    {
        if (strlen($this->search) < 2) {
            return collect();
        }

        return Product::where('name', 'like', '%' . trim($this->search) . '%')
            ->limit(5)
            ->get();
    }
};
?>

<x-offcanvas :position="$this->position">
    <x-slot:trigger>
        <x-lucide-search class="size-6" />

        @if ($search)
            <span class="absolute top-0 right-0 flex size-2">
                <span class="absolute inline-flex size-full animate-ping rounded-full bg-orange-400 opacity-75"></span>
                <span class="relative inline-flex size-2 rounded-full bg-orange-500"></span>
            </span>
        @endif
    </x-slot:trigger>

    <x-slot:header>Пошук</x-slot:header>

    @if (Str::length($search) < 2)
        <div class="flex flex-col justify-center items-center size-full">
            <x-lucide-search class="size-15 opacity-50" stroke-width="1.5" />
            <span class="font-semibold text-lg mt-5">Пошук матеріалів</span>
            <span class="text-gray-600 text-sm text-center max-w-xs">
                Введіть назву товара або статті для пошуку...
            </span>
        </div>
    @elseif($this->results->isEmpty())
        <div class="flex flex-col justify-center items-center size-full">
            <x-lucide-search-x class="size-15 opacity-50" stroke-width="1.5" />
            <span class="font-semibold text-lg mt-5">Нічого не знайдено</span>
            <span class="text-gray-500 text-sm text-center max-w-xs">
                Ми не знайшли товарів за запитом "{{ $search }}". Спробуйте іншу назву.
            </span>
        </div>
    @else
        <div class="flex flex-col gap-2.5">
            <span class="text-xs font-bold uppercase tracking-wider text-zinc-500">
                Результати пошуку
            </span>

            @each('partials.search-item', $this->results, 'product')
        </div>
    @endif

    <x-slot:footer>
        <div class="relative">
            <x-form.input wire:model.trim.live.debounce.300ms="search" color="soft" size="lg" icon="search"
                placeholder="Що ви шукаєте?" class="w-full" />

            @if ($search)
                <button type="button" wire:click="$set('search', '')"
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-zinc-500 hover:text-zinc-800 transition-colors duration-300 cursor-pointer">
                    <x-lucide-loader-circle wire:loading.delay wire:target="search"
                        class="size-5 stroke-gray-700 animate-spin" />
                    <x-lucide-circle-x wire:loading.remove class="size-5 shrink-0" />
                </button>
            @endif
        </div>
    </x-slot:footer>
</x-offcanvas>
