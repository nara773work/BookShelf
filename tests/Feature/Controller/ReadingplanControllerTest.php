<?php

namespace Tests\Feature\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Book;
use App\Enums\ReadingPlanStatus;
use App\Models\ReadingPlan;

class ReadingplanControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_index(): void
    {
        $user = User::first();

        $response = $this
        ->actingAs($user)
        ->get('/reading-plans');

        $response->assertStatus(200);
        $response->assertViewIs('reading-plans.index');
    }

    public function test_complete(): void
    {
        $user = User::first();
        $plan = ReadingPlan::first();

        $response = $this
        ->actingAs($user)
        ->post("/reading-plans/{$plan->id}/complete");

        $response->assertRedirect('/reading-plans');
    }

    public function test_create(): void
    {
        $user = User::first();

        $response = $this
        ->actingAs($user)
        ->get('/reading-plans/create');

        $response->assertStatus(200);
        $response->assertViewIs('reading-plans.create');
    }

    public function test_store(): void
    {
        $user = User::first();
        $book = Book::where('user_id',1)->first();

        $readingPlan = ([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => Carbon::today()->addDays(3),
            'status' => ReadingPlanStatus::Reading->value,
        ]);

        $response = $this->actingAs($user)
        ->post("/reading-plans",$readingPlan);

        $response->assertRedirect('/reading-plans');
      
        $this->assertDatabaseHas('reading_plans',
         [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => Carbon::today()->addDays(3),
            'status' => ReadingPlanStatus::Reading->value,]);
    }

    public function test_edit(): void
    {
        $user = User::first();
        $plan = ReadingPlan::where('user_id', $user->id)->first();

        $response = $this
        ->actingAs($user)
        ->get("/reading-plans/{$plan->id}/edit");

        $response->assertViewIs('reading-plans.edit');
    }

    public function test_update(): void
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
        ->put("/reading-plans/{$readingPlan->id}",$update_plan);

        $response->assertRedirect('/reading-plans');

        $this->assertDatabaseHas('reading_plans', ['target_date' => Carbon::today()->addDays(10)]);
        
    }

    public function test_delete(): void
    {
        $user = User::first();
        $plan = ReadingPlan::where('user_id', $user->id)->first();

        $response = $this->actingAs($user)->delete("/reading-plans/{$plan->id}");
        $response->assertRedirect('/reading-plans');
        
    }
}
