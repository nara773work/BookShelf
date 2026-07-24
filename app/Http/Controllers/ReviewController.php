<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReviewController extends Controller
{
    /**
     * レビューを登録する。
     *
     * @param  ReviewRequest  $request  レビュー内容
     * @param  Book  $book  レビュー対象の書籍
     * @return RedirectResponse
     */
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

    /**
     * レビューのいいね登録・解除を切り替える。
     *
     * @param  int  $id  レビューID
     * @return RedirectResponse
     */
    public function toggle($id)
    {

        $review = Review::findOrFail($id);
        auth()->user()->likedReviews()->toggle($review->id);

        return back();
    }

    /**
     * レビュー編集画面を表示する。
     *
     * @param  Review  $review  編集するレビュー
     * @return View
     */
    public function edit(Review $review)
    {
        $this->authorize('update', $review);

        return view('reviews.edit', compact('review'));
    }

    /**
     * レビューを更新する。
     *
     * @param  ReviewRequest  $request  更新するレビュー情報
     * @param  Review  $review  更新対象のレビュー
     * @return RedirectResponse
     */
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

    /**
     * レビューを削除する。
     *
     * @param  Review  $review  削除対処のレビュー
     * @return RedirectResponse
     */
    public function destroy(Review $review)
    {
        $this->authorize('update', $review);
        $review->delete();

        return redirect()
            ->route('books.show', $review->book)
            ->with('success', 'レビューを削除しました');
    }
}
