<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
| Views are not required for this skill test, so we keep this minimal.
*/
Route::get('/', function () {
    return 'OK';
})->name('home');

/*
|--------------------------------------------------------------------------
| Public Post Routes
|--------------------------------------------------------------------------
| Only published (active) posts
*/
Route::get('/posts', [PostController::class, 'index'])
    ->name('posts.index');

Route::get('/posts/{post}', [PostController::class, 'show'])
    ->name('posts.show');

/*
|--------------------------------------------------------------------------
| Protected Post Routes
|--------------------------------------------------------------------------
| Authenticated users only
*/
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

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
