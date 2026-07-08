<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookRequestTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;

    public function test_required_validation_errors(): void
    {
        $user = User::first();

        $data = [
            'title' => '',
            'author' => '',
            'isbn' => '',
            'published_date' => '',
            'genres' => [],
        ];

        $response = $this->actingAs($user)
            ->post('/books', $data);

        $response->assertSessionHasErrors([
            'title',
            'author',
            'isbn',
            'published_date',
            'genres',
        ]);
    }

    public function test_max_length_validation(): void
    {
        $user = User::first();
        $genre = Genre::first();

        // title 151文字
        $data = [
            'title' => str_repeat('a', 151),
            'author' => 'test',
            'isbn' => '0000000000000',
            'published_date' => '2026-06-12',
            'genres' => [$genre->id],
        ];

        $response = $this->actingAs($user)
            ->post('/books', $data);

        $response->assertSessionHasErrors(['title']);

        // author 101文字
        $data['title'] = 'test';
        $data['author'] = str_repeat('a', 101);

        $response = $this->actingAs($user)
            ->post('/books', $data);

        $response->assertSessionHasErrors(['author']);
    }

    public function test_isbn_validation(): void
    {
        $user = User::first();
        $genre = Genre::first();

        // 12桁
        $data = [
            'title' => 'test',
            'author' => 'test',
            'isbn' => str_repeat('1', 12),
            'published_date' => '2026-06-12',
            'genres' => [$genre->id],
        ];

        $response = $this->actingAs($user)
            ->post('/books', $data);

        $response->assertSessionHasErrors(['isbn']);

        // 14桁
        $data['isbn'] = str_repeat('1', 14);

        $response = $this->actingAs($user)
            ->post('/books', $data);

        $response->assertSessionHasErrors(['isbn']);
    }

    public function test_des_validation(): void
    {
        $user = User::first();
        $genre = Genre::first();

        // 255 OK
        $data = [
            'title' => 'test',
            'author' => 'test',
            'isbn' => '0000000000000',
            'published_date' => '2026-06-12',
            'genres' => [$genre->id],
            'description' => str_repeat('a', 255),
        ];

        $response = $this->actingAs($user)
            ->post('/books', $data);

        $response->assertSessionHasNoErrors();

        // 256 NG
        $data['description'] = str_repeat('a', 256);

        $response = $this->actingAs($user)
            ->post('/books', $data);

        $response->assertSessionHasErrors(['description']);
    }
}