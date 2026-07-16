<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenreRequest;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function index()
    {
        $genres = Genre::withCount('books')->get();

        return view('genres/index', compact('genres'));
    }

    public function show(Request $request, $id)
    {
        $genre = Genre::findOrFail($id);
        $books = $genre->books()->paginate(10);

        return view('genres/show', compact('genre', 'books'));
    }

    public function create()
    {
        return view('genres/create');
    }

    public function store(GenreRequest $request)
    {

        $genres = Genre::withCount('books')->get();
        $genre = Genre::create([
            'name' => $request->name,
        ]);

        return redirect()
            ->route('genres.index')
            ->with('success', 'ジャンルを登録しました');
    }

    public function edit(Request $request, $id)
    {
        $genre = Genre::findOrFail($id);

        return view('genres/edit', compact('genre'));
    }

    public function update(GenreRequest $request, $id)
    {
        $genre = Genre::findOrFail($id);
        $genre->update([
            'name' => $request->name,
        ]);

        return redirect()
            ->route('genres.index')
            ->with('success', 'ジャンルを更新しました');
    }

    public function destroy(Request $request, $id)
    {
        $genre = Genre::findOrFail($id);
        if ($genre->books()->count() == 0) {
            $genre->delete();
        } else {
            return redirect()->route('genres.index')
                ->with('error', '紐づいている書籍があるため削除できません');
        }

        return redirect()
            ->route('genres.index')
            ->with('success', 'ジャンルを削除しました');
    }
}
