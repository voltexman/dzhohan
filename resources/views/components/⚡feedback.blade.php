<?php

use Livewire\Component;

new class extends Component {
    public string $name = '';

    public string $contact = '';

    public string $message = '';

    public function send()
    {
        sleep(3);
        session()->flash('feedback-sent');
    }
};
?>

@session('feedback-sent')
    <div>Повідомлення відправлено</div>
@else
    <div class="space-y-5">
        <div class="space-y-1.5">
            <x-form.label text="Ваше ім'я" />
            <x-form.input wire:model.trim="name" placeholder="Ім'я або нік" wire:loading.attr="disabled" wire:target="send" />
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
            @error('name')
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
