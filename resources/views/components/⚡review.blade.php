<?php

use Livewire\Component;
use App\Livewire\Forms\ReviewForm;

new class extends Component {
    public $product;

    public ReviewForm $review;

    public function send()
    {
        $validated = $this->review->validate();

        $this->product->reviews()->create($validated);

        $this->review->reset();

        session()->flash('review-sent');
    }
};
?>

<div x-data="{
    open: false,
    rating: @entangle('review.rating'),
    hoverRating: 0
}" class="bg-zinc-50 max-w-xl lg:mx-10 p-5 border-t border-b lg:border border-zinc-100 mt-10">
    @session('review-sent')
        <div class="flex flex-col justify-center items-center my-5">
            <x-lucide-award class="size-40 shrink-0 fill-orange-50 stroke-orange-500 mb-10" stroke-width="1.2" />
            <div class="font-[Oswald] text-xl text-gray-700">Відгук надіслано</div>
            <div class="text-sm text-gray-600 text-center mt-2.5 max-w-xs">
                Дякую! Найближчим часом я обов'язково перегляну ваш відгук!
            </div>
        </div>
    @else
        <button @click="open = !open" class="flex items-center justify-between w-full group cursor-pointer text-left">
            <div class="flex items-center gap-3">
                <div class="size-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 shrink-0">
                    <x-lucide-shopping-bag class="size-5 shrink-0" />
                </div>
                <div>
                    <h4 class="font-bold text-zinc-900">Ви покупець цього ножа?</h4>
                    <p class="text-xs text-zinc-500">Поділіться досвідом користування та оцініть якість</p>
                </div>
            </div>
            <x-lucide-chevron-down class="size-5 text-zinc-400 transition-transform duration-300" ::class="open ? 'rotate-180 text-orange-600' : ''" />
        </button>

        <div x-show="open" x-collapse x-cloak class="mt-5 pt-5 space-y-5 border-t border-zinc-200/60">
            <div class="space-y-2.5">
                <label class="text-sm font-semibold text-zinc-700">Ваша оцінка:</label>
                <div class="flex gap-1.5">
                    @foreach (range(1, 5) as $star)
                        <button type="button" @click="rating = {{ $star }}"
                            @mouseenter="hoverRating = {{ $star }}; rating = {{ $star }}"
                            class="cursor-pointer transition-all duration-200 transform hover:scale-125 focus:outline-none">
                            <x-lucide-star class="size-8 transition-colors duration-200" ::class="(hoverRating || rating) >= {{ $star }} ?
                                'fill-orange-500 stroke-orange-500' :
                                'fill-zinc-200 stroke-zinc-300'" />
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <x-form.input wire:model="review.name" placeholder="Ваше ім’я" />
                <x-form.input wire:model="review.contact" placeholder="Email або телефон" />
            </div>

            <x-form.textarea wire:model="review.text" rows="3"
                placeholder="Розкажіть про ніж: як тримає заточку, ергономіку..." />

            <x-button wire:click="send" size="md" wire:loading.attr="disabled" wire:target="send"
                class="w-full sm:w-auto">
                <x-lucide-award class="size-4 mr-2" />
                Надіслати відгук
            </x-button>
        </div>
    @endsession
</div>
