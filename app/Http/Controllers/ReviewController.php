<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Book;
use App\Http\Requests\ReviewRequest;

class ReviewController extends Controller
{
    public function store(ReviewRequest $request,Book $book){
         
        $review = Review::create([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'user_id' => auth()->id(),
            'book_id' => $book->id
        ]);

        return redirect()
        ->route('books.show',$book)
        ->with('success', 'レビューを登録しました');       
    }

    public function toggle($id){
        
        $review =Review::findOrFail($id);
        auth()->user()->likedReviews()->toggle($review->id);

        return back();
    }

    public function edit(Review $review){    
        $this->authorize('update', $review);
        
        return view('reviews.edit',compact('review'));
    }

    public function update(ReviewRequest $request,Review $review){
        $this->authorize('update', $review);

        $review ->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()
        ->route('books.show',$review->book)
        ->with('success', 'レビューを更新しました');
    }

    public function destroy(Review $review){
        $this->authorize('update', $review);
        $review->delete();

        return redirect()
        ->route('books.show',$review->book)
        ->with('success', 'レビューを削除しました');
    }
}
