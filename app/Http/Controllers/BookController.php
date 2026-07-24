<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Http\Requests\FilterRequest;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class BookController extends Controller
{
    /**
     * 書籍一覧を表示する。
     *
     * キーワード検索、ジャンル検索、並び替えに対応した
     * 書籍一覧を取得して10件ずつ表示する。
     *
     * @param  FilterRequest  $request  検索条件
     * @return View
     */
    public function index(FilterRequest $request)
    {
        $genres = Genre::all();
        $query = Book::query();

        $keyword = $request->input('keyword');
        $genre = $request->input('genre');

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('author', 'like', "%{$keyword}%");
            });
        }

        if ($genre) {
            $query->whereHas('genres', function ($q) use ($genre) {
                $q->where('genres.id', $genre);
            });
        }

        match ($request->input('sort')) {
            'newest' => $query->orderBy('created_at', 'desc'),
            'oldest' => $query->orderBy('created_at', 'asc'),
            'title' => $query->orderBy('title', 'asc'),
            'rating' => $query->withAvg('reviews', 'rating')->orderBy('reviews_avg_rating', 'desc'),
            default => $query->latest(),
        };

        $books = $query->paginate(10)->withQueryString();

        return view('books.index', compact('books', 'genres'));
    }

    /**
     * 書籍詳細を表示する。
     *
     * @param  Book  $book  表示する書籍
     * @return View
     */
    public function show(Book $book)
    {

        $review = Review::withCount('likedByUsers')->get();

        return view('books.show', compact('book', 'review'));
    }

    /**
     * 書籍登録画面を表示する。
     *
     * @return View
     */
    public function create()
    {
        $genres = Genre::all();

        return view('books.create', compact('genres'));
    }

    /**
     * 書籍を登録する。
     *
     * @param  BookRequest  $request  登録する書籍情報
     * @return RedirectResponse
     */
    public function store(BookRequest $request)
    {
        DB::transaction(function () use ($request) {
            $book = Book::create([
                'title' => $request->title,
                'author' => $request->author,
                'isbn' => $request->isbn,
                'published_date' => $request->published_date,
                'description' => $request->description,
                'image_url' => $request->image_url,
                'user_id' => auth()->id(),
            ]);

            // 中間テーブルに紐づける
            $book->genres()->attach($request->genres);
        });

        return redirect()
            ->route('books.index')
            ->with('success', '書籍を登録しました');
    }

    /**
     * お気に入り登録・解除を切り替える。
     *
     * @param  int  $id  対象書籍ID
     * @return RedirectResponse
     */
    public function toggle($id)
    {

        $book = Book::findOrFail($id);

        auth()->user()->favoritebooks()->toggle($book->id);

        return back();
    }

    /**
     * 書籍編集画面を表示する。
     *
     * @param  Book  $book  編集対象の書籍
     * @return View
     */
    public function edit(Book $book)
    {
        $this->authorize('update', $book);

        $genres = Genre::all();

        return view('books.edit', compact('book', 'genres'));
    }

    /**
     * 書籍を更新する。
     *
     * @param  Book  $book  更新対象の書籍
     * @param  BookRequest  $request  更新する書籍情報
     * @return RedirectResponse
     */
    public function update(Book $book, BookRequest $request)
    {
        $this->authorize('update', $book);
        DB::transaction(function () use ($book, $request) {
            $book->update([
                'title' => $request->title,
                'author' => $request->author,
                'isbn' => $request->isbn,
                'published_date' => $request->published_date,
                'description' => $request->description,
                'image_url' => $request->image_url,
            ]);

            $book->genres()->sync($request->genres);

        });

        return redirect()
            ->route('books.index')
            ->with('success', '書籍を更新しました');
    }

    /**
     * 書籍を削除する。
     *
     * @param  Book  $book  削除する書籍
     * @return RedirectResponse
     */
    public function destroy(Book $book)
    {
        $this->authorize('delete', $book);

        $book->delete();

        return redirect()
            ->route('books.index')
            ->with('success', '書籍を削除しました');
    }

    /**
     * Google Books APIからISBNをもとに書籍情報を取得する。
     *
     * @param  Request  $request  リクエスト
     * @param  string  $isbn  ISBN
     * @return JsonResponse
     */
    public function isbn(Request $request, $isbn)
    {
        $apiKey = config('services.google_books.key');

        $response = Http::timeout(5)
            ->get('https://www.googleapis.com/books/v1/volumes',
                [
                    'q' => 'isbn:'.$isbn,
                    'key' => $apiKey,
                ]);

        $data = $response->json();

        // 書籍データが存在するかチェック
        if (isset($data['items']) && ! empty($data['items'])) {
            $info = $data['items'][0]['volumeInfo'];

            return response()->json([
                'title' => $info['title'] ?? '',
                'author' => isset($info['authors']) ? implode(', ', $info['authors']) : '',
                'description' => $info['description'] ?? '',
                'image_url' => $info['imageLinks']['thumbnail'] ?? '',
                'published_date' => $info['publishedDate'] ?? null,
            ]);
        }

        return response()->json([
            'error' => '書籍が見つかりません。',
        ], 404);
    }
}
