<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [App\Http\Controllers\TransactionController::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/admin/users',[App\Http\Controllers\AdminController::class,'getUsers'])->middleware(['auth','verified'])->name('admin.users');
Route::get('/admin/transactions',[App\Http\Controllers\AdminController::class,'getTransactions'])->middleware(['auth','verified'])->name('admin.transactions');
Route::put('/admin/users',[App\Http\Controllers\AdminController::class,'desactivateOrActivate'])->middleware(['auth','verified'])->name('admin.state');
Route::get('/transaction', function () {
    return view('transaction.don');
})->middleware(['auth', 'verified'])->name('don');

Route::get('/transaction/status', [App\Http\Controllers\TransactionController::class,'status'])->middleware(['auth', 'verified'])->name('transaction.status');

Route::post('/transaction', [App\Http\Controllers\TransactionController::class,'store'])->middleware(['auth', 'verified'])->name('transaction.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
