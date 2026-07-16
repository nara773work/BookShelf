<?php

namespace Tests\Feature\Controller;

use App\Models\Book;
use App\Models\User;
use App\Notifications\ExpiredNotification;
use Tests\TestCase;

class NotificationsControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_index(): void
    {
        $user = User::first();
        $notifications = $user->notifications()->first();

        $response = $this->actingAs($user)
            ->get('/notifications');

        $response->assertViewHas('notifications');

        $response->assertStatus(200);
    }

    public function test_read(): void
    {
        $user = User::first();
        $book = Book::first();

        $user->notify(new ExpiredNotification($book));

        $notifications = $user->notifications()->first();

        $responses = $this
            ->actingAs($user)
            ->from('/notifications')
            ->post("/notifications/{$notifications->id}/read");

        $responses->assertRedirect('/notifications');

        $notifications->refresh();

        $this->assertNotNull($notifications->read_at);
    }
}
