<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function index(){
        $books = Book::paginate(10);
        
        return view('books.index',compact('books'));
    }

    public function show($id){

        $book = Book::findOrFail($id);
        return view('books.show',compact('book'));
    }

    public function store(){
        return view('books.create');
    }
}
