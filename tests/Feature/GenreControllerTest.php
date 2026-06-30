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
        $response = $this->actingAs($user)->get('/genres');
        $response->assertStatus(200);
        $response->assertViewIs('genres.index');
    }

    public function test_Genre_show(): void
    {
        $user = User::first();
        $genre = Genre::first();

        $response = $this->actingAs($user)->get("/genres/{$genre->id}");
        $response->assertStatus(200);
        $response->assertViewIs('genres.show');
    }

    public function test_Genre_create(): void
    {
        $user = User::first();

        $response = $this->actingAs($user)->get("/genres/create");
        $response->assertStatus(200);
        $response->assertViewIs('genres.create');
    }

    public function test_Genre_store(): void
    {
        $user = User::first();
        $genre = ([
            'name' => 'test',
        ]);

        $response = $this->actingAs($user)
        ->post("/genres/create",$genre);

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
