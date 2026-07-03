<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\ReadingplanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;


//nonactive
//publiced
Route::get('/books',[BookController::class,'index'])->name('books.index');
Route::get('/ranking',[RankingController::class,'index'])->name('ranking.index');


//authed
//report
Route::middleware('auth')
->group(function () {
    Route::get('/reports', [ReportController::class,'index'])->name('reports.index');
});


//reading
Route::middleware('auth')
->group(function () {
    Route::get('/reading-plans', [ReadingplanController::class,'index'])->name('reading-plans.index');
    Route::get('/reading-plans/create', [ReadingplanController::class,'create'])->name('reading-plans.create');
    Route::post('/reading-plans', [ReadingplanController::class,'store'])->name('reading-plans.store');
    Route::get('/reading-plans/{plan}/edit', [ReadingplanController::class,'edit'])->name('reading-plans.edit');
    Route::post('/reading-plans/{plan}/complete', [ReadingplanController::class,'complete'])->name('reading-plans.complete');
    Route::put('/reading-plans/{plan}', [ReadingplanController::class,'update'])->name('reading-plans.update');
    Route::delete('/reading-plans/{plan}', [ReadingplanController::class,'destroy'])->name('reading-plans.destroy');
});

//notifications
Route::middleware('auth')
->group(function () {
    Route::get('/notifications', [NotificationController::class,'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class,'read'])->name('notifications.read');
});

//book　新規登録
Route::middleware('auth')->group(function () {
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/books/create',[BookController::class,'store'])->name('books.store');
    });


//genres 新規登録
Route::middleware('auth')
->group(function () {
    Route::get('/genres', [GenreController::class, 'index'])->name('genres.index');
    Route::get('/genres/create', [GenreController::class, 'create'])->name('genres.create');
    Route::post('/genres/create', [GenreController::class, 'store'])->name('genres.store');
    });


//favorite 
Route::middleware('auth')
->group(function () {
    Route::get('/favorites', [FavoriteController::class,'index'])->name('favorites.index');
    });    


//active
//public 詳細画面
Route::get('/books/{book}',[BookController::class,'show'])->name('books.show');


//book お気に入り登録、編集
Route::middleware('auth')->group(function () {
    Route::post('/books/{book}/favorites', [BookController::class,'toggle'])->name('favorites.toggle');
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('/books/{book}',[BookController::class,'update'])->name('books.update');
    Route::delete('/books/{book}/delete',[BookController::class,'destroy'])->name('books.destroy');
    });


//genres 
Route::middleware('auth')
->group(function () {
    Route::get('/genres/{genre}', [GenreController::class, 'show'])->name('genres.show');
    Route::get('/genres/{genre}/edit', [GenreController::class,'edit'])->name('genres.edit');
    Route::put('/genres/{genre}',[GenreController::class,'update'])->name('genres.update');
    Route::delete('/genres/{genre}',[GenreController::class,'destroy'])->name('genres.destroy');
    });


//review いいね
Route::middleware('auth')
->group(function () {
    Route::post('/reviews/{review}/like', [ReviewController::class,'toggle'])->name('reviews.like');

    Route::post('/books/{book}/reviews', [ReviewController::class,'store'])->name('reviews.store');
    Route::get('/reviews/{review}/edit',[ReviewController::class,'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}/', [ReviewController::class,'update'])->name('reviews.update');
    Route::delete('/reviews/{review}/', [ReviewController::class,'destroy'])->name('reviews.destroy');
    });

