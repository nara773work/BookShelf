<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GenreRequestTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;

    public function test_required_validation_errors(): void
    {
        $user = User::first();

        $data = [
            'name' => '',
        ];

        $response = $this->actingAs($user)
            ->post('/genres', $data);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_max_length_validation(): void
    {
        $user = User::first();

        // name 51文字
        $data = [
            'name' => str_repeat('a', 51),
        ];

        $response = $this->actingAs($user)
            ->post('/genres', $data);

        $response->assertSessionHasErrors(['name']);
    }  
}