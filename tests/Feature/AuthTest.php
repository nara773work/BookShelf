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

        $user = User::first();
        $login_user = [
            'email' => 'yamada@example.com',
            'password'=>'password'
        ];
        $response = $this->post('/login',$login_user);
        $response->assertRedirect('/books');
    }    
}
