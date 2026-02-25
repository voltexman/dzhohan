<?php

use function Laravel\Folio\name;
name('about');
?>

@section('header')
    <header class="relative h-96 w-full bg-cover bg-center bg-no-repeat"
        style="background-image: url('{{ Vite::asset('resources/images/header.png') }}')">

        {{-- затемнення --}}
        <div class="absolute inset-0 bg-black/60"></div>

        {{-- контент поверх --}}
        <div class="relative z-10 flex flex-col items-center justify-center h-full">
            <h1 class="text-zinc-200 text-4xl md:text-6xl font-bold text-center max-w-lg font-[Russo_One]">
                Про мене
            </h1>
        </div>
    </header>
@endsection

<x-layouts::app>
    <div>
        about page
    </div>
</x-layouts::app>
