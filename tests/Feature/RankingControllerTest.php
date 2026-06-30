<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RankingControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_ranking(): void
    {
        $response = $this->get('/ranking');

        $response->assertViewIs('ranking.index');
        $response->assertViewHas('rankedBooks');
        $response->assertStatus(200);

    }
}
