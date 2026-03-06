<div class="py-5 flex gap-5 items-center">
    <div class="size-20 bg-zinc-50 rounded-md overflow-hidden shrink-0">
        <img src="{{ $item->image }}" class="size-full object-cover" alt="">
    </div>

    <div class="flex flex-col grow">
        <span class="font-medium text-gray-900 leading-tight line-clamp-1">
            {{ $item->name }}
        </span>
        <span class="text-sm text-gray-500 mt-1">
            {{ number_format($item->price, 0, '.', ' ') }} грн
        </span>

        <!-- Керування кількісqтю -->
        <div class="flex items-center gap-2.5 mt-1.5">
            <x-button color="light" size="xs" icon class="rounded-full!"
                wire:click="decrement({{ $item->id }})" wire:loading.attr="disabled"
                wire:target="decrement({{ $item->id }})">
                <x-lucide-plus wire:loading.remove wire:target="decrement({{ $item->id }})" class="size-3" />
                <x-lucide-loader-circle wire:loading wire:target="decrement({{ $item->id }})"
                    class="size-3 animate-spin" />
            </x-button>

            <span class="text-sm font-semibold">{{ $item->qty }}</span>

            <x-button color="light" size="xs" icon class="rounded-full!"
                wire:click="increment({{ $item->id }})" wire:loading.attr="disabled"
                wire:target="increment({{ $item->id }})">
                <x-lucide-plus wire:loading.remove wire:target="increment({{ $item->id }})" class="size-3" />
                <x-lucide-loader-circle wire:loading wire:target="increment({{ $item->id }})"
                    class="size-3 animate-spin" />
            </x-button>
        </div>
    </div>

    <x-button variant="ghost" color="light" size="sm" icon wire:click="remove({{ $item->id }})"
        wire:loading.attr="disabled" wire:target="remove({{ $item->id }})">
        <x-lucide-trash wire:loading.remove wire:target="remove({{ $item->id }})" class="size-5 stroke-red-500"
            stroke-width="1.5" />
        <x-lucide-loader-circle wire:loading wire:target="remove({{ $item->id }})"
            class="size-5 animate-spin stroke-red-500" />
    </x-button>
</div>
