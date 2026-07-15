<?php

namespace Tests\Feature\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;

class ApiAuthControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;

    protected $seed = true;

    public function test_login_succsess(): void
    {
        $user = User::first();

        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'token'
        ]);
    }

    public function test_logout_success(): void
    {
        $user = User::first();

        // トークン作成
        $token = $user->createToken('test-token')->plainTextToken;


        $response = $this
            ->withHeaders([
                'Authorization' => 'Bearer '.$token,
                'Accept' => 'application/json',
            ])
            ->postJson('/api/v1/logout');


        $response->assertStatus(200);


        // トークン削除確認
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'name' => 'test-token'
        ]);
    }

}
