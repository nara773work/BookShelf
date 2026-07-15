<?php

namespace Tests\Feature\Policy;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Book;

class BookControllertest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;
    protected $seed = true;
    
    public function test_owuner_can_update(): void
    {
        $user = User::first();

        $book = Book::where('user_id', $user->id)->firstOrFail();

        $data = [
            'title' => 'edited',
            'author' => $book->author,
            'isbn' => $book->isbn,
            'published_date' => $book->published_date,
            'description' => $book->description,
            'image_url' => $book->image_url,
        ];

        $response = $this->actingAs($user)
        ->put("/books/{$book->id}", $data);

        $response->assertStatus(302); 
    }

    public function test_other_user_cannot_update(): void
{
    $book = Book::firstOrFail();

    $otherUser = User::where('id', '!=', $readingPlan->user_id)
    ->firstOrFail();

    $data = [
        'title' => 'edited',
        'author' => $book->author,
        'isbn' => $book->isbn,
        'published_date' => $book->published_date,
        'description' => $book->description,
        'image_url' => $book->image_url,
    ];

    $response = $this->actingAs($otherUser)
        ->put("/books/{$book->id}", $data);

    $response->assertForbidden(); // = assertStatus(403)
}

public function test_other_user_can_delete(): void
{
    $book = Book::where('user_id', $user->id)
    ->firstOrFail();

    $response = $this->actingAs($otherUser)
        ->delete("/books/{$book->id}");

    $response->assertForbidden();
}

    public function test_other_user_cannot_delete(): void
{
    $readingPlan = ReadingPlan::firstOrFail();

    $otherUser = User::where('id', '!=', $readingPlan->user_id)->firstOrFail();

    $response = $this->actingAs($otherUser)
        ->delete("/books/{$book->id}");

    $response->assertForbidden();
}
}
