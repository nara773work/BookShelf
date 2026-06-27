<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Review;
use App\Models\Genre;
use App\Http\Requests\BookRequest;

class BookController extends Controller
{
    public function index(){

        $books = Book::paginate(10);
        
        return view('books.index',compact('books'));
    }

    public function show(Book $book){

        $review = Review::withCount('likedByUsers')->get();

        return view('books.show',compact('book','review'));
    }

    public function create(){

        $genres = Genre::all();

        return view('books.create',compact('genres'));
    }

    public function store(BookRequest $request){
        
        $book = Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn,
            'published_date' => $request->published_date,
            'description' => $request->description,
            'image_url' => $request->image_url,
            'user_id' => auth()->id()
        ]);

        $book->genres()->attach($request->genres);

        return redirect()
        ->route('books.index')
        ->with('success', '書籍を登録しました'); 
    }

    public function toggle($id){

        $book = Book::findOrFail($id);

        auth()->user()->favoritebooks()->toggle($book->id);

        return back();
    }

    public function edit(Book $book){
        $this->authorize('update', $book);
        
        $genres = Genre::all();

        return view('books.edit',compact('book','genres'));
    }

    public function update(Book $book,BookRequest $request){
        $this->authorize('update', $book);
        
        $book->update([
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn,
            'published_date' => $request->published_date,
            'description' => $request->description,
            'image_url' => $request->image_url,
        ]);
        $book->genres()->sync($request->genres);

        return redirect()
        ->route('books.index')
        ->with('success', '書籍を編集しました'); 
    }

    public function destroy(Book $book,Request $request){
        $this->authorize('update', $book);

        $book->delete();
        
        return redirect()
        ->route('books.index')
        ->with('success', '書籍を削除しました'); 
    }

}
