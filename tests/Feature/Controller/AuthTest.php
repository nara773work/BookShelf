<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

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

        $response->assertSee('名前');
        $response->assertSee('メール');
        $response->assertSee('パスワード');
        $response->assertSee('パスワード確認');

        $user = [
            'name' => 'test',
            'email' => 'test@co.com',
            'password' => 'testpass',
            'password_confirmation' => 'testpass',
        ];
        $response = $this->post('/register',$user);       
        $response->assertRedirect('/books');
        $this->assertDatabaseHas('users', ['name' => 'test']);
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
            'password'=>'password'
        ];
        $response = $this->post('/login',$login_user);
        $response->assertRedirect('/books');
    }   

    
    
}
