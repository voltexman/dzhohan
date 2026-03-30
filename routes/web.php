<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/products/knife', 'pages::products.list')->name('knives');
Route::livewire('/products/knife/{collection}', 'pages::products.list')->name('knives.collection');
Route::livewire('/products/knife/{collection}/{product:slug}', 'pages::products.show')->name('knife.show');

Route::livewire('/products/material', 'pages::products.materials')->name('materials');
Route::livewire('/products/material/{product:slug}', 'pages::products.show')->name('material.show');

Route::livewire('/blog', 'pages::blog.list')->name('blog');
Route::livewire('/blog/{post:slug}', 'pages::blog.show')->name('blog.show');

Route::livewire('/gallery', 'pages::gallery')->name('gallery');

Route::livewire('/order', 'pages::order')->name('order');
Route::livewire('/checkout', 'pages::checkout')->name('checkout');
Route::livewire('/contacts', 'pages::contacts')->name('contacts');
