<?php

namespace Tests\Feature\Controller;

use App\Enums\ReadingPlanStatus;
use App\Models\Book;
use App\Models\ReadingPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadingPlanControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    protected $seed = true;

    public function test_reading_plan_index(): void
    {
        $user = User::first();

        $response = $this
            ->actingAs($user)
            ->get('/reading-plans');

        $response->assertStatus(200);
        $response->assertViewIs('reading-plans.index');
    }

    public function test_reading_plan_index_redirect(): void
    {
        $response = $this
            ->get('/reading-plans');

        $response->assertRedirect('/login');

    }

    public function test_reading_plan_index_fillter(): void
    {
        $user = User::first();

        $response = $this
            ->actingAs($user)
            ->get('/reading-plans?status=reading');

        $plans = $response->viewData('readingPlans');

        $response->assertSee('読書中');

        foreach ($plans as $plan) {
            $this->assertEquals(
                ReadingPlanStatus::Reading->value,
                $plan->status->value
            );
        }
    }

    public function test_reading_plan_complete(): void
    {
        $user = User::first();
        $plan = ReadingPlan::where('status', ReadingPlanStatus::Reading->value)->first();

        $response = $this
            ->actingAs($user)
            ->post("/reading-plans/{$plan->id}/complete");

        $response->assertRedirect('/reading-plans');

        $this->assertDatabaseHas('reading_plans', [
            'id' => $plan->id,
            'status' => ReadingPlanStatus::Completed->value,
        ]);
    }

    public function test_reading_plan_create(): void
    {
        $user = User::first();
        $book = Book::first();

        $response = $this
            ->actingAs($user)
            ->get('/reading-plans/create');

        $response->assertStatus(200);
        $response->assertViewIs('reading-plans.create');

        $response->assertSee($book->title);
    }

    public function test_reading_plan_create_ridairect(): void
    {
        $book = Book::first();

        $response = $this
            ->get('/reading-plans/create');

        $response->assertRedirect('/login');
    }

    public function test_reading_plan_store(): void
    {
        $user = User::first();
        $book = Book::where('id', 11)->first();

        $readingPlan = ([
            'book_id' => $book->id,
            'target_date' => Carbon::today()->addDays(3)->format('Y-m-d'),
        ]);

        $response = $this->actingAs($user)
            ->post('/reading-plans', $readingPlan);

        $response->assertRedirect('/reading-plans');

        $this->assertDatabaseHas('reading_plans',
            [
                'user_id' => $user->id,
                'book_id' => $book->id,
                'target_date' => Carbon::today()->addDays(3)->format('Y-m-d'),
                'status' => ReadingPlanStatus::Reading->value, ]);
    }

    public function test_reading_plan_edit(): void
    {
        $user = User::first();
        $plan = ReadingPlan::where('user_id', $user->id)->first();

        $response = $this
            ->actingAs($user)
            ->get("/reading-plans/{$plan->id}/edit");

        $response->assertViewIs('reading-plans.edit');

        $response->assertSee(
            $plan->target_date->format('Y-m-d')
        );
    }

    public function test_reading_plan_update(): void
    {
        $user = User::first();
        $readingPlan = ReadingPlan::where('user_id', $user->id)->first();

        $update_plan = ([
            'user_id' => $readingPlan->user_id,
            'book_id' => $readingPlan->book_id,
            'target_date' => Carbon::today()->addDays(10),
            'status' => ReadingPlanStatus::Reading->value,
        ]);

        $response = $this
            ->actingAs($user)
            ->put("/reading-plans/{$readingPlan->id}", $update_plan);

        $response->assertRedirect('/reading-plans');

        $this->assertDatabaseHas('reading_plans', ['target_date' => Carbon::today()->addDays(10)]);

    }

    public function test_reading_plan_delete(): void
    {
        $user = User::first();
        $plan = ReadingPlan::where('user_id', $user->id)->first();

        $response = $this->actingAs($user)->delete("/reading-plans/{$plan->id}");
        $response->assertRedirect('/reading-plans');

        $this->assertDatabaseMissing('reading_plans', [
            'id' => $plan->id,
        ]);

    }
}
