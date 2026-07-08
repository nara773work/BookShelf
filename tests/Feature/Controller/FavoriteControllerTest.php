<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class FavoriteControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;
    protected $seed = true;

    public function test_Favorite(): void
    {
        $user = User::with('favoritebooks')->first();
        $favorite_books = $user->favoritebooks->first();

        $response = $this->actingAs($user)->get('/favorites');

        $response->assertViewIs('favorites.index');
        $response->assertStatus(200);

        $response->assertSee($favorite_books->title);
        $response->assertSee($favorite_books->author);
    }

    public function test_Favorite_ridirect(): void{
        $response = $this->get('/favorites');
        $response->assertRedirect('/login');
    }
}
