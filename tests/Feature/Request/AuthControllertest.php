<?php

namespace Tests\Feature\Request;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllertest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_register_validate_requierd(): void
    {   //必須テスト
        $response = $this->get('/register');
        $response->assertStatus(200);

        $user = [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ];

        $response = $this->post('/register',$user);

        $response->assertSessionHasErrors([
            'name' => ['名前を入力してください'],
        ]);
        $response->assertSessionHasErrors([
            'email' => ['メールアドレスを入力してください'],
        ]);
        $response->assertSessionHasErrors([
            'password' => ['パスワードを入力してください'],
        ]);
    }

    public function test_register_validate_max(): void{
        //上限ギリギリ
        $response = $this->get('/register');
        $response->assertStatus(200);
        
        $user = [
            'name' => str_repeat('a', 50),
            'email' => str_repeat('a', 100),
            'password' => str_repeat('a', 20),
            'password_confirmation' => str_repeat('a', 20),
        ];

        $response = $this->post('/register',$user);
        $response->assertRedirect('/books');
    }

    public function test_register_validate_max_over(): void{
        //上限越え
        $response = $this->get('/register');
        $response->assertStatus(200);
        
        $user = [
            'name' => str_repeat('a', 51),
            'email' => str_repeat('a', 101),
            'password' => str_repeat('a', 21),
            'password_confirmation' => str_repeat('a', 21),
        ];

        $response = $this->post('/register',$user);

        $response->assertSessionHasErrors([
            'name' => ['50字以内で入力してください'],
        ]);
        $response->assertSessionHasErrors([
            'email' => ['max：100字以内で入力してください'],
        ]);
        $response->assertSessionHasErrors([
            'password' => ['8字以上20字以内で入力してください'],
        ]);
    }

    public function test_register_validate_min(): void{
        //下限ギリギリ
        $response = $this->get('/register');
        $response->assertStatus(200);
        
        $user = [
            'name' => str_repeat('a', 50),
            'email' => str_repeat('a', 100),
            'password' => str_repeat('a', 8),
            'password_confirmation' => str_repeat('a', 8),
        ];

        $response = $this->post('/register',$user);

        $response->assertRedirect('/books');
    }

    public function test_register_validate_min_over(): void{
        //下限オーバー
        $response = $this->get('/register');
        $response->assertStatus(200);
        
        $user = [
            'name' => str_repeat('a', 50),
            'email' => str_repeat('a', 100),
            'password' => str_repeat('a', 7),
            'password_confirmation' => str_repeat('a', 7),
        ];

        $response = $this->post('/register',$user);

        $response->assertSessionHasErrors([
            'password' => ['8字以上20字以内で入力してください'],
        ]);
    }

    public function test_register_validate_confirmed(): void{
        //パスワード確認とのずれ
        $response = $this->get('/register');
        $response->assertStatus(200);
        
        $user = [
            'name' => str_repeat('a', 50),
            'email' => str_repeat('a', 100),
            'password' => str_repeat('a', 8),
            'password_confirmation' => str_repeat('b', 8),
        ];

        $response = $this->post('/register',$user);

        $response->assertSessionHasErrors([
            'password' => ['確認用パスワードが一致しません'],
        ]);
    }

    public function test_register_validate_confirmed_blank(): void{
        //パスワード確認が空白の場合
        $response = $this->get('/register');
        $response->assertStatus(200);
        
        $user = [
            'name' => str_repeat('a', 50),
            'email' => str_repeat('a', 100),
            'password' => str_repeat('a', 8),
            'password_confirmation' => '',
        ];

        $response = $this->post('/register',$user);

        $response->assertSessionHasErrors([
            'password' => ['確認用パスワードが一致しません'],
        ]);
    }   



    public function test_login_validate_requierd(): void
    {   //必須テスト
        $response = $this->get('/login');
        $response->assertStatus(200);

        $user = [
            'email' => '',
            'password' => '',
        ];

        $response = $this->post('login',$user);

        $response->assertSessionHasErrors([
            'email' => ['メールアドレスを入力してください'],
        ]);
        $response->assertSessionHasErrors([
            'password' => ['パスワードを入力してください'],
        ]);
    }

    public function test_login_validate_mail(): void
    {   //必須テスト
        $response = $this->get('/login');
        $response->assertStatus(200);

        $user = [
            'email' => 'Yama@example.com',
            'password' => 'password',
        ];

        $response = $this->post('/login',$user);

        $response->assertSessionHasErrors([
            'name' => ['メールアドレス、またはパスワードが正しくありません'],
        ]);
    }

    public function test_login_validate_password(): void
    {   //必須テスト
        $response = $this->get('/login');
        $response->assertStatus(200);

        $user = [
            'email' => 'Yama@example.com',
            'password' => '12345678',
        ];

        $response = $this->post('/login',$user);

        $response->assertSessionHasErrors([
            'name' => ['メールアドレス、またはパスワードが正しくありません'],
        ]);
    }

    public function test_login_lodirect(): void
    {   
        //認証済みユーザーはリダイレクトされる
        $user = User::first();

        $response = $this->actingAs($user)
        ->post('/login',$user);

        $response->assertRedirect('/books');

        $response = $this->actingAs($user)
        ->post('/register',$user);

        $response->assertRedirect('/books');
    }
}
