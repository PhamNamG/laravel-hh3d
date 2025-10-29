<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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

// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

// Các routes khác (sẽ implement sau)
Route::get('/phim/{slug}', [App\Http\Controllers\PhimController::class, 'show'])->name('phim');

// Xem phim
Route::get('/xem/{categorySlug}/{episodeSlug}', [App\Http\Controllers\XemController::class, 'show'])->name('xem');

