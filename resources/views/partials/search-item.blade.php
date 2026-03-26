@use('App\Enums\ProductCategory')

<a href="{{ $product->category === ProductCategory::KNIFE
    ? route('knife.show', [
        'collection' => $product->collection?->value,
        'product' => $product,
    ])
    : route('material.show', [
        'product' => $product,
    ]) }}"
    wire:navigate class="flex items-center gap-2.5 p-1.5 rounded-sm hover:bg-zinc-100 transition-colors">
    <div class="size-14 bg-zinc-100 shrink-0 overflow-hidden">
        <img src="{{ $product->getFirstMediaUrl('products') }}" class="size-full object-cover rounded-sm"
            alt="{{ $product->name }}">
    </div>
    <div class="flex flex-col">
        <span class="font-medium text-zinc-900 leading-tight">
            {{ $product->name }}
        </span>
        <span class="text-sm text-zinc-500">
            {{ $product->currency->format($product->price) }}
        </span>
    </div>
    <x-lucide-chevron-right class="size-4 ms-auto stroke-zinc-400" />
</a>
