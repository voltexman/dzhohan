<div class="lg:h-[calc(100vh-4rem)] lg:mt-4 lg:pr-8 flex flex-col justify-between overflow-hidden">
    <div
        class="flex-1 space-y-10 lg:pt-4 pr-1.5 overflow-y-auto overflow-x-hidden [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-stone-300/0 hover:[&::-webkit-scrollbar-thumb]:bg-stone-300/80">

        <!-- 1. СТАТУС -->
        <div class="grid grid-cols-3 gap-x-0.5 p-1.5 mt-2.5 lg:mt-0 bg-white rounded-md border border-zinc-200">
            @foreach (['all' => 'Всі', 'in_stock' => 'Наявні', 'sold' => 'Продані'] as $val => $label)
                <button type="button" wire:click="$set('status', '{{ $val }}')"
                    class="py-2.5 text-xs font-semibold tracking-wide rounded-md transition-all duration-500 cursor-pointer 
                        {{ $status === $val ? 'bg-neutral-900 text-white' : 'text-neutral-500 hover:bg-zinc-50 hover:text-neutral-700' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <!-- 2. БЮДЖЕТ -->
        <div class="space-y-5" x-data="{
            expanded: $persist(true).as('prices-expanded'),
            minL: @js((int) $minLimit),
            maxL: @js((int) $maxLimit),
            from: @entangle('price_from'),
            to: @entangle('price_to')
        }" wire:loading.class="animate-pulse pointer-events-none"
            wire:target="price_from, price_to" x-cloak>
            <div class="flex items-center justify-between w-full group outline-none">
                <div
                    class="flex items-center gap-1.5 text-sm font-extrabold uppercase text-neutral-600 font-[Oswald] tracking-wide">
                    <x-lucide-wallet class="size-4" />
                    Бюджет
                </div>
                <button @click="expanded = !expanded" type="button" class="">
                    <x-lucide-chevron-down class="size-4 text-neutral-500 transition-transform duration-300"
                        x-bind:class="expanded ? 'rotate-180' : ''" />
                </button>
            </div>

            <!-- 2. КОНТЕНТ АКОРДЕОНА (Ціна, Скидання, Повзунки) -->
            <div x-show="expanded" class="space-y-5" x-collapse>
                <!-- Ряд з ціною та кнопкою скидання -->
                <div class="flex justify-between items-end">
                    <div class="text-2xl font-light tracking-tighter text-stone-950">
                        <span x-text="Number(from).toLocaleString()"></span> —
                        <span x-text="Number(to).toLocaleString()"></span>
                        <span class="text-xs align-top ml-1 font-bold text-zinc-400 uppercase">грн</span>
                    </div>

                    @if ($price_from !== $minLimit || $price_to !== $maxLimit)
                        <button type="button" wire:click="resetPrice"
                            class="p-2 rounded-full bg-white text-stone-500 hover:text-stone-800 hover:bg-stone-100 transition-all border border-stone-200 cursor-pointer">
                            <x-lucide-rotate-ccw class="size-3.5" />
                        </button>
                    @endif
                </div>

                <!-- Повзунки -->
                <div class="relative py-1.5">
                    <div class="relative h-2 w-full rounded-full bg-stone-200">
                        <div class="absolute h-full rounded-full bg-stone-950"
                            :style="'left: ' + (((from - minL) / (maxL - minL)) * 100) + '%; right: ' + (100 - (
                                (
                                    to -
                                    minL) / (
                                    maxL - minL)) * 100) + '%'">
                        </div>

                        <input type="range" :min="minL" :max="maxL" x-model.number="from"
                            @change="$wire.set('price_from', from)" @input="if(from > to) from = to"
                            class="pointer-events-none absolute -top-3 z-30 h-7 w-full appearance-none bg-transparent [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:size-6 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:border-[1.5px] [&::-webkit-slider-thumb]:border-stone-900 [&::-webkit-slider-thumb]:shadow-xl">

                        <input type="range" :min="minL" :max="maxL" x-model.number="to"
                            @change="$wire.set('price_to', to)" @input="if(to < from) to = from"
                            class="pointer-events-none absolute -top-3 z-30 h-7 w-full appearance-none bg-transparent [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:size-6 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:border-[1.5px] [&::-webkit-slider-thumb]:border-stone-900 [&::-webkit-slider-thumb]:shadow-xl">
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. КОЛЕКЦІЇ -->
        {{-- 1. Перевіряємо просто змінну $collection --}}
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
        <div class="space-y-5" x-data="{ expanded: $persist(true).as('steel-expanded') }" wire:loading.class="animate-pulse pointer-events-none"
            wire:target="steels" x-cloak>
            <div class="flex items-center justify-between w-full group outline-none">
                <div
                    class="flex items-center gap-1.5 text-sm font-extrabold uppercase text-neutral-600 font-[Oswald] tracking-wide">
                    <x-lucide-layers class="size-4" />
                    Марка сталі
                </div>
                @if (count($steels))
                    <button wire:click="$set('steels', [])" type="button"
                        class="flex items-center text-xs ms-auto me-1.5 text-neutral-400 hover:text-neutral-500 font-medium transition-colors duration-250 cursor-pointer">
                        <x-lucide-x-circle class="size-4 border border-neutral-200 rounded-full flex-none me-0.5" />
                        очистити
                    </button>
                @endif
                <button @click="expanded = !expanded" type="button" class="">
                    <x-lucide-chevron-down class="size-4 text-neutral-500 transition-transform duration-300"
                        x-bind:class="expanded ? 'rotate-180' : ''" />
                </button>
            </div>

            <div x-show="expanded" x-collapse>
                <div class="flex flex-wrap gap-y-1.5 gap-x-2.5">
                    @foreach (App\Enums\SteelType::cases() as $steel)
                        <label
                            class="group flex items-center gap-x-1.5 cursor-pointer py-1 has-checked:bg-stone-50 rounded-lg transition-all duration-300">
                            <div class="relative flex items-center justify-center">
                                <input type="checkbox" value="{{ $steel->value }}" wire:model.live="steels"
                                    class="peer appearance-none size-5.5 border border-stone-300 rounded-sm checked:bg-stone-900 checked:border-stone-900 transition-all duration-300 cursor-pointer">

                                <x-lucide-check
                                    class="absolute size-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"
                                    stroke-width="4" />
                            </div>

                            <span
                                class="text-sm font-semibold capitalize transition-all duration-300 text-neutral-500 group-hover:text-stone-900 group-has-checked:text-black tracking-tight">
                                {{ $steel->value }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- ФІЛЬТР: МАТЕРІАЛ РУКІВ'Я -->
        <div class="space-y-5" x-data="{ expanded: $persist(true).as('handle-expanded') }" wire:loading.class="animate-pulse pointer-events-none"
            wire:target="handle_materials" x-cloak>
            <div class="flex items-center justify-between w-full group outline-none">
                <div
                    class="flex items-center gap-1.5 text-sm font-extrabold uppercase text-neutral-600 font-[Oswald] tracking-wide">
                    <x-lucide-layers class="size-4" />
                    Матеріал руків'я
                </div>
                @if (count($handle_materials))
                    <button wire:click="$set('handle_materials', [])" type="button"
                        class="flex items-center text-xs ms-auto me-1.5 text-neutral-400 hover:text-neutral-500 font-medium transition-colors duration-250 cursor-pointer">
                        <x-lucide-x-circle class="size-4 border border-neutral-200 rounded-full flex-none me-0.5" />
                        очистити
                    </button>
                @endif
                <button @click="expanded = !expanded" type="button" class="">
                    <x-lucide-chevron-down class="size-4 text-neutral-500 transition-transform duration-300"
                        x-bind:class="expanded ? 'rotate-180' : ''" />
                </button>
            </div>

            <div x-show="expanded" x-collapse>
                <div class="flex flex-wrap gap-y-1.5 gap-x-2.5">
                    @foreach (App\Enums\HandleMaterial::cases() as $material)
                        <label
                            class="group flex items-center gap-x-1.5 cursor-pointer py-1 transition-all duration-300">
                            <div class="relative flex items-center justify-center">
                                <input type="checkbox" value="{{ $material->value }}"
                                    wire:model.live="handle_materials"
                                    class="peer appearance-none size-5.5 border border-neutral-300 rounded-sm checked:bg-neutral-900 checked:border-neutral-900 transition-all duration-300 cursor-pointer">

                                <x-lucide-check
                                    class="absolute size-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"
                                    stroke-width="4" />
                            </div>

                            <span
                                class="text-sm font-semibold capitalize transition-all duration-300 text-neutral-500 group-hover:text-neutral-900 group-has-checked:text-black tracking-tight">
                                {{ $material->value }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- 4. ПРОФІЛЬ КЛИНКА -->
        <div class="space-y-5" x-data="{ expanded: $persist(true).as('blade-expanded') }" wire:loading.class="animate-pulse pointer-events-none"
            wire:target="blade_shapes" x-cloak>
            <div class="flex items-center justify-between w-full group outline-none">
                <div
                    class="flex items-center gap-1.5 text-sm font-extrabold uppercase text-neutral-600 font-[Oswald] tracking-wide">
                    <x-lucide-layers class="size-4" />
                    Профіль клинка
                </div>
                @if (count($blade_shapes))
                    <button wire:click="$set('blade_shapes', [])" type="button"
                        class="flex items-center text-xs ms-auto me-1.5 text-neutral-400 hover:text-neutral-500 font-medium transition-colors duration-250 cursor-pointer">
                        <x-lucide-x-circle class="size-4 border border-neutral-200 rounded-full flex-none me-0.5" />
                        очистити
                    </button>
                @endif
                <button @click="expanded = !expanded" type="button" class="">
                    <x-lucide-chevron-down class="size-4 text-neutral-500 transition-transform duration-300"
                        x-bind:class="expanded ? 'rotate-180' : ''" />
                </button>
            </div>

            <div x-show="expanded" x-collapse>
                <div class="flex flex-wrap gap-y-1.5 gap-x-2.5">
                    @foreach (App\Enums\BladeShape::cases() as $shape)
                        <label
                            class="group flex items-center gap-x-1.5 cursor-pointer py-1 transition-all duration-300">
                            <div class="relative flex items-center justify-center">
                                <input type="checkbox" value="{{ $shape->value }}" wire:model.live="blade_shapes"
                                    class="peer appearance-none size-5.5 border border-neutral-300 rounded-sm checked:bg-neutral-900 checked:border-neutral-900 transition-all duration-300 cursor-pointer">

                                <x-lucide-check
                                    class="absolute size-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"
                                    stroke-width="4" />
                            </div>

                            <span
                                class="text-sm font-semibold capitalize transition-all duration-300 text-neutral-500 group-hover:text-neutral-900 group-has-checked:text-black tracking-tight">
                                {{ str_replace('_', ' ', $shape->value) }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- 4. ОЧИСТИТИ ВСЕ -->
    <div class="shrink-0 py-2.5 lg:py-5">
        <button wire:click="resetFilters"
            class="group w-fit mx-auto h-full flex items-center justify-center gap-1.5 text-xs text-red-500 hover:text-red-500 uppercase font-semibold cursor-pointer">
            <x-lucide-rotate-ccw class="size-3.5 transition duration-300 group-hover:-rotate-45" />
            Очистити все
        </button>
    </div>
</div>
