<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    protected $seed = true;

    public function test_review_store(): void
    {
        $book = Book::first();
        $user = User::first();

        $comments = [
            1 => '満足できませんでした',
            2 => 'あまり満足できませんでした',
            3 => '普通でした',
            4 => '満足しました',
            5 => 'とても満足しました',
        ];

        $rating = rand(1, 5);

        $review = [
            'rating' => 3,
            'book_id' => $book->id,
        ];

        $response = $this->actingAs($user)
            ->post("/books/{$book->id}/reviews", $review);

        $response->assertRedirect("/books/{$book->id}");

        $this->assertDatabaseHas('reviews', [
            'rating' => 3,
            'comment' => '普通でした',
            'user_id' => $user->id,
            'book_id' => $book->id]);

        $response->assertSessionHas('success', 'レビューを登録しました');
    }

    public function test_review_store_redirect(): void
    {
        $book = Book::first();
        $review = [
            'rating' => 3,
            'book_id' => $book->id,
        ];

        $response = $this
            ->post("/books/{$book->id}/reviews", $review);
        $response->assertRedirect('/login');
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

    public function test_toggle_redirect(): void
    {
        $review = Review::first();

        $response = $this->post("/reviews/{$review->id}/like");
        $response->assertRedirect('/login');
    }

    public function test_review_edit(): void
    {
        $review = Review::first();
        $user = $review->user;

        $response = $this->actingAs($user)
            ->get("/reviews/{$review->id}/edit");

        $response->assertSee($review->rating);
        $response->assertSee($review->comment);

        $response->assertStatus(200);
        $response->assertViewIs('reviews.edit');
    }

    public function test_review_update(): void
    {
        $review = Review::first();
        $user = $review->user;
        $book = $review->book;

        $update_review = ([
            'rating' => 4,
            'book_id' => $book->id,
        ]);

        $response = $this->actingAs($user)
            ->put("/reviews/{$review->id}", $update_review);

        $response->assertRedirect("/books/{$book->id}");

        $this->assertDatabaseHas('reviews', [
            'rating' => 4,
            'comment' => '満足しました',
            'user_id' => $user->id,
            'book_id' => $book->id]);

        $response->assertSessionHas('success', 'レビューを更新しました');
    }

    public function test_review_delete(): void
    {
        $review = Review::with('likedByUsers')->first();
        $user = $review->user;
        $book = $review->book;

        $response = $this->actingAs($user)
            ->delete("/reviews/{$review->id}");

        $response->assertRedirect("/books/{$book->id}");

        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
        $this->assertDatabaseMissing('review_likes', ['review_id' => $review->id]);

        $response->assertSessionHas('success', 'レビューを削除しました');
    }
}
