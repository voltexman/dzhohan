<div class=" h-[calc(100vh-120px)] lg:h-[calc(100vh-4rem)] lg:mt-4 lg:pr-8 flex flex-col justify-between">
    <x-scrollbar class="space-y-10 pt-4">
        <!-- 1. СТАТУС -->
        {{-- <x-filter.tabs>
            <x-filter.tabs.item model="status" value="all" :current="$status">Всі</x-filter.tabs.item>
            <x-filter.tabs.item model="status" value="in_stock" :current="$status">Наявні</x-filter.tabs.item>
            <x-filter.tabs.item model="status" value="sold" :current="$status">Продані</x-filter.tabs.item>
        </x-filter.tabs> --}}

        <!-- 2. БЮДЖЕТ -->
        <x-filter.group title="Бюджет" icon="wallet" model="price_from, price_to" persist="filter-price">
            <div class="space-y-5">
                <x-filter.range :min="$minLimit" :max="$maxLimit" from-model="price_from" to-model="price_to" />
            </div>
        </x-filter.group>

        @foreach ($this->allAttributes as $attribute)
            <x-filter.group :title="$attribute->name" :model="'filters.' . $attribute->slug" :persist="'filter-' . $attribute->slug">
                <div class="flex flex-wrap gap-2.5">
                    @foreach ($attribute->values as $value)
                        <x-filter.option :attribute="$attribute" :value="$value" :filters="$filters" />
                    @endforeach
                </div>
            </x-filter.group>
        @endforeach
    </x-scrollbar>
</div>
