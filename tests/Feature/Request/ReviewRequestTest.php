<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Genre;
use App\Models\User;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

        $response->assertSessionHasErrors(['rating']);
    }

    public function test_max_length_validation(): void
    {
        $user = User::first();
        $book = Book::first();
        // comment 256文字
        $data = [
            'rating' => 3,
            'comment' => str_repeat('a',256 ),
        ];

        $response = $this->actingAs($user)
            ->post("/books/{$book->id}/reviews", $data);

        $response->assertSessionHasErrors(['comment']);
    
}

}