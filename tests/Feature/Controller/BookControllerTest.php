<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Genre;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_book_index(): void
    {
        $user = User::first();

        // ゲストユーザーのアクセス
        $response = $this->get('/books');
        $response->assertStatus(200);
        $response->assertViewIs('books.index');

        // 認証済みユーザーのアクセス
        $response = $this->actingAs($user)
            ->get('/books');
        $response->assertStatus(200);
        $response->assertViewIs('books.index');
    }

    public function test_book_index_paginate(): void
    {
        $response = $this->get('/books');

        // ページネーションされているかテストする
        // 1ページ目に10件、2ページ目に1件（11件ダミーデータあり）
        $response->assertViewHas('books', function ($books) {
            return $books->count() === 10;
        });

        $response = $this->get('/books?page=2');
        $response->assertViewHas('books', function ($books) {
            return $books->count() === 1; // 2ページ目に1件
        });
    }

    public function test_book_index_view_genre(): void
    {
        $book = Book::first();
        $genre = $book->genres->first();

        $response = $this->get('/books');

        // 各書籍に紐づいているジャンルが表示されている
        $response->assertViewHas('books', function ($books) {
            foreach ($books as $book) {
                if ($book->genres->isEmpty()) {
                    return false;
                }
            }

            return true;
        });

        $response->assertSee($genre->name);
    }

    public function test_book_index_fillter(): void
    {
        $book = Book::where('title', '吾輩は猫である')->first();

        $response = $this->get('/books?keyword=猫');

        $response->assertStatus(200);
        $response->assertViewIs('books.index');
        $response->assertSee($book->title);
        $response->assertViewHas('books', function ($book) {
            return $book->count() === 1;
        });
        $response->assertDontSee('人間失格');

        $author = Book::where('author', '夏目漱石')->first();

        $response = $this->get('/books?keyword=夏');
        $response->assertStatus(200);
        $response->assertViewIs('books.index');
        $response->assertSee($book->title);
        $response->assertViewHas('books', function ($book) {
            return $book->count() === 2;
        });

        $response->assertDontSee('Clean Code');
    }

    public function test_book_index_genre_fillter(): void
    {
        $genres = Genre::first(); // 検索でジャンル=1を選択
        $keyword = $genres->books()->get(); // 検索したジャンルを持つ書籍を取得

        $response = $this->get('/books?genre=1');
        $response->assertStatus(200);
        $response->assertViewIs('books.index');
        $response->assertSee($genres->name);
        $response->assertViewHas('books', function ($books) {
            return $books->count() === 3;
        });

        $author = Book::where('author', '夏目漱石')->first();

        // 検索条件が重なっても絞り込める
        $response = $this->get('/books?keyword=夏&genre=1');
        $response->assertStatus(200);
        $response->assertViewIs('books.index');
        $response->assertSee($author->author);
        $response->assertSee($genres->name);
        $response->assertViewHas('books', function ($books) {
            return $books->count() === 2;
        });
    }

    public function test_book_index_sort(): void
    {
        $books = Book::all();

        // title順に並び替え
        $response = $this->get('/books?sort=title');
        $response->assertViewIs('books.index');
        $response->assertViewHas('books', function ($books) {
            return $books->first()->title === '7つの習慣';
        });

        $response->assertViewHas('books', function ($books) {
            $ratings = $books->pluck('reviews_avg_rating')->filter()->values();

            return $ratings->toArray() === $ratings->sortDesc()->values()->toArray();
        });

        // 評価順に表示
        $response = $this->get('/books?sort=rating');
        $response->assertViewIs('books.index');
    }

    public function test_book_index_paginate_keep(): void
    {
        $response = $this->get('/books?sort=rating');

        // ページネーションされているかテストする
        // 1ページ目に10件、2ページ目に1件（11件ダミーデータあり）
        $response->assertViewHas('books', function ($books) {
            return $books->count() === 10;
        });

        $response = $this->get('/books?page=2&sort=rating');

        $response->assertViewHas('books', function ($books) {
            return $books->count() === 1;
        });

        $response->assertViewHas('books', function ($books) {
            $ratings = $books->pluck('reviews_avg_rating')
                ->filter()
                ->values();

            return $ratings->toArray() === $ratings->sortDesc()->values()->toArray();
        });
    }

    public function test_book_show(): void
    {
        $book = Book::first();
        $response = $this->get("/books/{$book->id}");
        $genres = $book->genres()->first();
        $reviews = Review::withCount('likedByUsers')->first();
        $reviewLikes = $reviews->first()->review_likes_count;
        $response->assertStatus(200);
        $response->assertViewIs('books.show');

        $response->assertSee($book->title);
        $response->assertSee($book->author);
        $response->assertSee($book->isbn);
        $response->assertSee(
            $book->published_date->format('Y-m-d')
        );
        $response->assertSee($book->description);
        $response->assertSee($book->image_url);
        $response->assertSee($genres->name);
        $response->assertSee($reviews->rating);
        $response->assertSee($reviews->comment);
        $response->assertSee($reviewLikes);
    }

    public function test_book_create(): void
    {
        $user = User::first();
        $genres = Genre::all();

        $response = $this->actingAs($user)->get('/books/create');

        $response->assertSee('タイトル');
        $response->assertSee('著者');
        $response->assertSee('isbn');
        $response->assertSee('出版日');
        $response->assertSee('説明');
        $response->assertSee('画像URL');
        $response->assertSee('ジャンル');
        foreach ($genres as $genre) {
            $response->assertSee($genre->name);
        }

        $response->assertStatus(200);
        $response->assertViewIs('books.create');
    }

    public function test_book_create_redirect(): void
    {
        $response = $this->get('/books/create');
        $response->assertRedirect('/login');
    }

    public function test_book_store(): void
    {
        $user = User::first();
        $genres = Genre::take(2)->get();

        $book = ([
            'title' => 'test',
            'author' => 'test',
            'isbn' => '0000000000000',
            'published_date' => '2026-06-12',
            'user_id' => $user->id,
            'genres' => $genres->pluck('id')->toArray(),
        ]);

        $response = $this->actingAs($user)
            ->post('/books', $book);

        $response->assertSessionHas('success', '書籍を登録しました');

        $response->assertRedirect('/books');

        $this->assertDatabaseHas('books',
            ['isbn' => 0000000000000]);

        $book = Book::where('isbn', '0000000000000')->first();

        $this->assertCount(2, $book->genres);

        // 中間テーブルに保存されているか確認
        foreach ($genres as $genre) {
            $this->assertDatabaseHas('book_genre', [
                'book_id' => $book->id,
                'genre_id' => $genre->id,
            ]);
        }
    }

    public function test_toggle(): void
    {
        $user = User::first();
        $book = Book::whereDoesntHave('favoritebooks', function ($query) use ($user) {
            $query
                ->where('user_id', $user->id);
        })->first();

        $response = $this
            ->actingAs($user)
            ->post("/books/{$book->id}/favorites");

        $response->assertRedirect();

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);

        $this->actingAs($user)
            ->post("/books/{$book->id}/favorites");

        $response->assertRedirect();

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);

    }

    public function test_toggle_redirect(): void
    {
        $user = User::first();
        $book = Book::whereDoesntHave('favoritebooks', function ($query) use ($user) {
            $query
                ->where('user_id', $user->id);
        })->first();

        $response = $this->post("/books/{$book->id}/favorites");
        $response->assertRedirect('/login');
    }

    public function test_book_edit(): void
    {
        $user = User::first();
        $book = Book::where('user_id', 1)->first();
        $genres = $book->genres()->first();
        $reviews = Review::withCount('likedByUsers')->first();

        $response = $this->actingAs($user)
            ->get("/books/{$book->id}/edit");

        $response->assertSee($book->title);
        $response->assertSee($book->author);
        $response->assertSee($book->isbn);
        $response->assertSee(
            $book->published_date->format('Y-m-d')
        );
        $response->assertSee($book->description);
        $response->assertSee($book->image_url);
        $response->assertSee($genres->name);

        $response->assertStatus(200);
        $response->assertViewIs('books.edit');
    }

    public function test_book_update(): void
    {
        $user = User::first();
        $book = Book::where('user_id', 1)->first();
        $genres = Genre::take(10)->get();

        $update_book = ([
            'title' => 'edited',
            'author' => $book->author,
            'isbn' => $book->isbn,
            'user_id' => $book->user_id,
            'published_date' => $book->published_date,
            'genres' => $genres->pluck('id')->toArray(),
        ]);

        $response = $this->actingAs($user)
            ->put("/books/{$book->id}", $update_book);

        $response->assertSessionHas('success', '書籍を更新しました');

        $response->assertRedirect('/books');

        $this->assertDatabaseHas('books', ['title' => 'edited']);

        $book = Book::where('title', 'edited')->first();

        $this->assertCount(10, $book->genres);

        // 中間テーブルに保存されているか確認
        foreach ($genres as $genre) {
            $this->assertDatabaseHas('book_genre', [
                'book_id' => $book->id,
                'genre_id' => $genre->id,
            ]);
        }
    }

    public function test_book_delete(): void
    {
        $user = User::first();
        $book = Book::where('user_id', 1)->first();

        $response = $this->actingAs($user)
            ->delete("/books/{$book->id}");

        $response->assertRedirect('/books');

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
        $this->assertDatabaseMissing('reviews', ['book_id' => $book->id]);
        $this->assertDatabaseMissing('favorites', ['book_id' => $book->id]);
        $this->assertDatabaseMissing('book_genre', ['book_id' => $book->id]);

        $response->assertSessionHas('success', '書籍を削除しました');
    }

    public function test_book_isbn_get(): void
    {
        $user = User::first();
        $isbn = '9784488464011';

        $response = $this->actingAs($user)
            ->get("/books/isbn/{$isbn}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'title',
            'author',
            'published_date',
        ]);
    }

    public function test_book_isbn_error(): void
    {
        $user = User::first();
        $isbn = '1111111111111';

        $response = $this->actingAs($user)
            ->get("/books/isbn/{$isbn}");

        $response->assertStatus(404);
    }
}
