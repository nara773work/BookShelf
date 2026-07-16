<?php

namespace App\Http\Controllers;

class FavoriteController extends Controller
{
    public function index()
    {
        $books = auth()->user()->favoritebooks()->paginate(10);

        return view('favorites.index', compact('books'));
    }
}
