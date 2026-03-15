<div class="lg:h-[calc(100vh-4rem)] lg:mt-4 lg:pr-8 flex flex-col justify-between overflow-hidden">
    <x-scrollbar class="space-y-10 lg:pt-4">
        <!-- 1. СТАТУС -->
        <x-filter.tabs>
            <x-filter.tabs.item model="status" value="all" :current="$status">Всі</x-filter.tabs.item>
            <x-filter.tabs.item model="status" value="in_stock" :current="$status">Наявні</x-filter.tabs.item>
            <x-filter.tabs.item model="status" value="sold" :current="$status">Продані</x-filter.tabs.item>
        </x-filter.tabs>

        <!-- 2. БЮДЖЕТ -->
        <x-filter.group title="Бюджет" icon="wallet" persist="filter-price">
            <x-filter.range :min="$minLimit" :max="$maxLimit" from-model="price_from" to-model="price_to" />
        </x-filter.group>

        <!-- 3. КОЛЕКЦІЇ -->
        @if (!$collection)
            <div class="space-y-5" x-data="{ expanded: $persist(true).as('collection-expanded') }" wire:loading.class="animate-pulse pointer-events-none"
                wire:target="collections" x-cloak>

                <div class="flex items-center justify-between w-full group outline-none">
                    <div
                        class="flex items-center gap-1.5 text-sm font-extrabold uppercase text-neutral-600 font-[Oswald] tracking-wide">
                        <x-lucide-layers class="size-4" />
                        Колекції
                    </div>

                    {{-- 2. Безпечна перевірка масиву --}}
                    @if (is_array($collections) && count($collections) > 0)
                        <button wire:click="$set('collections', [])" type="button"
                            class="flex items-center text-xs ms-auto me-1.5 text-neutral-400 hover:text-neutral-500 font-medium transition-colors duration-250 cursor-pointer">
                            <x-lucide-x-circle class="size-4 border border-neutral-200 rounded-full flex-none me-0.5" />
                            очистити
                        </button>
                    @endif

                    <button @click="expanded = !expanded" type="button">
                        <x-lucide-chevron-down class="size-4 text-neutral-500 transition-transform duration-300"
                            x-bind:class="expanded ? 'rotate-180' : ''" />
                    </button>
                </div>

                <div x-show="expanded" x-collapse>
                    <div class="flex flex-wrap gap-2.5">
                        @foreach (App\Enums\ProductCategory::cases() as $categoryCase)
                            {{-- 3. Перевірка активності через колекцію --}}
                            @php $isActive = is_array($collections) && in_array($categoryCase->value, $collections); @endphp

                            <label
                                class="relative inline-flex items-center px-2.5 py-1.5 rounded-sm border cursor-pointer transition-all duration-300 select-none
                        {{ $isActive ? 'bg-neutral-900 border-neutral-900 text-white' : 'bg-white border-neutral-200 text-gray-600 hover:border-neutral-200 hover:bg-neutral-100' }}">

                                <input type="checkbox" value="{{ $categoryCase->value }}" wire:model.live="collections"
                                    class="hidden">

                                <span class="text-xs font-semibold tracking-tight">
                                    {{ $categoryCase->getLabel() }}
                                </span>

                                @if ($isActive)
                                    <x-lucide-x class="size-3.5 ml-1.5 text-stone-400" />
                                @endif
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- ФІЛЬТР: МАРКА СТАЛІ -->
        <x-filter.group title="Марка сталі" icon="anvil" model="steels" persist="filter-steel">
            <div class="flex flex-wrap gap-1.5">
                @foreach (App\Enums\SteelType::cases() as $steel)
                    <x-filter.checkbox :value="$steel->value" :label="$steel->getLabel()" model="steels" />
                @endforeach
            </div>
        </x-filter.group>

        <!-- ФІЛЬТР: МАТЕРІАЛ РУКІВ'Я -->
        <x-filter.group title="Матеріал руків`я" icon="anvil" model="handle_materials" persist="handle_materials">
            <div class="flex flex-wrap gap-1.5">
                @foreach (App\Enums\HandleMaterial::cases() as $material)
                    <x-filter.checkbox :value="$material->value" :label="$material->getLabel()" model="handle_materials" />
                @endforeach
            </div>
        </x-filter.group>

        <!-- 4. ПРОФІЛЬ КЛИНКА -->
        <x-filter.group title="Профіль клинка" icon="spline" model="blade_shapes" persist="blade_shapes">
            <div class="flex flex-wrap gap-1.5">
                @foreach (App\Enums\BladeShape::cases() as $shape)
                    <x-filter.checkbox :value="$shape->value" :label="$shape->getLabel()" model="blade_shapes" />
                @endforeach
            </div>
        </x-filter.group>

        {{-- 5. ТИПИ СПУСКІВ --}}
        <x-filter.group title="Типи спусків" icon="triangle" model="blade_grinds" persist="blade_grinds">
            <div class="flex flex-wrap gap-1.5">
                @foreach (App\Enums\BladeGrind::cases() as $grind)
                    <x-filter.checkbox :value="$grind->value" :label="$grind->getLabel()" model="blade_grinds" />
                @endforeach
            </div>
        </x-filter.group>
    </x-scrollbar>

    <!-- 4. ОЧИСТИТИ ВСЕ -->
    <div class="shrink-0 py-2.5 lg:py-5">
        <button wire:click="resetFilters"
            class="group w-fit mx-auto h-full flex items-center justify-center gap-1.5 text-xs text-red-500 hover:text-red-500 uppercase font-semibold cursor-pointer">
            <x-lucide-rotate-ccw class="size-3.5 transition duration-300 group-hover:-rotate-45" />
            Очистити все
        </button>
    </div>
</div>
