<?php

namespace Tests\Feature\Request;

use App\Models\Book;
use App\Models\ReadingPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadingPlantest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    protected $seed = true;

    public function test_required_validation_post_date(): void
    {
        $user = User::first();
        $book = Book::first();

        $data = [
            'book_id' => $book->id,
            'target_date' => Carbon::yesterday(),
        ]; // postのときだけ過去の日付は選べない

        $response = $this->actingAs($user)
            ->post('/readingPlans', $data);

        $response->assertSessionHasErrors([
            'target_date',
        ]);
    }

    public function test_required_validation_post_book(): void
    {
        $user = User::first();

        $data = [
            'book_id' => '',
            'target_date' => Carbon::today()->addDays(3),
        ];

        $response = $this->actingAs($user)
            ->post('/readingPlans', $data);

        $response->assertSessionHasErrors([
            'book_id',
        ]);
    }

    public function test_required_validation_put_date(): void
    {
        $readingPlan = ReadingPlan::first();
        $user = User::first();
        $book = Book::first();

        $data = [
            'book_id' => $book->id,
            'target_date' => Carbon::yesterday(),
        ];

        $response = $this->actingAs($user)
            ->put("/readingPlans/{$readingPlan->id}", $data);

        $response->assertSessionHasErrors([
            'target_date',
        ]);
    }
}
