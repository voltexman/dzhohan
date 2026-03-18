@php
    $sortOptions = [
        [
            'label' => 'Дешевші спочатку',
            'icon' => 'lucide-trending-up',
            'column' => 'price',
            'direction' => 'asc',
        ],
        [
            'label' => 'Дорожчі спочатку',
            'icon' => 'lucide-trending-down',
            'column' => 'price',
            'direction' => 'desc',
        ],
        [
            'label' => 'Новинки',
            'icon' => 'lucide-sparkles',
            'column' => 'created_at',
            'direction' => 'desc',
        ],
    ];
@endphp

<x-drawer class="ms-auto lg:hidden">
    <x-slot:trigger>
        <x-button variant="ghost" color="light" size="sm" icon>
            <div class="relative flex items-center justify-center size-5">
                <div wire:loading.remove wire:target="setSort">
                    @if ($sortBy === 'price' && $sortDirection === 'asc')
                        <x-lucide-trending-up class="size-5" />
                    @elseif ($sortBy === 'price' && $sortDirection === 'desc')
                        <x-lucide-trending-down class="size-5" />
                    @elseif ($sortBy === 'created_at')
                        <x-lucide-sparkles class="size-5" />
                    @else
                        <x-lucide-arrow-up-down class="size-5" />
                    @endif
                </div>

                {{-- 2. Цей блок ПРИХОВАНИЙ завжди, крім моменту виконання setSort --}}
                <div wire:loading wire:target="setSort">
                    <x-lucide-loader-circle class="size-5 animate-spin text-zinc-500" />
                </div>
            </div>

        </x-button>
    </x-slot:trigger>

    <x-slot:header>Сортування</x-slot:header>

    <div class="space-y-1.5 my-5">
        @foreach ($sortOptions as $option)
            @php
                $isActive = $sortBy === $option['column'] && $sortDirection === $option['direction'];
                $target = "setSort('{$option['column']}','{$option['direction']}')";
            @endphp

            <button type="button" wire:click="{{ $target }}" x-on:click="open = false"
                @disabled($isActive)
                class="w-full flex items-center justify-between text-nowrap px-5 py-2.5 rounded-md text-sm font-medium tracking-tight transition group cursor-pointer 
        {{ $isActive ? 'bg-neutral-100 text-black' : 'text-neutral-500 hover:bg-neutral-50' }}">

                <div class="flex items-center gap-2.5">
                    <x-dynamic-component :component="$option['icon']" class="size-4" />
                    <span>{{ $option['label'] }}</span>
                </div>

                <div class="relative size-5 flex items-center justify-center ms-1.5">
                    {{-- Спінер показується тільки для цієї конкретної кнопки --}}
                    <div wire:loading wire:target="{{ $target }}">
                        <x-lucide-loader-circle class="size-4 animate-spin text-zinc-500" />
                    </div>

                    {{-- Крапка показується, коли ця кнопка НЕ завантажується --}}
                    <div wire:loading.remove wire:target="{{ $target }}">
                        <div
                            class="size-4 rounded-full border-2 flex items-center justify-center transition-all 
                    {{ $isActive ? 'border-stone-900 bg-stone-900' : 'border-zinc-300' }}">
                            <div
                                class="size-1.5 rounded-full bg-white transition-transform {{ $isActive ? 'scale-100' : 'scale-0' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </button>
        @endforeach
    </div>
</x-drawer>

<x-dropdown class="hidden lg:block">
    <x-slot:trigger>
        <x-button variant="soft" color="dark" size="sm" class="gap-x-1.5 lg:py-3.5!">
            @if ($sortBy === 'price' && $sortDirection === 'asc')
                <x-lucide-trending-up class="size-4" />
                <span>Дешевші</span>
            @elseif ($sortBy === 'price' && $sortDirection === 'desc')
                <x-lucide-trending-down class="size-4" />
                <span>Дорожчі</span>
            @elseif ($sortBy === 'created_at')
                <x-lucide-sparkles class="size-4" />
                <span>Новинки</span>
            @else
                <x-lucide-arrow-up-down class="size-4" />
                <span>Сортувати</span>
            @endif
            <x-lucide-chevron-down class="size-3.5 transition-transform duration-300"
                x-bind:class="open ? 'rotate-180' : ''" />
        </x-button>
    </x-slot:trigger>

    <x-dropdown.content>
        <!-- Дешевші спочатку -->
        <x-dropdown.item x-data="{ loading: false }"
            @click="loading=true; $wire.setSort('price','asc').then(() => {loading=false; open=false;})"
            :active="$sortBy === 'price' && $sortDirection === 'asc'">
            <x-lucide-trending-up class="size-4" />
            <span>Дешевші спочатку</span>
        </x-dropdown.item>

        <!-- Дорожчі спочатку -->
        <x-dropdown.item x-data="{ loading: false }"
            @click="loading=true; $wire.setSort('price','desc').then(() => {loading=false; open=false;})"
            :active="$sortBy === 'price' && $sortDirection === 'desc'" x-bind:disabled="loading">
            <x-lucide-trending-down class="size-4" />
            <span>Дорожчі спочатку</span>
        </x-dropdown.item>

        <!-- Новинки -->
        <x-dropdown.item x-data="{ loading: false }"
            @click="loading=true; $wire.setSort('created_at','desc').then(() => {loading=false; open=false;})"
            :active="$sortBy === 'created_at' && $sortDirection === 'desc'" x-bind:disabled="loading">
            <x-lucide-sparkles class="size-4" />
            <span>Новинки</span>
        </x-dropdown.item>
    </x-dropdown.content>
</x-dropdown>
