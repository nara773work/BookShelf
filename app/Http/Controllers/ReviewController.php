<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Book;
use App\Http\Requests\ReviewRequest;

class ReviewController extends Controller
{
    public function edit(){
        return view('review.edit',compact('book'));
    }

    public function store(ReviewRequest $request,$id){
        $book = Book::findOrFail($id);

        $review = Review::create([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'user_id' => auth()->id(),
            'book_id' => $book->id
        ]);

        return redirect()->route('books.show', $book->id);       
    }

    public function toggle($id){
        
        $review =Review::findOrFail($id);
        auth()->user()->likedReviews()->toggle($review->id);

        return back();
    }
}
