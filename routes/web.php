<?php

use Illuminate\Support\Facades\Route;



use App\Http\Controllers\BookController;
Route::get('/books',[BookController::class,'index']);
Route::get('/books/{$book->id}',[BookController::class,'show']);
Route::get('/books',[BookController::class,'store'])->name('books.create');
Route::get('/books/{$book->id}/edit',[BookController::class,'edit'])->name('books.create');
