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

// Search
Route::get('/search', [App\Http\Controllers\SearchController::class, 'index'])->name('search');

// Mới cập nhật (Latest)
Route::get('/moi-cap-nhat', [App\Http\Controllers\LatestController::class, 'getLatest'])->name('latest');

// Phim routes
Route::get('/phim/{slug}', [App\Http\Controllers\PhimController::class, 'show'])->name('phim');

// Xem phim
Route::get('/xem/{episodeSlug}', [App\Http\Controllers\XemController::class, 'show'])->name('xem');

// API routes
Route::prefix('api')->group(function () {
    // Sidebar
    Route::get('/sidebar/popular', [App\Http\Controllers\SidebarController::class, 'getPopularMovies'])->name('api.sidebar.popular');
    Route::post('/sidebar/clear-cache', [App\Http\Controllers\SidebarController::class, 'clearCache'])->name('api.sidebar.clear-cache');
    
    // Week Schedule
    Route::get('/week', [App\Http\Controllers\WeekController::class, 'getByWeek'])->name('api.week');
    Route::post('/week/clear-cache', [App\Http\Controllers\WeekController::class, 'clearCache'])->name('api.week.clear-cache');
});

// Sitemap
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
Route::get('/sitemap/debug', [App\Http\Controllers\SitemapController::class, 'debug'])->name('sitemap.debug');
Route::post('/sitemap/clear', [App\Http\Controllers\SitemapController::class, 'clear'])->name('sitemap.clear');

// Calendar
Route::get('/lich-chieu', [App\Http\Controllers\CalendarController::class, 'index'])->name('calendar');
Route::get('/xem-nhieu', [App\Http\Controllers\TopViewController::class, 'index'])->name('top-view');
Route::get('/hoan-thanh', [App\Http\Controllers\CompleteController::class, 'index'])->name('complete');