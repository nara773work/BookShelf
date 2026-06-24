<?php

use Illuminate\Support\Facades\Route;



use App\Http\Controllers\BookController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\GenreController;

//nonactive
//publiced
Route::get('/books',[BookController::class,'index'])->name('books.index');
Route::get('/ranking',[RankingController::class,'index'])->name('ranking.index');


//authed
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


//review 新規登録、自分のレビュー表示
Route::middleware('auth')
->group(function () {
    Route::post('/review', [ReviewController::class,'store'])->name('reviews.store');
    Route::get('/reviews/{review}/edit',[ReviewController::class,'store']);
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
    Route::post('/books/{books}/favorite', [BookController::class,'toggle'])->name('favorites.toggle');
    Route::get('/books/{book}/edit', [BookController::class, 'edit']);
    Route::put('/books/{$book->id}/edit',[BookController::class,'update']);
    Route::delete('/books/{$book->id}/edit',[BookController::class,'destroy']);
    });


//genres 
Route::middleware('auth')
->group(function () {
    Route::get('/genres/{genre}', [GenreController::class, 'show'])->name('genres.show');
    Route::get('/genres/{genre}/edit', [GenreController::class,'edit'])->name('genres.edit');
    Route::put('/books/{$book->id}/edit',[GenreController::class,'update'])->name('genres.update');
    Route::delete('/books/{book}/edit',[GenreController::class,'destroy'])->name('genres.destroy');
    });


//review 
Route::middleware('auth')
->group(function () {
    Route::post('/review/{review}/like', [ReviewController::class,'toggle'])->name('reviews.like');
    Route::post('/review/{review}/create', [ReviewController::class,'create'])->name('reviews.create');
    Route::post('/review/{review}/store', [ReviewController::class,'store'])->name('reviews.store');
    });
