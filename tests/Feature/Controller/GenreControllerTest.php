<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Genre;
use App\Models\User;
use App\Models\Book;

class GenreControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    protected $seed = true;

    public function test_Genre_index(): void
    {
        $user = User::first();
        $genres = Genre::withCount('books')->first();
        $books = $genres->books_count;

        $response = $this->actingAs($user)->get('/genres');
        $response->assertStatus(200);

        $response->assertSee($genres->name);
        $response->assertSee($books);
        $response->assertViewIs('genres.index');
    }

    public function test_Genre_index_ridirect(): void{
        $response = $this->get('/genres');
        $response->assertRedirect('/login');
    }

    public function test_Genre_show(): void
    {
        $user = User::first();
        $genre = Genre::with('books')->first();
        $books = $genre->books->first();

        $response = $this->actingAs($user)->get("/genres/{$genre->id}");
        $response->assertStatus(200);
        $response->assertViewIs('genres.show');
        $response->assertSee($genre->name);
        $response->assertSee($books->title);
        $response->assertSee($books->author);
    }

    public function test_Genre_create(): void
    {
        $user = User::first();

        $response = $this->actingAs($user)->get("/genres/create");
        $response->assertStatus(200);
        $response->assertViewIs('genres.create');

        $response->assertSee('ジャンル名');
    }

    public function test_Genre_create_redirect(): void{
        $response = $this->get('/genres/create');
        $response->assertRedirect('/login');
    }

    public function test_Genre_store(): void
    {
        $user = User::first();
        $genre = ([
            'name' => 'test',
        ]);

        $response = $this->actingAs($user)
        ->post("/genres",$genre);

        $response->assertRedirect('/genres');
        $response->assertStatus(302);
      
        $this->assertDatabaseHas('genres', ['name' => 'test']);
    }

    public function test_Genre_edit(): void
    {
        $user = User::first();
        $genre = Genre::first();

        $response = $this->actingAs($user)
        ->get("/genres/{$genre->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('genres.edit');

        $response->assertSee($genre->name);
        $response->assertSee($genre->comment);
    }

    public function test_Genre_edit_ridirect(): void{
        $response = $this->get("/genres/{$genre->id}/edit");
        $response->assertRedirect('/login');
    }

    public function test_Genre_update(): void
    {
        $user = User::first();
        $genre = Genre::first();

        $update_genre = ([
            'name' => 'edited',
        ]);

        $response = $this->actingAs($user)
        ->put("/genres/{$genre->id}",$update_genre);

        $response->assertStatus(302);
        $response->assertRedirect('/genres');

        $this->assertDatabaseHas('genres', ['name' => 'edited']);
    }

    public function test_Genre_cannot_delete(): void
    {
        $book = Book::all();
        $user = User::first();
        $genre = Genre::whereHas('books')->first();
    
        $response = $this->actingAs($user)
        ->delete("/genres/{$genre->id}");

        $this->assertDatabaseHas('genres', ['id' => $genre->id]);
        
        $response->assertStatus(302);
        $response->assertRedirect('/genres');
    }

    public function test_Genre_can_delete(): void
    {
        $book = Book::all();
        $user = User::first();
        $genre = Genre::whereDoesntHave('books')->first();

        $response = $this->actingAs($user)
        ->delete("/genres/{$genre->id}");
    
        $this->assertDatabaseMissing('genres', ['id' => $genre->id]);
        
        $response->assertStatus(302);
        $response->assertRedirect('/genres');
    }
}
