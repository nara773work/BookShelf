<?php

namespace Tests\Feature\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Enums\ReadingPlanStatus;
use App\Models\ReadingPlan;

class ReportControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_report_index(): void
    {
        $user = User::withCount('reviews')
        ->withAvg('reviews','rating')
        ->withCount([
            'readingPlans as completed_count' => function ($query) {
                $query->where('status', ReadingPlanStatus::Completed->value);
            }
        ])->first();

        $reviews = $user->reviews_count;
        $completed = $user->completed_count;
        $Avg = $user->reviews_avg_rating;

        $response = $this->actingAs($user)
        ->get("/reports");

        $response->assertStatus(200);
        $response->assertViewIs('reports.index');

        // 総レビュー数、読了冊数（ユニーク書籍数）、平均評価点が表示されている
        $response->assertSee($reviews);
        $response->assertSee($completed);
        $response->assertSee(number_format($Avg, 1));

        //5段階であることを評価
        $response->assertViewHas('stats');
        $stats = $response->viewData('stats');
        $this->assertCount(5, $stats['rating_distribution']);

        //4星以上の書籍を評価の高い順に最大5件表示
        $books = $stats['top_rated_books'];
        foreach ($books as $book) {
            $this->assertGreaterThanOrEqual(4, $book['rating']);
            $response->assertSee($book['title']);
            $response->assertSee($book['author']);
        }
         for ($i = 0; $i < count($books) - 1; $i++) {
            $this->assertGreaterThanOrEqual(
                $books[$i + 1]['rating'],
                $books[$i]['rating']
            );
        }
        $this->assertLessThanOrEqual(5, count($books));

        //ジャンル別評価TOP5
        $genres = $stats['genre_ratings'];

        $this->assertLessThanOrEqual(5, count($genres));

        foreach ($genres as $genre) {
            $response->assertSee($genre['name']);
        }

        for ($i = 0; $i < count($genres) - 1; $i++) {
            $this->assertGreaterThanOrEqual(
                $genres[$i + 1]['average_rating'],
                $genres[$i]['average_rating']
            );
        }
    }

    public function test_Report_ridirect(): void{
        $response = $this->get('/reports');
        $response->assertRedirect('/login');
    }
}
