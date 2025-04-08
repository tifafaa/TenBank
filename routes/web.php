<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');

Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

// USER TRANSAKSI
Route::post('/wallet/deposit', [WalletController::class, 'deposit'])->name('wallet.deposit');
Route::post('/wallet/withdraw', [WalletController::class, 'withdraw'])->name('wallet.withdraw');
Route::post('/wallet/transfer', [WalletController::class, 'transfer'])->name('wallet.transfer');

// ADMIN
Route::get('/users/create', [HomeController::class, 'create'])->name('admin.create');
Route::post('/users', [HomeController::class, 'store'])->name('admin.store');
Route::get('/users/{id}/edit', [HomeController::class, 'edit'])->name('admin.edit');
Route::put('/users/{id}', [HomeController::class, 'update'])->name('admin.update');
Route::delete('/users/{id}', [HomeController::class, 'destroy'])->name('admin.destroy');

// BANK
Route::put('/transaction/confirm/{id}', [WalletController::class, 'confirmTransaction'])->name('confirm.transaction');
Route::put('/transaction/reject/{id}', [WalletController::class, 'rejectTransaction'])->name('reject.transaction');

Route::get('/wallet/transaction/{user_id}', [WalletController::class, 'transaction'])->name('bank.transaction');
Route::post('/wallet/transaction/{user_id}', [WalletController::class, 'processTransaction'])->name('transaction.process');
