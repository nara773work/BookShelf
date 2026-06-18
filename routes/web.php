<?php

use Illuminate\Support\Facades\Route;



use App\Http\Controllers\BookController;
Route::get('/books',[BookController::class,'index']);

Route::get('/books/{$book->id}',[BookController::class,'show']);

Route::get('/books/cretae',[BookController::class,'store'])->name('books.create');
Route::post('/books/cretae',[BookController::class,'store']);

Route::get('/books/{$book->id}/edit',[BookController::class,'edit']);
Route::post('/books/{$book->id}/edit',[BookController::class,'update']);
Route::delete('/books/{$book->id}/edit',[BookController::class,'destroy']);


use App\Http\Controllers\GenreController;
Route::get('/genre',[GenreController::class,'index']);

Route::get('/genre/{$genre->id}',[GenreController::class,'show']);

Route::get('/genre/create',[GenreController::class,'store']);
Route::post('/books/cretae',[BookController::class,'store']);

Route::get('/genre/{$genre->id}/edit',[GenreController::class,'edit']);
Route::post('/books/{$book->id}/edit',[BookController::class,'update']);
Route::delete('/books/{$book->id}/edit',[BookController::class,'destroy']);

use App\Http\Controllers\ReviewController;
