<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Review;
use App\Models\Genre;
use App\Http\Requests\BookRequest;
use Illuminate\Support\Facades\Http;

class BookController extends Controller
{
    public function index(Request $request){
        $genres =Genre::all();
        $query = Book::query();

        $keyword = $request->input('keyword');
        $genre = $request->input('genre');

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                ->orWhere('author', 'like', "%{$keyword}%");
            });
        }

        if ($genre) {
            $query->whereHas('genres', function ($q) use ($genre) 
            {
                $q->where('genres.id', $genre);
            });
        }

        match ($request->input('sort')) {
        'newest' => $query->orderBy('created_at', 'desc'),
        'oldest' => $query->orderBy('created_at', 'asc'),
        'title'  => $query->orderBy('title', 'asc'),
        'rating' => $query->withAvg('reviews', 'rating')->orderBy('reviews_avg_rating', 'desc'),
        default  => $query->latest(), 
    };


        $books = $query->paginate(10)->withQueryString();;
        return view('books.index',compact('books','genres'));
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
        ->with('success', '書籍を更新しました'); 
    }

    public function destroy(Book $book,Request $request){
        $this->authorize('delete', $book);

        $book->delete();
        
        return redirect()
        ->route('books.index')
        ->with('success', '書籍を削除しました'); 
    }

    public function isbn(Request $request,$isbn)
{
    $apiKey = 'AIzaSyAsovsHBdcHvjTLlY-zr8YGBW3QRAw9F68';

    $response = Http::timeout(5)
    ->get('https://www.googleapis.com/books/v1/volumes', 
    [
        'q' => 'isbn:' . $isbn ,
        'key' => $apiKey,
    ]);

    $data = $response->json();

    // 書籍データが存在するかチェック
    if (isset($data['items']) && !empty($data['items'])) {
        $info = $data['items'][0]['volumeInfo'];

        return response()->json([
            'title'          => $info['title'] ?? '',
            'author'         => isset($info['authors']) ? implode(', ', $info['authors']) : '',
            'description'    => $info['description'] ?? '',
            // JavaScriptで image_url を参照しているため、ここに画像URLを追加します
            'image_url'      => $info['imageLinks']['thumbnail'] ?? '',
            'published_date' => $info['publishedDate'] ?? null,
        ]);
    }

    return response()->json([
        'error' => '書籍が見つかりません。',
    ], 404);
}
    
    }


