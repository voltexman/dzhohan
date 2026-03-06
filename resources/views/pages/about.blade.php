<?php

use function Laravel\Folio\name;
name('about');
?>

@section('header')
    <x-header :image="Vite::asset('resources/images/header.png')">
        <x-slot:title>
            Про мене
        </x-slot:title>
    </x-header>
@endsection

<x-layouts::app>
    <section class="max-w-5xl mx-auto px-6 py-10 lg:py-20 lg:px-0">
        about page
    </section>
</x-layouts::app>
