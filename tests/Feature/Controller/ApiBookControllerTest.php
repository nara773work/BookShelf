<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiBookControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_api_index(): void
    {
        $response = $this->get('/api/v1/books');
        $response->assertStatus(200);

        $response->assertJsonFragment([
            'message' => '書籍一覧の取得に成功しました',
        ]);

        $response->assertJsonStructure([
            'message',
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'author',
                    'isbn',
                    'published_date',
                    'description',
                    'image_url',
                    'genres',
                    'reviews_avg_rating',
                    'reviews_count',
                ],
            ],
            'meta',
        ]);
    }

    public function test_book_index_paginate(): void
    {
        $response = $this->get('/api/v1/books');
        $response->assertStatus(200);

        $this->assertCount(
            10,
            $response->json('data')
        );

        $response = $this->get('/api/v1/books?page=2');

        $this->assertCount(
            1,
            $response->json('data')
        );
    }

    public function test_book_index_view_genre(): void
    {
        $book = Book::first();
        $genre = $book->genres->first();

        $response = $this->get('/api/v1/books');

        // 各書籍に紐づいているジャンルが表示されている
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'genres',
                ],
            ],
        ]);
    }

    public function test_book_index_fillter(): void
    {
        $keyword = '猫';
        $book = Book::where('title', '吾輩は猫である')
            ->first();

        $response = $this->get("/api/v1/books?keyword={$keyword}");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'title' => $book->title,
        ]);

        $this->assertCount(
            1,
            $response->json('data')
        );

        $response = $this->get('/api/v1/books?keyword=夏');

        $this->assertCount(
            2,
            $response->json('data')
        );
    }

    public function test_book_index_genre_fillter(): void
    {
        $genres = Genre::first(); // 検索でジャンル=1を選択

        $response = $this->get("/api/v1/books?genre={$genres->id}");
        $response->assertStatus(200);

        foreach ($response->json('data') as $book) {

            $this->assertContains(
                $genres->name,
                $book['genres']
            );
        }

        $this->assertCount(
            3,
            $response->json('data')
        );

        // 検索条件が重なっても絞り込める
        $response = $this->get("/api/v1/books?keyword=夏&genre={$genres->id}");

        $response->assertStatus(200);

        $this->assertCount(
            2,
            $response->json('data')
        );
    }

    public function test_book_index_sort(): void
    {
        $books = Book::orderBy('title');

        // title順に並び替え
        $response = $this->get('/api/v1/books?sort=title');

        $titles = collect($response->json('data'))
            ->pluck('title')
            ->values();

        $this->assertEquals(
            $titles->sort()->values()->toArray(),
            $titles->toArray()
        );

    }

    public function test_api_show(): void
    {
        $book = Book::with(['reviews', 'genres'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->first();

        $response = $this->get("/api/v1/books/{$book->id}");

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'message' => '書籍の詳細取得に成功しました',
        ]);

        $response->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'title',
                'author',
                'isbn',
                'published_date',
                'description',
                'image_url',
                'genres',
                'reviews' => [
                    '*' => [
                        'user_name',
                        'rating',
                        'comment',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ],
        ]);

        $response = $this->get('/api/v1/books/1000');
        $response->assertStatus(404);

        $response->assertJsonFragment([
            'message' => '書籍が存在しません',
        ]);
    }

    public function test_ap_i_store(): void
    {
        $user = User::first();
        $genre = Genre::first();

        $book = ([
            'title' => 'test',
            'author' => 'test',
            'isbn' => '0000000000000',
            'published_date' => '2026-06-12',
            'user_id' => $user->id,
            'genres' => [$genre->id],
        ]);

        $token = $user->createToken('test')->plainTextToken;
        $response = $this
            ->actingAs($user, 'sanctum')
            ->withHeaders([
                'Authorization' => 'Bearer '.$token,
                'Accept' => 'application/json',
            ])
            ->post('/api/v1/books', $book);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'message' => '書籍の登録に成功しました',
        ]);

        $this->assertDatabaseHas('books',
            ['isbn' => '0000000000000']);

        // 中間テーブルに保存されているか確認
        $book = Book::where('isbn', '0000000000000')->first();

        $this->assertDatabaseHas('book_genre',
            [
                'book_id' => $book->id,
                'genre_id' => $genre->id,
            ]);
    }

    public function test_ap_i_store_auth(): void
    {
        $genre = Genre::first();
        $user = User::first();

        $book = [
            'title' => 'test',
            'author' => 'test',
            'isbn' => '1111111111111',
            'published_date' => '2026-06-12',
            'genres' => [$genre->id],
        ];

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post('/api/v1/books', $book);

        $response->assertStatus(401);
    }

    public function test_api_update(): void
    {
        $user = User::with('books')->first();
        $book = $user->books()->first();

        $update_book = ([
            'title' => 'edited',
            'author' => $book->author,
            'isbn' => $book->isbn,
            'user_id' => $book->user_id,
            'published_date' => $book->published_date->format('Y-m-d'),
            'genres' => $book->genres->pluck('id')->toArray(),
        ]);

        $token = $user->createToken('test')->plainTextToken;
        $response = $this
            ->actingAs($user, 'sanctum')
            ->withHeaders([
                'Authorization' => 'Bearer '.$token,
                'Accept' => 'application/json',
            ])
            ->put("/api/v1/books/{$book->id}", $update_book);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'message' => '書籍の更新に成功しました',
        ]);

        $this->assertDatabaseHas('books', ['title' => 'edited']);

        $response = $this->get('/api/v1/books/2000');
        $response->assertStatus(404);

        $response->assertJsonFragment([
            'message' => '書籍が存在しません',
        ]);
    }

    public function test_api_update_auth(): void
    {
        $book = Book::first();
        $otherUser = User::where('id', '!=', $book->user_id)->first();

        $update_book = ([
            'title' => 'edited',
            'author' => $book->author,
            'isbn' => $book->isbn,
            'published_date' => $book->published_date->format('Y-m-d'),
            'genres' => $book->genres->pluck('id')->toArray(),
        ]);

        $response = $this
            ->actingAs($otherUser, 'sanctum')
            ->put("/api/v1/books/{$book->id}", $update_book);

        $response->assertStatus(403);
    }

    public function test_ap_i_delete(): void
    {
        $user = User::with('books')->first();
        $book = $user->books->first();

        $token = $user->createToken('test')->plainTextToken;

        $response = $this
            ->actingAs($user, 'sanctum')
            ->withHeaders([
                'Authorization' => 'Bearer '.$token,
                'Accept' => 'application/json',
            ])
            ->delete("/api/v1/books/{$book->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('books', ['id' => $book->id]);

        // お気に入り、レビュー、ジャンルの紐づけが削除されているか
        $this->assertDatabaseMissing('reviews', ['book_id' => $book->id]);
        $this->assertDatabaseMissing('favorites', ['book_id' => $book->id]);
        $this->assertDatabaseMissing('book_genre', ['book_id' => $book->id]);

        $response = $this->get('/api/v1/books/3000');
        $response->assertStatus(404);

        $response->assertJsonFragment([
            'message' => '書籍が存在しません',
        ]);
    }

    public function test_ap_i_delete_auth(): void
    {
        $book = Book::first();
        $otherUser = User::where('id', '!=', $book->user_id)->first();

        $response = $this
            ->actingAs($otherUser, 'sanctum')
            ->delete("/api/v1/books/{$book->id}");

        $response->assertStatus(403);
    }
}
