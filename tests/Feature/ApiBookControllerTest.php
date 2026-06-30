<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use App\Models\Genre;

class ApiBookControllerTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;

    public function test_API_index(): void
    {
        $response = $this->get('/api/v1/books');
        $response->assertStatus(200);
    }

    public function test_API_show(): void
    {
        $book = Book::first();

        $response = $this->get("/api/v1/books/{$book->id}");
        $response->assertStatus(200);
    }

    public function test_API_store(): void
    {
        $user = User::first();
        $genre = Genre::first();

        $book = ([
            'title' => 'test',
            'author' => 'test',
            'isbn' => '0000000000000',
            'published_date' => '2026-06-12',
            'user_id' => $user->id,
            'genres' => [$genre->id]
        ]);

        $response = $this->actingAs($user)
        ->post("/api/v1/books/",$book);

        $response->assertStatus(201);
      
        $this->assertDatabaseHas('books',
         ['isbn' => 0000000000000]);
        
        //中間テーブルに保存されているか確認
        $book = Book::where('isbn', '0000000000000')->first();

        $this->assertDatabaseHas('book_genre', 
        [
            'book_id' => $book->id,
            'genre_id' => $genre->id
        ]);
    }

    public function test_API_update(): void
    {
        $user = User::first();
        $book = Book::first();

        $update_book = ([
            'title' => 'edited',
            'author' => $book->author,
            'isbn' => $book->isbn,
            'user_id' => $book->user_id,
            'published_date' => $book->published_date,
            'genres'=> $book->genres
        ]);

        $response = $this->actingAs($user)
        ->put("/api/v1/books/{$book->id}",$update_book);

        $response->assertStatus(200);

        $this->assertDatabaseHas('books', ['title' => 'edited']);
    }

    public function test_API_delete(): void
    {
        $user = User::first();
        $book = Book::first();
    
        $response = $this->actingAs($user)
        ->delete("/api/v1/books/{$book->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('books', ['id' => $user->id]);
    }
}
