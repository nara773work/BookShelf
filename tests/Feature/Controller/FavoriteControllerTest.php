<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Favorite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    protected $seed = true;

    public function test_favorite(): void
    {
        $user = User::with('favoritebooks')->first();
        $favorite_books = $user->favoritebooks->first();

        $response = $this->actingAs($user)->get('/favorites');

        $response->assertViewIs('favorites.index');
        $response->assertStatus(200);

        $response->assertSee($favorite_books->title);
        $response->assertSee($favorite_books->author);
    }

    public function test_favorite_ridirect(): void
    {
        $response = $this->get('/favorites');
        $response->assertRedirect('/login');
    }

    public function test_favorite_paginate(): void
    {

        $user = User::with('favoritebooks')->first();
        $books = Book::factory()->count(12)->create();

        $favorites = Favorite::factory()
            ->count(12)
            ->create([
                'user_id' => $user->id,
            ]);

        $response = $this->actingAs($user)->get('/favorites');

        $response->assertViewHas('books', function ($favorites) {
            return $favorites->count() === 10;
        });

        $response = $this->actingAs($user)->get('/favorites?page=2');
        $response->assertViewHas('books', function ($favorites) {
            return $favorites->count() > 4; // シーダーで最低4件のお気に入りをつけているため
        });

        $response = $this->actingAs($user)->get('/favorites');
    }
}
