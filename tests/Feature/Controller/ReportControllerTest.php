<?php

namespace Tests\Feature\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ReportControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_index(): void
    {
        $user = User::first();
        $response = $this->actingAs($user)
        ->get("/reports");

        $response->assertStatus(200);
        $response->assertViewIs('reports.index');
    }
}
