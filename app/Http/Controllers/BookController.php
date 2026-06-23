<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Review;
use App\Models\Genre;

class BookController extends Controller
{
    public function index(){
        $books = Book::paginate(10);
        
        return view('books.index',compact('books'));
    }

    public function show($id){

        $book = Book::find($id);

        $review = Review::withCount('likedByUsers')->get();

        return view('books.show',compact('book','review'));
    }

    public function create(){

        $genres = Genre::all();

        return view('books.create',compact('genres'));
    }

    public function store(Request $request){
        
        $book = Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn,
            'published_date' => $request->published_date,
            'description' => $request->description,
            'image_url' => $request->image_url,
            'user_id' => auth()->id()
        ]);

        $book->genres()->attach($request->genre_ids);

        return redirect('');
    }
}
