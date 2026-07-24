<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\View\View;

class RankingController extends Controller
{
    /**
     * ランキングを表示する。
     *
     * レビュー平均評価順で上位10件の書籍を取得する。
     *
     * @return View
     */
    public function index()
    {
        $rankedBooks = Book::withAvg('reviews', 'rating')
            ->whereHas('reviews')
            ->withCount('reviews')
            ->orderByDesc('reviews_avg_rating')
            ->take(10)
            ->get();

        return view('ranking.index', compact('rankedBooks'));
    }
}
