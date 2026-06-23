<?php

use Illuminate\Support\Facades\Route;



use App\Http\Controllers\BookController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReviewController;

//nonactive
//publiced
Route::get('/books',[BookController::class,'index'])->name('books.index');
Route::get('/ranking',[RankingController::class,'index'])->name('ranking.index');

//authed
//book create
Route::middleware('auth')->group(function () {
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');

    Route::post('/books',[BookController::class,'store'])->name('books.store');
    });


//genre index
use App\Http\Controllers\GenreController;
Route::middleware('auth')
->group(function () {
    Route::get('genre', [BookController::class, 'index'])->name('genres.index');
    });

//create    
Route::middleware('auth')
->group(function () {
    Route::get('/genre/create', [BookController::class, 'store']);
    });
Route::post('/books/cretae',[BookController::class,'store']);

//edit
Route::middleware('auth')
->group(function () {
    Route::get('/review/{review}/edit', [BookController::class,'edit']);
    });


//favorite edit
Route::middleware('auth')
->group(function () {
    Route::get('/favorites', [FavoriteController::class,'index'])->name('favorites.index');
    });    


//active
//public
Route::get('/books/{book}',[BookController::class,'show'])->name('books.show');

//book edit
Route::middleware('auth')->group(function () {
    Route::get('/books/{book}/edit', [BookController::class, 'edit']);
    });
Route::put('/books/{$book->id}/edit',[BookController::class,'update']);
Route::delete('/books/{$book->id}/edit',[BookController::class,'destroy']);

//genre show
Route::middleware('auth')
->group(function () {
    Route::get('/genre/{genre}', [BookController::class, 'show']);
    });

//genre edit
Route::middleware('auth')
->group(function () {
    Route::get('/genre/{genre}/edit', [BookController::class,'edit']);
    });
Route::put('/books/{$book->id}/edit',[BookController::class,'update']);
Route::delete('/books/{book}/edit',[BookController::class,'destroy']);