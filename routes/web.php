<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\HomePage;
use App\Livewire\ProductDetail;
use App\Livewire\CartPage;
use App\Livewire\CheckoutPage;
use App\Livewire\SuccessPage;

Route::get('/checkout', CheckoutPage::class)->name('checkout');
Route::get('/success', SuccessPage::class)->name('success');
Route::get('/cart', CartPage::class)->name('cart');
Route::get('/products/{slug}', ProductDetail::class)->name('products.show');
Route::get('/', HomePage::class)->name('home');
