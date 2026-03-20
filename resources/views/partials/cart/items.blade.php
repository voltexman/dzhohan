@use('App\Enums\CurrencyType')

<div class="flex gap-2.5 lg:gap-5 py-3 lg:py-4.5 items-start border-b border-gray-100 last:border-0">
    <div class="size-26 bg-zinc-50 rounded-md overflow-hidden shrink-0 border border-gray-100">
        <img src="{{ $item->image }}" class="size-full object-cover" alt="{{ $item->name }}">
    </div>

    <div class="flex flex-col grow min-w-0 self-stretch">
        <div class="flex flex-col space-y-0.5">
            <span class="font-bold text-gray-900 leading-snug line-clamp-1">
                {{ $item->name }}
            </span>
            <span class="text-sm font-medium text-gray-500">
                {{ CurrencyType::tryFrom($item->currency)?->format($item->price) }}
            </span>
        </div>

        @if ($item->qty > $item->stock)
            @php $preorderQty = $item->qty - $item->stock; @endphp
            <div class="mt-0.5 flex items-center text-xs leading-tight text-amber-600 font-medium tracking-wider">
                {{ $item->stock > 0 ? "{$preorderQty} шт. на виготовлення" : 'Під замовлення' }}
            </div>
        @endif

        <div class="flex items-center gap-2.5 mt-auto">
            <div class="flex items-center gap-2 bg-gray-50 rounded-full p-0.5 border border-gray-100">
                <x-button color="light" size="xs" icon class="rounded-full! shadow-sm"
                    wire:click="decrement({{ $item->id }})" wire:loading.attr="disabled"
                    wire:target="decrement({{ $item->id }})">
                    <x-lucide-minus wire:loading.remove wire:target="decrement({{ $item->id }})" class="size-3" />
                    <x-lucide-loader-2 wire:loading wire:target="decrement({{ $item->id }})"
                        class="size-3 animate-spin" />
                </x-button>

                <span class="text-sm font-bold w-6 text-center text-gray-800">{{ $item->qty }}</span>

                <x-button color="light" size="xs" icon class="rounded-full! shadow-sm"
                    wire:click="increment({{ $item->id }})" wire:loading.attr="disabled"
                    wire:target="increment({{ $item->id }})">
                    <x-lucide-plus wire:loading.remove wire:target="increment({{ $item->id }})" class="size-3" />
                    <x-lucide-loader-2 wire:loading wire:target="increment({{ $item->id }})"
                        class="size-3 animate-spin" />
                </x-button>
            </div>
        </div>
    </div>

    <div class="self-center pl-2">
        <x-button variant="ghost" color="light" size="sm" icon wire:click="remove({{ $item->id }})"
            wire:loading.attr="disabled" wire:target="remove({{ $item->id }})"
            class="hover:bg-red-50 group min-w-9">
            <x-lucide-trash-2 wire:loading.remove wire:target="remove({{ $item->id }})"
                class="size-5 stroke-gray-500 group-hover:stroke-red-500 transition-colors" stroke-width="1.5" />
            <x-lucide-loader-2 wire:loading wire:target="remove({{ $item->id }})"
                class="size-5 stroke-red-500 animate-spin" />
        </x-button>
    </div>
</div>
