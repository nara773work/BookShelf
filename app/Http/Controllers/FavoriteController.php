<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class FavoriteController extends Controller
{
    /**
     * お気に入りの書籍一覧を表示する。
     *
     * @return View
     */
    public function index()
    {
        $books = auth()->user()->favoritebooks()->paginate(10);

        return view('favorites.index', compact('books'));
    }
}
