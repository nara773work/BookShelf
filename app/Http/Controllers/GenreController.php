<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenreRequest;
use App\Models\Genre;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GenreController extends Controller
{
    /**
     * ジャンル一覧を表示する。
     *
     * ジャンルに紐づいている書籍数を表示する。
     *
     * @return View
     */
    public function index()
    {
        $genres = Genre::withCount('books')->get();

        return view('genres/index', compact('genres'));
    }

    /**
     * ジャンル詳細を表示する。
     *
     * 紐づいている書籍も表示する。
     *
     * @param  int  $id  ジャンルID
     * @return View
     */
    public function show($id)
    {
        $genre = Genre::findOrFail($id);
        $books = $genre->books()->paginate(10);

        return view('genres/show', compact('genre', 'books'));
    }

    /**
     * ジャンル登録画面を表示する。
     *
     * @return View
     */
    public function create()
    {
        return view('genres/create');
    }

    /**
     * ジャンルを登録する。
     *
     * @param  GenreRequest  $request  登録するジャンル情報
     * @return RedirectResponse
     */
    public function store(GenreRequest $request)
    {
        $genre = Genre::create([
            'name' => $request->name,
        ]);

        return redirect()
            ->route('genres.index')
            ->with('success', 'ジャンルを登録しました');
    }

    /**
     * ジャンル編集画面を表示する。
     *
     * @param  int  $id  編集対象のジャンルID
     * @return View
     */
    public function edit($id)
    {
        $genre = Genre::findOrFail($id);

        return view('genres/edit', compact('genre'));
    }

    /**
     * ジャンルを更新する。
     *
     * @param  GenreRequest  $request  更新するジャンル情報
     * @param  int  $id  更新対象のジャンルID
     * @return RedirectResponse
     */
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

    /**
     * ジャンルを削除する。
     *
     * 紐づいている書籍がない場合のみ削除する。
     *
     * @param  int  $id  削除対象のジャンルID
     * @return RedirectResponse
     */
    public function destroy($id)
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
