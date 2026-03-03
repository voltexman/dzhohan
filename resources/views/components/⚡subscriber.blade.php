<?php

use App\Models\Subscriber;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component {
    #[
        Validate(
            'required|email|unique:subscribers,email|max:100',
            message: [
                'required' => 'Вкажіть поштову адресу',
                'email' => 'Некоректний EMail',
                'unique' => 'Ви вже підписані',
                'max' => 'Занадто багато символів',
            ],
        ),
    ]
    public string $email;

    public function send()
    {
        Subscriber::create($this->validate());

        $this->reset('email');

        session()->flash('subscriber-success');
    }
};
?>

@session('subscriber-success')
    <div>success</div>
@else
    <div class="">
        <div class="relative w-full">
            <x-form.input color="dark" size="xl" wire:model.trim="email" class="w-full pr-38"
                placeholder="Вкажіть Email" />

            <x-button color="dark" size="md" wire:click="send" wire:loading.attr="disabled"
                class="inline-flex items-center absolute right-2.5 top-1/2 -translate-y-1/2">
                <span wire:loading.remove wire:target="send">Підписатись</span>
                <span wire:loading wire:target="send">Підписка</span>
                <x-lucide-send wire:loading.remove wire:target="send" class="size-4 shrink-0 ms-0.5" />
                <x-lucide-loader-circle wire:loading wire:target="send" class="size-4 shrink-0 ms-0.5 animate-spin" />
            </x-button>
        </div>

        @error('email')
            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
        @enderror
    </div>
@endsession
