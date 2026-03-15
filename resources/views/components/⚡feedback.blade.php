<?php

use Illuminate\Support\Facades\Notification;
use App\Notifications\FeedbackSent;
use Livewire\Attributes\Validate;
use App\Models\Feedback;
use Livewire\Component;

new class extends Component {
    #[
        Validate(
            'string|min:2',
            message: [
                'min' => 'Імʼя має містити мінімум 2 символи',
                'string' => 'Імʼя повинно бути текстом',
            ],
        ),
    ]
    public string $name = '';

    #[
        Validate(
            'string|min:5',
            message: [
                'min' => 'Контакт має містити мінімум 5 символів',
                'string' => 'Контакт повинен бути текстом',
            ],
        ),
    ]
    public string $contact = '';

    #[
        Validate(
            'required|string|min:5|max:1500',
            message: [
                'required' => 'Напишіть повідомлення',
                'min' => 'Занадто коротке повідомлення',
                'max' => 'Повідомлення має містити не більше ніж 1500 символім',
                'string' => 'Повідомлення повинно бути текстом',
            ],
        ),
    ]
    public string $message = '';

    public function send()
    {
        $validated = $this->validate();

        Notification::routes([
            'mail' => env('ADMIN_EMAIL'),
            'telegram' => env('TELEGRAM_CHAT_ID'),
        ])->notify(new FeedbackSent((object) $validated));

        Feedback::create($validated);

        $this->reset(['name', 'contact', 'message']);

        session()->flash('feedback-sent');
    }
};
?>

@session('feedback-sent')
    <div class="flex flex-col justify-center items-center min-h-50 py-5 lg:py-10" x-cloak>
        <x-lucide-send class="size-40 shrink-0 stroke-zinc-400 mb-10" stroke-width="1.2" />
        <div class="font-[Oswald] text-2xl text-gray-700">Повідомлення відправлено</div>
        <div class="text-sm text-gray-600 text-center mt-2.5 max-w-xs">
            Дякую! Найближчим часом я обов'язково перегляну ваше повідомлення!
        </div>
        <button type="button" wire:click="$refresh" wire:loading.attr="disabled"
            class="mt-5 text-gray-600 text-sm font-semibold hover:text-gray-900 transition-colors duration-300 disabled:opacity-50 cursor-pointer">
            Відправити ще листа?
            <x-lucide-loader-circle wire:loading wire:target="$refresh"
                class="size-4 inline-flex shrink-0 stroke-gray-600 animate-spin mb-0.5" />
        </button>
    </div>
@else
    <div class="space-y-5" x-cloak>
        <div class="space-y-1.5">
            <x-form.label text="Ваше ім'я" />
            <x-form.input wire:model.trim="name" placeholder="Ім'я або нік" wire:loading.attr="disabled"
                wire:target="send" />
            @error('name')
                <x-form.error>{{ $message }}</x-form.error>
            @enderror
        </div>

        <div class="space-y-1.5">
            <x-form.label text="Контакт" />
            <x-form.input wire:model.trim="contact" placeholder="Пошта або телефон" wire:loading.attr="disabled"
                wire:target="send" />
            @error('contact')
                <x-form.error>{{ $message }}</x-form.error>
            @enderror
        </div>

        <div class="space-y-1.5">
            <x-form.label text="Повідомлення" />
            <x-form.textarea wire:model.trim="message" rows="6" placeholder="Напишіть ваше повідомлення"
                wire:loading.attr="disabled" wire:target="send" />
            @error('message')
                <x-form.error>{{ $message }}</x-form.error>
            @enderror
        </div>

        <x-button color="dark" wire:click="send" wire:loading.attr="disabled" wire:target="send">
            <span wire:loading.remove wire:target="send">Відправити</span>
            <span wire:loading wire:target="send">Відправка</span>
            <x-lucide-send wire:loading.remove wire:target="send" class="size-5 shrink-0 ms-1.5" />
            <x-lucide-loader-circle wire:loading wire:target="send" class="size-5 shrink-0 ms-1.5 animate-spin" />
        </x-button>
    </div>
@endsession
