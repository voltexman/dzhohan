@use(App\Enums\Order\DeliveryMethod)

<form class="lg:col-span-7 space-y-10" novalidate>

    <!-- Контактні дані -->
    <div>
        <h2 class="text-lg font-semibold mb-2.5 flex items-center gap-1.5">
            <x-lucide-user class="size-5" /> Контактні дані
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <x-form.group>
                <x-form.label>Електронна пошта</x-form.label>
                <x-form.input wire:model.trim.live.blur="form.email" placeholder="example@gmail.com" required />
                <x-form.checkbox wire:model="subscribe" class="mt-1.5" label="Отримувати статті, новини та пропозиції" />
            </x-form.group>
        </div>
    </div>

    <!-- Доставка -->
    <div x-data="{ delivery_method: @entangle('form.delivery_method') }">
        <h2 class="text-lg font-semibold mb-2.5 flex items-center gap-1.5">
            <x-lucide-truck class="size-5" /> Доставка
        </h2>

        <!-- Кнопки вибору способу доставки -->
        <div class="w-full lg:max-w-lg grid grid-cols-2 lg:grid-cols-4 gap-2.5">
            @foreach (DeliveryMethod::cases() as $method)
                <button type="button" @click="delivery_method = '{{ $method->value }}'"
                    :class="delivery_method === '{{ $method->value }}' ?
                        'border-orange-500/50 ring-1 ring-orange-500/50 bg-orange-50 text-orange-700' :
                        'border-zinc-200 bg-zinc-100 hover:border-zinc-300 text-zinc-600'"
                    class="relative flex flex-col items-center justify-center px-2.5 py-5 border rounded-md transition-all duration-300 group cursor-pointer">
                    <span class="text-sm font-semibold">{{ $method->getLabel() }}</span>
                    <div x-show="delivery_method === '{{ $method->value }}'" x-transition.scale
                        class="absolute top-1.5 right-1.5">
                        <x-lucide-check-circle-2 class="size-4 fill-orange-50 stroke-orange-600" />
                    </div>
                </button>
            @endforeach
        </div>

        <template
            x-if="delivery_method === '{{ DeliveryMethod::NovaPoshta }}' || delivery_method === '{{ DeliveryMethod::UkrPoshta }}'">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                <x-form.group>
                    <x-form.label>Ім'я</x-form.label>
                    <x-form.input wire:model.trim.live.blur="form.first_name" placeholder="Іван" required />
                </x-form.group>

                <x-form.group>
                    <x-form.label>Прізвище</x-form.label>
                    <x-form.input wire:model.trim.live.blur="form.last_name" placeholder="Іванов" required />
                </x-form.group>

                <x-form.group>
                    <x-form.label>Номер телефону</x-form.label>
                    <x-form.input wire:model.trim.live.blur="form.phone" x-mask="+999 (99) 999-99-99"
                        placeholder="+380 (63) 123-44-56" required />
                </x-form.group>

                <x-form.group>
                    <x-form.label>Місто</x-form.label>
                    <x-form.input wire:model.trim.live.blur="form.city" placeholder="Київ" required />
                </x-form.group>

                <x-form.group>
                    <x-form.label>Адреса або відділення</x-form.label>
                    <x-form.input wire:model.trim.live.blur="form.address" placeholder="Відділення/поштомат" required />
                </x-form.group>
            </div>
        </template>

        <template x-if="delivery_method === '{{ DeliveryMethod::Pickup }}'">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                <x-form.group>
                    <x-form.label>Ім'я</x-form.label>
                    <x-form.input wire:model.trim.live.blur="form.first_name" placeholder="Ім'я" />
                </x-form.group>

                <x-form.group>
                    <x-form.label>Номер телефону</x-form.label>
                    <x-form.input wire:model.trim.live.blur="form.phone" x-mask="+999 (99) 999-99-99"
                        placeholder="Номер телефону" />
                </x-form.group>
            </div>
        </template>
    </div>

    <!-- Коментар -->
    <div class="max-w-xl">
        <h2 class="text-lg font-semibold flex items-center gap-1.5">
            <x-lucide-message-square class="size-5" /> Коментар
            <span class="italic text-gray-500 text-sm font-normal">(за бажанням)</span>
        </h2>
        <x-form.textarea wire:model.trim="form.comment" rows="5" placeholder="Ваші побажання до замовлення..." />
    </div>
</form>
