<?php

namespace Tests\Feature\Policy;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookControllertest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    protected $seed = true;

    public function test_owuner_can_edit_view(): void
    {
        $user = User::first();

        $review = Review::where('user_id', $user->id)->firstOrFail();

        $response = $this->actingAs($user)
            ->get("/reviews/{$review->id}/edit");

        $response->assertStatus(200);
    }

    public function test_other_user_cannot_edit_view(): void
    {
        $review = Review::where('user_id', $user->id)->firstOrFail();

        $otherUser = User::where('id', '!=', $readingPlan->user_id)
            ->firstOrFail();

        $response = $this->actingAs($user)
            ->get("/reviews/{$review->id}/edit");

        $response->assertForbidden(); // = assertStatus(403)
    }

    public function test_owuner_can_update(): void
    {
        $user = User::first();
        $review = Review::where('user_id', $user->id)->firstOrFail();

        $data = [
            'rating' => 3,
            'comment' => 'testcontents_edited',
            'book_id' => $book->id,
        ];

        $response = $this->actingAs($user)
            ->put("/books/{$book->id}", $data);

        $response->assertStatus(302);
    }

    public function test_other_user_cannot_update(): void
    {
        $review = Review::firstOrFail();

        $otherUser = User::where('id', '!=', $review->user_id)
            ->firstOrFail();

        $data = [
            'rating' => 3,
            'comment' => 'testcontents_edited',
            'book_id' => $book->id,
        ];

        $response = $this->actingAs($otherUser)
            ->put("/books/{$book->id}", $data);

        $response->assertForbidden(); // = assertStatus(403)
    }

    public function test_other_user_can_delete(): void
    {
        $user = User::first();
        $review = Review::where('user_id', $user->id)->firstOrFail();

        $response = $this->actingAs($otherUser)
            ->delete("/books/{$book->id}");

        $response->assertForbidden();
    }

    public function test_other_user_cannot_delete(): void
    {
        $review = Review::firstOrFail();

        $otherUser = User::where('id', '!=', $review->user_id)->firstOrFail();

        $response = $this->actingAs($otherUser)
            ->delete("/books/{$book->id}");

        $response->assertForbidden();
    }
}
