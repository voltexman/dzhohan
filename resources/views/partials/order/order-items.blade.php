@use('App\Enums\CurrencyType')

<div class="py-5 flex flex-col" wire:key="order-item-{{ $item->id }}">
    <div class="flex justify-between gap-5">
        <div class="flex flex-col">
            <span class="font-bold text-gray-900 leading-tight">{{ $item->name }}</span>
            <span class="text-sm text-gray-500">
                <span class="font-semibold">{{ $item->qty }}</span>
                шт. ×
                <span class="font-medium">
                    {{ CurrencyType::tryFrom($item->currency)?->format($item->price) }}
                </span>
            </span>
        </div>
        <div class="flex flex-col items-end">
            <span class="font-bold whitespace-nowrap text-gray-900">
                {{ CurrencyType::tryFrom($item->currency)?->format($item->price * $item->qty) }}
            </span>
        </div>
    </div>

    {{-- Блок попередження про виготовлення під замовлення --}}
    @if ($item->qty > $item->stock)
        @php $preorderCount = $item->qty - $item->stock; @endphp
        <div class="text-xs font-medium tracking-wider text-amber-600">
            @if ($item->stock > 0)
                {{ $preorderCount }}
                {{ trans_choice('шт. на виготовлення', $preorderCount, [], 'uk') }}
            @else
                Замовлення на виготовлення
            @endif
        </div>
    @endif
</div>
