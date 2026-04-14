<?php

use App\Models\Post;
use Livewire\Component;

new class extends Component {
    public Post $post;

    public function mount($post)
    {
        $this->post = $post;
    }
};
?>

<x-slot name="title">
    Каталог ножів ручної роботи — купити авторський ніж
</x-slot>
<x-slot name="description">
    Каталог ножів ручної роботи: мисливські, кухонні та універсальні ножі. Висока якість матеріалів, ручне виготовлення
    та доставка по Україні.
</x-slot>

@section('header')
    <x-header class="h-[75vh]!" :image="Vite::asset('resources/images/header.png')">
        <x-slot:title class="text-xl! lg:text-3xl! -mt-20 lg:-mt-25!">
            {{ $post->name }}
        </x-slot:title>

        <x-slot:description>
            Lorem ipsum dolor, sit amet consectetur adipisicing elit. Animi, laboriosam vel praesentium maxime quas
            reiciendis dolores ipsum nam assumenda.
        </x-slot:description>
    </x-header>
@endsection

<section class="px-5 py-20">
    <div class="max-w-5xl mx-auto">
        <img src="{{ Vite::asset('resources/images/header.png') }}"
            class="w-full h-auto object-cover -mt-50 lg:-mt-80 relative z-10 shadow-lg rounded-md"
            alt="{{ $post->name }}" />

        <div class="flex gap-2.5 mt-10">
            <div></div>
            <div class="text-sm font-medium text-zinc-500">
                <x-lucide-calendar class="size-3.5 inline-flex shrink-0 mb-0.5" />
                {{ $post->created_at->format('d.m.Y') }}
            </div>
        </div>

        <div class="prose prose-lg mt-10">
            {{ $post->text }}
        </div>

        {{-- <div class="flex gap-1.5 mt-10">
            @foreach ($post->tags as $tag)
                <span
                    class="text-xs px-1.5 py-0.5 bg-neutral-200 rounded-md font-medium border border-neutral-100 text-neutral-600">
                    <x-lucide-tag class="size-3 inline-flex" />
                    {{ $tag->name }}
                </span>
            @endforeach
        </div> --}}

        <div class="max-w-xl mt-10 scroll-mt-20 lg:scroll-mt-8" id="comments-section">
            <livewire:comments :model="$post" />
        </div>
</section>
