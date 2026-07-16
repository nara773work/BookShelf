<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    protected $seed = true;

    public function test_register(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);

        // 入力フォームが表示されているか
        $response->assertSee('名前');
        $response->assertSee('メール');
        $response->assertSee('パスワード');
        $response->assertSee('パスワード確認');

        $user = [
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => 'testpass',
            'password_confirmation' => 'testpass',
        ];

        $response = $this->post('/register', $user);

        $response->assertRedirect('/books');
        $this->assertDatabaseHas('users', [
            'name' => 'test',
            'email' => 'test@test.com',
        ]);

        $user = User::where('email', 'test@test.com')->first();

        $this->assertTrue(
            Hash::check('testpass', $user->password)
        );
    }

    public function test_login(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $response->assertSee('メール');
        $response->assertSee('パスワード');

        $user = User::first();
        $login_user = [
            'email' => 'yamada@example.com',
            'password' => 'password',
        ];
        $response = $this->post('/login', $login_user);
        $response->assertRedirect('/books');
    }

    public function test_logout(): void
    {
        $user = User::first();

        $response = $this->actingAs($user)
            ->post('/logout');

        $response->assertRedirect('/login');

        $this->assertGuest();
    }
}
