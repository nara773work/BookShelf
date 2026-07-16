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

    public function test_owuner_can_complete(): void
    {
        $user = User::first();

        $readingplan = ReadingPlan::where('user_id', $user->id)
            ->where('status', ReadingPlanStatus::Reading->value)
            ->firstOrFail();

        $data = [
            'status' => ReadingPlanStatus::complete->value,
        ];

        $response = $this->actingAs($user)
            ->put("/books/{$book->id}", $data);

        $response->assertStatus(302);
    }

    public function test_owuner_can_update(): void
    {
        $user = User::first();

        $readingPlan = ReadingPlan::where('user_id', $user->id)->firstOrFail();

        $data = [
            'target_date' => Carbon::today()->addDays(100),
        ];

        $response = $this->actingAs($user)
            ->put("/books/{$book->id}", $data);

        $response->assertStatus(302);
    }

    public function test_other_user_cannot_update(): void
    {
        $otherUser = User::where('id', '!=', $readingPlan->user_id)
            ->firstOrFail();

        $readingPlan = ReadingPlan::where('user_id', $user->id)->firstOrFail();

        $data = [
            'target_date' => Carbon::today()->addDays(100),
        ];

        $response = $this->actingAs($otherUser)
            ->put("/books/{$book->id}", $data);

        $response->assertForbidden(); // = assertStatus(403)
    }

    public function test_other_user_can_delete(): void
    {
        $readingPlan = ReadingPlan::where('user_id', $user->id)->firstOrFail();

        $otherUser = User::where('id', '!=', $readingPlan->user_id)
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
