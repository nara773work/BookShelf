<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Genre;
use App\Models\Book;

class GenreController extends Controller
{
    public function index(){
        $genres = Genre::withCount('books')->get();
        return view('genres/index',compact('genres'));
    }

    public function show(Request $request,$id){
        $genre = Genre::findOrFail($id);
        $books = $genre->books()->paginate(10);
        return view('genres/show',compact('genre','books'));
    }

    public function create(){
        return view('genres/create');
    }
    public function store(){
        return view('genres/create');
    }
    public function edit(Request $request,$id){
        $genre = Genre::findOrFail($id);
        return view('genres/edit',compact('genre'));
    }
    public function update(Request $request,$id){
        $genre = Genre::findOrFail($id);
        return view('genres/edit',compact('genre'));
    }
    public function destroy(Request $request,$id){
        $genre = Genre::findOrFail($id);
        return view('genres/edit',compact('genre'));
    }
}
