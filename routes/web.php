<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/products', 'pages::products.list')->name('products');
Route::livewire('/products/{category}', 'pages::products.list')->name('products.category');
Route::livewire('/product/{product}', 'pages::products.show')->name('product.show');
Route::livewire('/gallery', 'pages::gallery')->name('gallery');
Route::livewire('/blog', 'pages::blog.list')->name('blog');
Route::livewire('/blog/{post}', 'pages::blog.show')->name('blog.show');
Route::livewire('/order', 'pages::products.order')->name('order');
Route::livewire('/contacts', 'pages::contacts')->name('contacts');
