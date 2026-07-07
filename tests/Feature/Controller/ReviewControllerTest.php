<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Review;
use App\Models\User;
use App\Models\Book;

class ReviewControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    protected $seed = true;

    public function test_Review_store(): void
    {
        $book = Book::first();
        $user = User::first();

        $review = [
            'rating' => 3,
            'comment' => 'test',
            'book_id' => $book->id
        ];

        $response = $this->actingAs($user)
        ->post("/books/{$book->id}/reviews",$review);

        $response->assertRedirect("/books/{$book->id}");
      
        $this->assertDatabaseHas('reviews', [
            'rating' => 3,
            'comment' => 'test',
            'user_id' => $user->id,
            'book_id' => $book->id]);
    }

    public function test_toggle(): void
    {
        $review = Review::first();
        $book = $review->book;
        $user = $review->user;

        $response = $this->actingAs($user)
        ->post("/reviews/{$review->id}/like");

        $response->assertRedirect();

        $this->assertDatabaseHas('review_likes', [
        'user_id' => $user->id,
        'review_id' => $review->id,
        ]);

        $this->actingAs($user)
        ->post("/reviews/{$review->id}/like");

        $response->assertRedirect();
        
        $this->assertDatabaseMissing('review_likes', [
        'user_id' => $user->id,
        'review_id' => $review->id,
        ]);
    
    }

    public function test_Review_edit(): void
    {
        $review = Review::first();
        $user = $review->user;

        $response = $this->actingAs($user)
        ->get("/reviews/{$review->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('reviews.edit');
    }

    public function test_Review_update(): void
    {
        $review = Review::first();
        $user = $review->user;
        $book = $review->book;
        
        $update_review = ([
            'rating' => 4,
            'comment' => 'test',
            'book_id' => $book->id
        ]);

        $response = $this->actingAs($user)
        ->put("/reviews/{$review->id}",$update_review);

        $response->assertRedirect("/books/{$book->id}");

        $this->assertDatabaseHas('reviews', [
            'rating' => 4,
            'comment' => 'test',
            'user_id' => $user->id,
            'book_id' => $book->id]);
    }

    public function test_Review_delete(): void
    {
        $review = Review::first();
        $user = $review->user;
        $book = $review->book;
       
        $response = $this->actingAs($user)
        ->delete("/reviews/{$review->id}");

        $response->assertRedirect("/books/{$book->id}");

        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }
}
