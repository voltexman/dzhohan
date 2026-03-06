<?php

use Illuminate\Support\Facades\Notification;
use App\Notifications\CallbackNotification;
use Livewire\Component;

new class extends Component {
    public $phone = '';

    public $wait = false;

    public $time_from = null;

    public $time_to = null;

    public function boot()
    {
        $this->reset(['wait', 'phone', 'time_from', 'time_to']);
    }

    protected function rules(): array
    {
        $rules = [
            'phone' => ['required', 'string', 'min:10'],
        ];

        if ($this->wait) {
            $rules['time_from'] = ['required', 'date_format:H:i', 'after_or_equal:08:00'];
            $rules['time_to'] = ['required', 'date_format:H:i', 'after:time_from', 'before_or_equal:22:00'];
        }

        return $rules;
    }

    protected function messages(): array
    {
        return [
            // Телефон
            'phone.required' => 'Напишіть номер телефону',
            'phone.string' => 'Номер телефону має бути рядком',
            'phone.min' => 'Номер телефону має містити мінімум 10 символів',

            // Час початку
            'time_from.required' => 'Вкажіть з котрої години',
            'time_from.date_format' => 'Час початку має бути у форматі ГГ:ХХ',
            'time_from.after_or_equal' => 'Не раніше 08:00',

            // Час закінчення
            'time_to.required' => 'Вкажіть до котрої години',
            'time_to.date_format' => 'Час завершення має бути у форматі ГГ:ХХ',
            'time_to.after' => 'Час завершення має бути пізніше за час початку',
            'time_to.before_or_equal' => 'Не пізніше 23:00',
        ];
    }

    public function callback()
    {
        $this->validate();

        Notification::route('telegram', env('TELEGRAM_CHAT_ID'))
            //
            ->notify(new CallbackNotification($this->phone, $this->wait, $this->time_from, $this->time_to));

        session()->flash('callback-sent');
    }
};
?>

@session('callback-sent')
    <div class="flex flex-col justify-start">
        <div class="text-sm text-orange-500 font-medium max-w-sm">
            @if (!$wait)
                Очікуйте на дзвінок найближчим часом за номером
                <strong class="font-bold text-orange-600">{{ $phone }}</strong>
            @else
                Очікуйте на дзвінок за номером
                <strong class="font-bold text-orange-600">{{ $phone }}</strong>
                з
                <strong class="font-bold text-orange-600">{{ $time_from }}</strong>
                до
                <strong class="font-bold text-orange-600">{{ $time_to }}</strong>
            @endif
        </div>
        <button type="button" wire:click="$refresh" wire:loading.attr="disabled"
            class="w-fit mt-5 text-gray-600 text-sm font-semibold hover:text-gray-900 transition-colors duration-300 disabled:opacity-50 cursor-pointer">
            Замовити ще дзвінок?
            <x-lucide-loader-circle wire:loading wire:target="$refresh"
                class="size-4 inline-flex shrink-0 stroke-gray-600 animate-spin mb-0.5" />
        </button>
    </div>
@else
    <div>
        <div class="max-w-sm">
            <x-form.input icon="phone-incoming" wire:model="phone" x-mask="+999 (99) 999-99-99"
                placeholder="+380 (63) 123-45-67" class="">
                <x-slot:button>
                    <button type="button"" wire:click="callback" wire:loading.attr="disabled" wire:target="callback"
                        class="absolute z-40 size-8 top-1/2 right-2.5 -translate-y-1/2 cursor-pointer text-zinc-600 hover:text-zinc-700 transition-colors duration-300">
                        <x-lucide-bell-ring wire:loading.remove wire:target="callback" class="size-5 shrink-0" />
                        <x-lucide-loader-circle wire:loading wire:target="callback" class="size-5 shrink-0 animate-spin" />
                    </button>
                </x-slot:button>
            </x-form.input>
        </div>

        <div class="inline-flex rounded-md -space-x-px mt-5" role="group">
            <button type="button" wire:click="wait = false"
                class="inline-flex items-center justify-center rounded-l-md border border-zinc-200 px-4 py-2.5 text-sm font-medium transition-colors cursor-pointer focus:outline-none focus:ring-2 focus:ring-neutral-300"
                :class="!$wire.wait ? 'bg-neutral-200/65 text-gray-900' : 'bg-neutral-100 text-gray-500 hover:bg-neutral-200/60'">
                Зателефонувати зараз
            </button>

            <button type="button" wire:click="wait = true"
                class="inline-flex items-center justify-center rounded-r-md border border-zinc-200 px-4 py-2.5 text-sm font-medium transition-colors cursor-pointer focus:outline-none focus:ring-2 focus:ring-neutral-300"
                :class="$wire.wait ? 'bg-neutral-200/65 text-gray-900' :
                    'bg-neutral-100 text-gray-500 hover:bg-neutral-200/60'">
                <span>Потім</span>
                <x-lucide-clock class="size-4 shrink-0 ms-2" />
            </button>
        </div>

        <div wire:show="wait" wire:transition class="max-w-xs grid grid-cols-2 gap-y-2.5 gap-x-5 mt-2.5" x-cloak>
            <div class="text-xs col-span-full">Оберіть зручний для вас час для розмови</div>
            <!-- Start -->
            <div class="space-y-1.5">
                <label for="start-time" class="text-sm font-medium text-zinc-600">З</label>

                <div class="relative">
                    <x-lucide-clock
                        class="absolute right-3 top-1/2 -translate-y-1/2 size-4 stroke-zinc-500 pointer-events-none" />

                    <input type="time" id="start-time" wire:model="time_from" min="09:00" max="18:00" required
                        class="no-native-time-icon w-full bg-zinc-50 rounded-md border border-stone-200 px-4 py-3.5 text-sm font-medium text-zinc-700 focusborder-zinc-900 focus:ring-4 focus:ring-stone-200/50 transition-colors outline-none" />
                </div>
                @error('time_from')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- End -->
            <div class="space-y-1.5">
                <label for="end-time" class="text-sm font-medium text-zinc-600">До</label>

                <div class="relative">
                    <x-lucide-clock
                        class="absolute right-3 top-1/2 -translate-y-1/2 size-4 stroke-zinc-500 pointer-events-none" />

                    <input type="time" id="end-time" wire:model="time_to" min="08:00" max="22:00" required
                        class="no-native-time-icon w-full bg-zinc-50 rounded-md border border-zinc-200 px-4 py-3.5 text-sm font-medium text-zinc-700 focus:border-zinc-300 focus:ring-4 focus:ring-stone-200/50 transition-colors outline-none" />
                </div>
                @error('time_to')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
@endsession
