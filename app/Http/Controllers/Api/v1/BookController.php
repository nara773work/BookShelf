<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Http\Requests\FilterRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class BookController extends Controller
{
    /**
     * 書籍一覧を表示する。
     *
     * @param  FilterRequest  $request  検索条件
     * @return JsonResponse
     */
    public function index(FilterRequest $request)
    {
        $query = Book::query();

        $query->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->with('genres');

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('author', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('genre')) {
            $query->whereHas('genres', function ($q) use ($request) {
                $q->where('genres.id', $request->genre);
            });
        }

        if ($request->sort === 'title') {
            $query->orderBy('title');
        }

        $books = $query->paginate(10);

        return BookResource::collection($books)
            ->additional(['message' => '書籍一覧の取得に成功しました'])
            ->response()
            ->setStatusCode(200);
    }

    /**
     * 書籍を登録する。
     *
     * @param  BookRequest  $request  登録する書籍情報
     * @return JsonResponse
     */
    public function store(BookRequest $request)
    {
        if (! auth()->check()) {
            return response()->json([
                'message' => 'ログインしてください',
            ], 401);
        }

        $book = Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn,
            'published_date' => $request->published_date,
            'description' => $request->description,
            'image_url' => $request->image_url,
            'user_id' => auth()->id(),
        ]);

        $book->genres()->attach($request->genres);

        return (new BookResource($book))
            ->additional(['message' => '書籍の登録に成功しました'])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * 書籍詳細を取得する。
     *
     * @param  string  $id  取得対象の書籍ID
     * @return JsonResponse
     */
    public function show(string $id)
    {
        $book = Book::with(['genres', 'reviews'])
            ->find($id);

        if (! $book) {
            return response()->json([
                'message' => '書籍が存在しません',
            ], 404);
        }

        return (new BookResource($book))
            ->additional(['message' => '書籍の詳細取得に成功しました'])
            ->response()
            ->setStatusCode(200);
    }

    /**
     * 書籍を更新する。
     *
     * @param  BookRequest  $request  更新する書籍情報
     * @param  string  $id  更新対象の書籍ID
     * @return JsonResponse
     */
    public function update(BookRequest $request, string $id)
    {
        $book = Book::with(['genres', 'reviews'])
            ->find($id);

        if (! $book) {
            return response()->json([
                'message' => '書籍が存在しません',
            ], 404);
        }

        if ($book->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'この操作を行う権限がありません',
            ], 403);
        }

        $book->update([
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn,
            'published_date' => $request->published_date,
            'description' => $request->description,
            'image_url' => $request->image_url,
        ]);

        $book->genres()->sync($request->genres);

        return (new BookResource($book))
            ->additional(['message' => '書籍の更新に成功しました'])
            ->response()
            ->setStatusCode(200);
    }

    /**
     * 書籍を削除する。
     *
     * @param  string  $id  削除対象の書籍ID
     * @return Response
     */
    public function destroy(string $id)
    {
        $book = Book::with(['genres', 'reviews'])
            ->find($id);

        if (! $book) {
            return response()->json([
                'message' => '書籍が存在しません',
            ], 404);
        }

        if ($book->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'この操作を行う権限がありません',
            ], 403);
        }

        $book->delete();

        return response()->noContent();
    }
}
