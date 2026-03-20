@use('App\Enums\ProductCategory')
@use('App\Enums\HandleMaterial')
@use('App\Enums\CurrencyType')
@use('App\Enums\BladeGrind')
@use('App\Enums\BladeShape')
@use('App\Enums\SteelType')

<div class=" h-[calc(100vh-120px)] lg:h-[calc(100vh-4rem)] lg:mt-4 lg:pr-8 flex flex-col justify-between">
    <x-scrollbar class="space-y-10 pt-4">
        <!-- 1. СТАТУС -->
        <x-filter.tabs>
            <x-filter.tabs.item model="status" value="all" :current="$status">Всі</x-filter.tabs.item>
            <x-filter.tabs.item model="status" value="in_stock" :current="$status">Наявні</x-filter.tabs.item>
            <x-filter.tabs.item model="status" value="sold" :current="$status">Продані</x-filter.tabs.item>
        </x-filter.tabs>

        <!-- 2. БЮДЖЕТ -->
        <x-filter.group title="Бюджет" icon="wallet" model="price_from, price_to" persist="filter-price">
            <div class="space-y-5">
                {{-- <x-filter.tags>
                    @foreach (\App\Enums\CurrencyType::cases() as $type)
                        <x-filter.tags.item :label="$type->getLabel()" :value="$type->value" model="currency" />
                    @endforeach
                </x-filter.tags> --}}

                <x-filter.range :min="$minLimit" :max="$maxLimit" from-model="price_from" to-model="price_to" />
            </div>
        </x-filter.group>

        {{-- Для довжини клинка --}}
        <x-filter.group title="Довжина клинка" icon="ruler" model="blade_length_from, blade_length_to"
            persist="filter-blade-lenght">
            <div class="space-y-2.5">
                <span class="text-xs font-medium tracking-wide text-zinc-400">Розмір (мм)</span>
                <div class="flex items-center gap-2.5">
                    <x-filter.number model="blade_length_from" :min="$minBladeLen" :max="$maxBladeLen" :step="1" />
                    <div class="text-zinc-300 text-xs">—</div>
                    <x-filter.number model="blade_length_to" :min="$minBladeLen" :max="$maxBladeLen" :step="1" />
                </div>
            </div>
        </x-filter.group>

        {{-- Для товщини обуху --}}
        <x-filter.group title="Товщина обуху" icon="ruler" model="blade_thickness_from, blade_thickness_to"
            persist="filter-thickess">
            <div class="space-y-2.5">
                <span class="text-xs font-medium tracking-wide text-zinc-400">Товщина (мм)</span>
                <div class="flex items-center gap-2.5">
                    <x-filter.number model="blade_thickness_from" :min="$minThickness" :max="$maxThickness"
                        step="0.1" />
                    <div class="text-zinc-300 text-xs">—</div>
                    <x-filter.number model="blade_thickness_to" :min="$minThickness" :max="$maxThickness" step="0.1" />
                </div>
            </div>
        </x-filter.group>

        <!-- 3. КОЛЕКЦІЇ -->
        @if (!$collection)
            <x-filter.group title="Колекції" icon="layers" model="collections" persist="filter-collections">

                <div class="flex flex-wrap gap-2.5">
                    @foreach (ProductCategory::cases() as $categoryCase)
                        <x-filter.badge :value="$categoryCase->value" :label="$categoryCase->getLabel()" model="collections" :active="in_array($categoryCase->value, $collections)" />
                    @endforeach
                </div>
            </x-filter.group>
        @endif

        <!-- ФІЛЬТР: МАРКА СТАЛІ -->
        <x-filter.group title="Марка сталі" icon="anvil" model="steels" persist="filter-steel">
            <div class="flex flex-wrap gap-1.5">
                @foreach (SteelType::cases() as $steel)
                    <x-filter.checkbox :value="$steel->value" :label="$steel->getLabel()" model="steels" />
                @endforeach
            </div>
        </x-filter.group>

        <!-- ФІЛЬТР: МАТЕРІАЛ РУКІВ'Я -->
        <x-filter.group title="Матеріал руків`я" icon="anvil" model="handle_materials" persist="handle_materials">
            <div class="flex flex-wrap gap-1.5">
                @foreach (HandleMaterial::cases() as $material)
                    <x-filter.checkbox :value="$material->value" :label="$material->getLabel()" model="handle_materials" />
                @endforeach
            </div>
        </x-filter.group>

        <!-- 4. ПРОФІЛЬ КЛИНКА -->
        <x-filter.group title="Профіль клинка" icon="spline" model="blade_shapes" persist="blade_shapes">
            <div class="flex flex-wrap gap-1.5">
                @foreach (BladeShape::cases() as $shape)
                    <x-filter.checkbox :value="$shape->value" :label="$shape->getLabel()" model="blade_shapes" />
                @endforeach
            </div>
        </x-filter.group>

        {{-- 5. ТИПИ СПУСКІВ --}}
        <x-filter.group title="Типи спусків" icon="triangle" model="blade_grinds" persist="blade_grinds">
            <div class="flex flex-wrap gap-1.5">
                @foreach (BladeGrind::cases() as $grind)
                    <x-filter.checkbox :value="$grind->value" :label="$grind->getLabel()" model="blade_grinds" />
                @endforeach
            </div>
        </x-filter.group>
    </x-scrollbar>
</div>
