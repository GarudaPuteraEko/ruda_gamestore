<?php

use App\Http\Controllers\Admin\AdminGameController as AdminAdminGameController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\AdminTransactionController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\UserMiddleware;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return redirect()->route('user.games.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// ROUTE UNTUK SEMUA YANG LOGIN
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Transaksi user
    Route::post('/games/{id}/buy', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

    // Main game (admin atau user yang beli)
    Route::get('/games/{id}/play', [GameController::class, 'play'])->name('games.play');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{game}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{game}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
});

// ADMIN ROUTES
Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->group(function () {
    Route::resource('games', AdminAdminGameController::class)->names('admin.games');
    Route::resource('categories', CategoryController::class);
    Route::get('/transactions', [TransactionController::class, 'adminIndex'])->name('transactions.admin');
    Route::put('/transactions/{id}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::get('/admin/transactions', [AdminTransactionController::class, 'index'])->name('admin.transactions.index');
    Route::post('/admin/transactions/{id}/approve', [AdminTransactionController::class, 'approve'])->name('admin.transactions.approve');
    Route::post('/admin/transactions/{id}/cancel', [AdminTransactionController::class, 'cancel'])->name('admin.transactions.cancel');
});

// USER ROUTES
Route::middleware(['auth', UserMiddleware::class])->prefix('user')->group(function () {
    Route::get('/games', [GameController::class, 'index'])->name('user.games.index');
    Route::get('/games/create', [GameController::class, 'create'])->name('user.games.create');
    Route::post('/games', [GameController::class, 'store'])->name('user.games.store');
    Route::get('/games/{id}/edit', [GameController::class, 'edit'])->name('user.games.edit');
    Route::put('/games/{id}', [GameController::class, 'update'])->name('user.games.update');
    Route::delete('/games/{id}', [GameController::class, 'destroy'])->name('user.games.destroy');
    // Transaksi user
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
});

require __DIR__.'/auth.php';
