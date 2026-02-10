<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 1. Home Route
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return 'Home'; // Test Logout butuh redirect ke sini
})->name('home');

/*
|--------------------------------------------------------------------------
| 2. Dummy Dashboard (PENTING AGAR TEST LOGIN LOLOS)
|--------------------------------------------------------------------------
| Test Login & Register mengharuskan redirect ke route bernama 'dashboard'.
| Kita buat route sederhana saja agar test-nya hijau.
*/
Route::get('/dashboard', function () {
    return 'Dashboard Page';
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| 3. Post Routes (INI TUGAS UTAMA KAMU)
|--------------------------------------------------------------------------
*/
Route::get('/posts', [PostController::class, 'index'])
    ->name('posts.index');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/posts/create', [PostController::class, 'create'])
        ->name('posts.create');

    Route::post('/posts', [PostController::class, 'store'])
        ->name('posts.store');

    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])
        ->name('posts.edit');

    Route::match(['put', 'patch'], '/posts/{post}', [PostController::class, 'update'])
        ->name('posts.update');

    Route::delete('/posts/{post}', [PostController::class, 'destroy'])
        ->name('posts.destroy');
});

Route::get('/posts/{post}', [PostController::class, 'show'])
    ->name('posts.show');

/*
|--------------------------------------------------------------------------
| 4. Auth Routes
|--------------------------------------------------------------------------
*/
// Pastikan file auth.php ada (bawaan Laravel Breeze/Install)
require __DIR__.'/auth.php';
