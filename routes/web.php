<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/products', 'pages::products.list')->name('products');
Route::livewire('/products/{collection}', 'pages::products.list')->name('products.collection');
Route::livewire('/products/{collection}/{product:slug}', 'pages::products.show')->name('product.show');

Route::livewire('/gallery', 'pages::gallery')->name('gallery');
Route::livewire('/blog', 'pages::blog.list')->name('blog');
Route::livewire('/blog/{post}', 'pages::blog.show')->name('blog.show');
Route::livewire('/order', 'pages::order')->name('order');
Route::livewire('/checkout', 'pages::checkout')->name('checkout');
Route::livewire('/contacts', 'pages::contacts')->name('contacts');
