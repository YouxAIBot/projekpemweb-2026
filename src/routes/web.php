<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\StorefrontController;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

Livewire::setUpdateRoute(fn ($handle) => Route::post(config('app.asset_prefix') . '/livewire/update', $handle));
Livewire::setScriptRoute(fn ($handle) => Route::get(config('app.asset_prefix') . '/livewire/livewire.js', $handle));

Route::get('/', [StorefrontController::class, 'home'])->name('home');
Route::get('/games', [StorefrontController::class, 'games'])->name('games');
Route::get('/leaderboard', [StorefrontController::class, 'leaderboard'])->middleware('auth')->name('leaderboard');
Route::get('/faq', [StorefrontController::class, 'faq'])->name('faq');
Route::get('/support', [StorefrontController::class, 'support'])->name('support');
Route::post('/support', [StorefrontController::class, 'submitSupport'])->name('support.submit');
Route::get('/transactions', [StorefrontController::class, 'transactions'])->name('transactions');
Route::get('/topup/{game:slug}', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/topup/{game:slug}', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/invoice/{invoice}', [InvoiceController::class, 'show'])->name('invoice.show');
Route::post('/invoice/{order}/simulate-success', [InvoiceController::class, 'simulateSuccess'])->name('invoice.simulate-success');
