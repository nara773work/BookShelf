<?php

namespace Tests\Feature;

use App\Models\Book;
use Tests\TestCase;

class RankingControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_ranking(): void
    {
        $rankedbooks = Book::withAvg('reviews', 'rating')
            ->orderByDesc('reviews_avg_rating')
            ->take(10)
            ->get();

        $response = $this->get('/ranking');

        $response->assertViewIs('ranking.index');
        $response->assertStatus(200);

        for ($i = 0; $i < $rankedbooks->count() - 1; $i++) {
            $book = $rankedbooks->skip($i)->first();
            $nextbook = $rankedbooks->skip($i + 1)->first();

            $this->assertTrue($book->reviews_avg_rating >= $nextbook->reviews_avg_rating);
        }

        $this->assertCount(10, $rankedbooks);
    }

    public function test_favorite_ridirect(): void
    {
        $response = $this->get('/favorites');
        $response->assertRedirect('/login');
    }
}
