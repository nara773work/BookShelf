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
    public function test_ReadingPlan_index(): void
    {
        $user = User::first();

        $response = $this
        ->actingAs($user)
        ->get('/reading-plans');

        $response->assertStatus(200);
        $response->assertViewIs('reading-plans.index');

    }

    public function test_ReadingPlan_index_fillter(): void{
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

    public function test_ReadingPlan_complete(): void
    {
        $user = User::first();
        $plan = ReadingPlan::where('status',ReadingPlanStatus::Reading->value)->first();

        $response = $this
        ->actingAs($user)
        ->post("/reading-plans/{$plan->id}/complete");

        $response->assertRedirect('/reading-plans');

        $this->assertDatabaseHas('reading_plans', [
            'id' => $plan->id,    
            'status' => ReadingPlanStatus::Completed->value
            ]);
    }

    public function test_ReadingPlan_create(): void
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

    public function test_ReadingPlan_store(): void
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

    public function test_ReadingPlan_edit(): void
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

    public function test_ReadingPlan_update(): void
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

    public function test_ReadingPlan_delete(): void
    {
        $user = User::first();
        $plan = ReadingPlan::where('user_id', $user->id)->first();

        $response = $this->actingAs($user)->delete("/reading-plans/{$plan->id}");
        $response->assertRedirect('/reading-plans');

        $this->assertDatabaseMissing('reading_plans', [
            'id' => $plan->id
        ]);
        
    }
}
