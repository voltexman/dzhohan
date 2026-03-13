<?php

use Illuminate\Support\Facades\Route;

/*

|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Загальний список усіх товарів
Route::livewire('/products', 'pages::products.list')
    ->name('products');

// 2. Список товарів конкретної колекції (напр. /products/outdoor)
// Важливо: параметр {collection} має збігатися з ключем у вашому Enum url()
Route::livewire('/products/{collection}', 'pages::products.list')
    ->name('products.collection');

// 3. Сторінка конкретного товару всередині колекції (напр. /products/outdoor/super-knife)
// Використовуємо {product:slug} для автоматичного пошуку моделі за слагом
Route::livewire('/products/{collection}/{product:slug}', 'pages::products.show')
    ->name('product.show');

// Інші сторінки сайту
Route::livewire('/gallery', 'pages::gallery')->name('gallery');
Route::livewire('/blog', 'pages::blog.list')->name('blog');
Route::livewire('/blog/{post}', 'pages::blog.show')->name('blog.show');
Route::livewire('/order', 'pages::order')->name('order');
Route::livewire('/checkout', 'pages::checkout')->name('checkout');
Route::livewire('/contacts', 'pages::contacts')->name('contacts');
