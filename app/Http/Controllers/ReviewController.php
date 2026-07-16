<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Book;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(ReviewRequest $request, Book $book)
    {
        $comments = [
            1 => '満足できませんでした',
            2 => 'あまり満足できませんでした',
            3 => '普通でした',
            4 => '満足しました',
            5 => 'とても満足しました',
        ];

        $review = Review::create([
            'rating' => $request->rating,
            'comment' => $comments[$request->rating],
            'user_id' => auth()->id(),
            'book_id' => $book->id,
        ]);

        return redirect()
            ->route('books.show', $book)
            ->with('success', 'レビューを登録しました');
    }

    public function toggle($id)
    {

        $review = Review::findOrFail($id);
        auth()->user()->likedReviews()->toggle($review->id);

        return back();
    }

    public function edit(Review $review)
    {
        $this->authorize('update', $review);

        return view('reviews.edit', compact('review'));
    }

    public function update(ReviewRequest $request, Review $review)
    {
        $this->authorize('update', $review);

        $comments = [
            1 => '満足できませんでした',
            2 => 'あまり満足できませんでした',
            3 => '普通でした',
            4 => '満足しました',
            5 => 'とても満足しました',
        ];

        $review->update([
            'rating' => $request->rating,
            'comment' => $comments[$request->rating],
        ]);

        return redirect()
            ->route('books.show', $review->book)
            ->with('success', 'レビューを更新しました');
    }

    public function destroy(Review $review)
    {
        $this->authorize('update', $review);
        $review->delete();

        return redirect()
            ->route('books.show', $review->book)
            ->with('success', 'レビューを削除しました');
    }
}
