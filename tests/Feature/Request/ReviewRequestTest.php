<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewRequestTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_required_validation_errors(): void
    {
        $user = User::first();
        $book = Book::first();
        $data = [
            'rating' => '',
        ];

        $response = $this->actingAs($user)
            ->post("/books/{$book->id}/reviews", $data);

        $response->assertSessionHasErrors([
            'rating',
        ]);
    }

    public function test_rating_is_between_1_and_5()
    {
        $user = User::first();
        $book = Book::first();

        // 0点の場合
        $response = $this->actingAs($user)
            ->post("/books/{$book->id}/reviews", [
                'rating' => 0,
                'comment' => 'テストレビュー',
            ]);

        $response->assertSessionHasErrors('rating');

        // 6点の場合
        $response = $this->actingAs($user)
            ->post("/books/{$book->id}/reviews", [
                'rating' => 6,
                'comment' => 'テストレビュー',
            ]);

        $response->assertSessionHasErrors('rating');
    }

    public function test_max_length_validation(): void
    {
        $user = User::first();
        $book = Book::first();
        // comment 256文字
        $data = [
            'rating' => 3,
            'comment' => str_repeat('a', 256),
        ];

        $response = $this->actingAs($user)
            ->post("/books/{$book->id}/reviews", $data);

        $response->assertSessionHasErrors([
            'comment',
        ]);

    }
}
