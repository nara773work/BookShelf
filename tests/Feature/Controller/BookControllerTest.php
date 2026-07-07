<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use App\Models\Genre;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;

    public function test_Book_index(): void
    {
        $response = $this->get('/books');
        $response->assertStatus(200);
        $response->assertViewIs('books.index');
    }

    public function test_Book_show(): void
    {
        $book = Book::first();

        $response = $this->get("/books/{$book->id}");
        $response->assertStatus(200);
        $response->assertViewIs('books.show');
    }

    public function test_Book_create(): void
    {
        $user = User::first();

        $response = $this->actingAs($user)->get("/books/create");
        $response->assertStatus(200);
        $response->assertViewIs('books.create');
    }

    public function test_Book_store(): void
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
        ->post("/books/create",$book);

        $response->assertRedirect('/books');
      
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

    public function test_toggle(): void
    {
        $user = User::first();
        $book = Book::whereDoesntHave('favoritebooks',function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->first();

        $response = $this->actingAs($user)
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

    public function test_Book_edit(): void
    {
        
        $user = User::first();
        $book = Book::where('user_id',1)->first();

        $response = $this->actingAs($user)
        ->get("/books/{$book->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('books.edit');
    }

    public function test_Book_update(): void
    {
        $user = User::first();
        $book = Book::where('user_id',1)->first();

        $update_book = ([
            'title' => 'edited',
            'author' => $book->author,
            'isbn' => $book->isbn,
            'user_id' => $book->user_id,
            'published_date' => $book->published_date,
            'genres'=> $book->genres
        ]);

        $response = $this->actingAs($user)
        ->put("/books/{$book->id}",$update_book);

        $response->assertRedirect('/books');

        $this->assertDatabaseHas('books', ['title' => 'edited']);
    }

    public function test_Book_delete(): void
    {
        $user = User::first();
        $book = Book::where('user_id',1)->first();
    
        $response = $this->actingAs($user)
        ->delete("/books/{$book->id}/delete");

        $response->assertRedirect('/books');

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }
}
