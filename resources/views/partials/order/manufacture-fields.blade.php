@use(App\Enums\ProductCategory)
@use(App\Enums\SteelType)
@use(App\Enums\BladeShape)
@use(App\Enums\BladeGrind)
@use(App\Enums\BladeFinish)
@use(App\Enums\HandleMaterial)
@use(App\Enums\SheathType)


<div x-data="{
    sheath: 'none'
}" class="space-y-10 border-b border-zinc-200 pb-10 hidden">

    {{-- Параметри ножа --}}
    <div class="space-y-5">
        <h2 class="text-xl font-semibold font-[SN_Pro] mb-2.5 flex items-center gap-1.5">Параметри ножа</h2>
        <div class="grid md:grid-cols-2 gap-4">

            {{-- Марка сталі --}}
            <x-form.group>
                <x-form.label>Марка сталі
                    <x-popover>
                        Це «серце» ножа. Від вибору сталі залежить, як довго ніж триматиме заточку, наскільки він буде
                        міцним та чи буде іржавіти. Для кухні краще нержавійка, для полювання — зносостійкі порошкові
                        сталі.
                    </x-popover>
                </x-form.label>
                <x-form.select wire:model="custom_options.blade.steel" name="steel_type">
                    @foreach (SteelType::cases() as $steel)
                        <x-form.select.option value="{{ $steel->label() }}">
                            {{ $steel->label() }}
                        </x-form.select.option>
                    @endforeach
                </x-form.select>
            </x-form.group>

            {{-- Профіль клинка --}}
            <x-form.group>
                <x-form.label>Профіль клинка
                    <x-popover>
                        Зовнішня форма ножа. Впливає на зручність при різних роботах: проколюванні, оббілуванні чи
                        тонкому нарізанні продуктів. Обирайте класичний Drop Point для універсальності або Tanto для
                        грубих робіт.
                    </x-popover>
                </x-form.label>
                <x-form.select wire:model="custom_options.blade.shape" name="blade_shape">
                    @foreach (BladeShape::cases() as $shape)
                        <x-form.select.option value="{{ $shape->label() }}">
                            {{ $shape->label() }}
                        </x-form.select.option>
                    @endforeach
                </x-form.select>
            </x-form.group>

            {{-- Тип спусків --}}
            <x-form.group>
                <x-form.label>Тип спусків
                    <x-popover>
                        Те, як ніж звужується до леза. Прямі спуски — для ідеального різу, лінза — для надзвичайної
                        міцності (сокири, важкі ножі), сканді — найкращі для роботи з деревом та виживання.
                    </x-popover>
                </x-form.label>
                <x-form.select wire:model="custom_options.blade.grind" name="blade_grind">
                    @foreach (BladeGrind::cases() as $grind)
                        <x-form.select.option value="{{ $grind->label() }}">
                            {{ $grind->label() }}
                        </x-form.select.option>
                    @endforeach
                </x-form.select>
            </x-form.group>

            {{-- Матуріал руків'я --}}
            <x-form.group>
                <x-form.label>Матеріал руків'я
                    <x-popover>
                        Обробка поверхні металу. Сатин — класичні штрихи, виглядає дорого. Стоунвош (Stonewash) — матове
                        покриття, на якому майже не видно подряпин від використання. Дзеркало — максимальний блиск.
                    </x-popover>
                </x-form.label>
                <x-form.select wire:model="custom_options.blade.finish" name="blade_finish">
                    @foreach (BladeFinish::cases() as $finish)
                        <x-form.select.option value="{{ $finish->label() }}">
                            {{ $finish->label() }}
                        </x-form.select.option>
                    @endforeach
                </x-form.select>
            </x-form.group>

            {{-- Довжина клинка --}}
            <x-form.group>
                <x-form.label>Довжина клинка (мм)</x-form.label>
                <x-form.input type="number" wire:model="custom_options.blade.length" name="blade_length" />
            </x-form.group>

            {{-- Товщина --}}
            <x-form.group>
                <x-form.label>Товщина клинка (мм)</x-form.label>
                <x-form.input type="number" step="0.1" wire:model="custom_options.blade.thickness"
                    name="blade_thickness" />
            </x-form.group>
        </div>
    </div>

    {{-- Руків'я --}}
    <div class="space-y-5">
        <h2 class="text-lg font-semibold font-[SN_Pro] mb-2.5 flex items-center gap-1.5">Руків'я</h2>
        <div class="grid md:grid-cols-2 gap-5">
            <x-form.group>
                <x-form.label>Матеріал руків'я
                    <x-popover>
                        Те, що забезпечує комфорт у руці. Мікарта та G10 не бояться вологи та не ковзають. Дерево — це
                        класика, приємна на дотик та тепла взимку.
                    </x-popover>
                </x-form.label>
                <x-form.select wire:model="custom_options.handle.material" name="handle_material">
                    @foreach (HandleMaterial::cases() as $material)
                        <x-form.select.option value="{{ $material->label() }}">
                            {{ $material->label() }}
                        </x-form.select.option>
                    @endforeach
                </x-form.select>
            </x-form.group>

            <x-form.group>
                <x-form.label>Колір / побажання</x-form.label>
                <x-form.input type="text" wire:model.trim="custom_options.handle.color" name="handle_color" />
            </x-form.group>
        </div>
    </div>

    {{-- Піхви --}}
    <div class="space-y-5">
        <h2 class="text-lg font-semibold font-[SN_Pro] mb-2.5 flex items-center gap-1.5">Піхви</h2>
        <div class="grid md:grid-cols-2 gap-5">
            <x-form.group>
                <x-form.label>Тип піхв
                    <x-popover>
                        Захист ножа та зручність носіння. Кайдекс (пластик) надійно фіксує ніж «до кліку» і легко
                        миється. Шкіра — традиційний варіант, який з часом стає тільки кращим.
                    </x-popover>
                </x-form.label>
                <x-form.select wire:model="custom_options.sheath.type" name="sheath_type" x-model="sheath">
                    @foreach (SheathType::cases() as $sheath)
                        <x-form.select.option value="{{ $sheath->label() }}">
                            {{ $sheath->label() }}
                        </x-form.select.option>
                    @endforeach
                </x-form.select>
            </x-form.group>

            <div x-show="sheath !== 'none'" x-transition>
                <x-form.label>Спосіб носіння</x-form.label>
                <x-form.select wire:model="custom_options.sheath.carry" name="sheath_carry">
                    <x-form.select.option value="belt">На поясі</x-form.select.option>
                    <x-form.select.option value="horizontal">Горизонтально</x-form.select.option>
                    <x-form.select.option value="vertical">Вертикально</x-form.select.option>
                    <x-form.select.option value="molle">MOLLE</x-form.select.option>
                </x-form.select>
            </div>
        </div>
    </div>
</div>
