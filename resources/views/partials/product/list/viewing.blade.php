@php
    $viewOptions = [
        [
            'label' => 'Сітка',
            'icon' => 'lucide-layout-grid',
            'value' => 'grid',
        ],
        [
            'label' => 'Список',
            'icon' => 'lucide-list',
            'value' => 'list',
        ],
        [
            'label' => 'Картки',
            'icon' => 'lucide-layout-template',
            'value' => 'cards',
        ],
    ];
@endphp

<x-drawer class="lg:hidden">
    <x-slot:trigger>
        <x-button variant="ghost" color="dark" size="sm" icon>
            <div class="relative flex items-center justify-center size-5">
                <div wire:loading.remove wire:target="setView">
                    @php
                        $currentIcon = match ($view) {
                            'grid' => 'lucide-layout-grid',
                            'list' => 'lucide-list',
                            'cards' => 'lucide-layout-template',
                            default => 'lucide-layout-grid',
                        };
                    @endphp
                    <x-dynamic-component :component="$currentIcon" class="size-5 stroke-zinc-700" />
                </div>

                <div wire:loading wire:target="setView">
                    <x-lucide-loader-circle class="size-5 animate-spin text-zinc-500" />
                </div>
            </div>
        </x-button>
    </x-slot:trigger>

    <x-slot:header>Відображення</x-slot:header>

    <div class="space-y-1.5 my-5">
        @foreach ($viewOptions as $opt)
            <button type="button" wire:click="setView('{{ $opt['value'] }}')" x-on:click="open = false"
                @disabled($view === $opt['value'])
                class="w-full flex items-center justify-between ... {{ $view === $opt['value'] ? 'bg-neutral-100' : '' }}">

                <div class="flex items-center gap-2.5">
                    <x-dynamic-component :component="$opt['icon']" class="size-4" />
                    <span>{{ $opt['label'] }}</span>
                </div>

                <div class="relative size-5 flex items-center justify-center ms-1.5">
                    {{-- Спінер спрацює на БУДЬ-ЯКИЙ виклик setView --}}
                    <div wire:loading wire:target="setView">
                        <x-lucide-loader-circle class="size-4 animate-spin text-zinc-500" />
                    </div>

                    {{-- Крапка зникає, поки йде БУДЬ-ЯКЕ завантаження setView --}}
                    <div wire:loading.remove wire:target="setView">
                        <div class="size-4 rounded-full border-2 ...">
                            <div
                                class="size-1.5 rounded-full ... {{ $view === $opt['value'] ? 'scale-100' : 'scale-0' }}">
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
        <x-button variant="ghost" color="light" size="sm" class="gap-x.1.5 lg:py-3.5!">
            @if ($view === 'grid')
                <x-lucide-layout-grid class="size-4 me-1.5" />
                <span>Сітка</span>
            @elseif ($view === 'list')
                <x-lucide-list class="size-4 me-1.5" />
                <span>Список</span>
            @else
                <x-lucide-layout-template class="size-4 me-1.5" />
                <span>Картки</span>
            @endif
            <x-lucide-chevron-down class="size-3.5 transition-transform duration-300"
                x-bind:class="open ? 'rotate-180' : ''" />
        </x-button>
    </x-slot:trigger>

    <x-dropdown.content>
        <!-- Варіант: Сітка -->
        <x-dropdown.item x-data="{ loading: false }"
            @click="loading = true; $wire.setView('grid').then(() => { loading = false; open = false; })"
            :active="$view === 'grid'" x-bind:disabled="loading">
            <x-lucide-layout-grid class="size-4" />
            <span class="font-medium">Сітка</span>
        </x-dropdown.item>

        <!-- Варіант: Список -->
        <x-dropdown.item x-data="{ loading: false }"
            @click="loading = true; $wire.setView('list').then(() => { loading = false; open = false; })"
            :active="$view === 'list'" x-bind:disabled="loading">
            <x-lucide-list class="size-4" />
            <span class="font-medium">Список</span>
        </x-dropdown.item>

        <!-- Варіант: Картки -->
        <x-dropdown.item x-data="{ loading: false }"
            @click="loading = true; $wire.setView('cards').then(() => { loading = false; open = false; })"
            :active="$view === 'cards'" x-bind:disabled="loading">
            <x-lucide-layout-template class="size-4" />
            <span class="font-medium">Картки</span>
        </x-dropdown.item>
    </x-dropdown.content>
</x-dropdown>
