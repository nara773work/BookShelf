<?php

namespace App\Http\Controllers;

use App\Enums\ReadingPlanStatus;
use App\Models\Genre;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * 読書レポートを表示する。
     *
     * @return View
     */
    public function index()
    {
        $user = auth()->user();
        $reviews = $user->reviews()->with('book.genres')->get();
        $plans = $user->readingPlans()->get();
        $completedBooks = $plans->where('status', ReadingPlanStatus::Completed);

        $stats = [
            'summary' => [
                'total_reviews' => $reviews->count(),
                'books_read' => $completedBooks->count(),
                'average_rating' => $reviews->avg('rating'),
            ],

            'rating_distribution' => collect(range(0, 4))
                ->map(fn ($i) => $reviews->where('rating', $i + 1)->count()),

            'top_rated_books' => $reviews
                ->where('rating', '>=', 4)
                ->sortByDesc('rating')
                ->map(fn ($review) => [
                    'id' => $review->book->id,
                    'title' => $review->book->title,
                    'author' => $review->book->author,
                    'rating' => $review->rating,
                ])
                ->take(5)
                ->values(),

            'genre_ratings' => Genre::all()->map(function ($genre) use ($user) {
                // このジャンルに紐づくユーザーレビューだけ集める
                $reviews = $genre->books()
                    ->whereHas('reviews', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })
                    ->with('reviews')
                    ->get()
                    ->flatMap->reviews
                    ->where('user_id', $user->id);

                return [
                    'id' => $genre->id,
                    'name' => $genre->name,
                    'count' => $reviews->count(),
                    'average_rating' => $reviews->avg('rating') ?? 0,
                ];
            })
                ->filter(fn ($g) => $g['count'] > 0)
                ->sortByDesc('average_rating')
                ->take(5)
                ->values(),
        ];

        return view('reports.index', compact('stats'));
    }
}
