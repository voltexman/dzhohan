@props(['id' => uniqid('calendar-')])

<div x-data="calendarComponent()" x-modelable="value" x-init="value = @entangle($attributes->wire('model'))" wire:ignore class="relative w-full">
    <div class="relative">
        <input x-ref="calendarInput" type="text" :value="value" readonly
            class="peer py-3 px-4 ps-11 block w-full border-zinc-200 rounded-lg text-sm focus:border-zinc-500 focus:ring-zinc-500 cursor-pointer bg-white outline-none"
            placeholder="Оберіть дату">

        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
            <x-lucide-calendar class="size-4 text-zinc-400" />
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('calendarComponent', () => ({
            value: null,

            init() {
                const calendar = new Calendar(this.$refs.calendarInput, {
                    locale: 'uk',
                });

                calendar.init();
            }
        }));
    });
</script>
