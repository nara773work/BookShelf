<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Models\Book;
use App\Models\ReadingPlan;
use App\Enums\ReadingPlanStatus;
use App\Notifications\ReminderNotification;
use App\Notifications\ExpiredNotification;
use Carbon\Carbon;


class CommandsTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;
    protected $seed = true;

    public function test_command_runs_successfully_reminder()
    {
        //reminder通知が送られるか
        Notification::fake();

        $user = User::first();
        $book = Book::first();

        ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => ReadingPlanStatus::Reading->value,
            'target_date' => Carbon::today()->addDays(2),
        ]);

        $this->artisan('books:reminder')
        ->assertExitCode(0);

        Notification::assertSentTo($user, ReminderNotification::class);

        $this->assertDatabaseHas('reading_plans', [
            'user_id' => $user->id,
            'reminded_at' => Carbon::today(),
        ]);
    }

    public function test_command_runs_successfully_expierd()
    {
        //期限切れになるかのテスト、期限切れ通知が送られるか
        Notification::fake();

        $user = User::first();
        $book = Book::first();

        ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => ReadingPlanStatus::Reading->value,
            'target_date' => Carbon::yesterday(),
        ]);

        $this->artisan('books:expired')
        ->assertExitCode(0);

        Notification::assertSentTo($user, ExpiredNotification::class);

        $this->assertDatabaseHas('reading_plans', [
            'user_id' => $user->id,
            'status' => ReadingPlanStatus::Expired->value,
        ]);
    }
}
