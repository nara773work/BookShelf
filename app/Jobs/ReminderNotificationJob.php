<?php

namespace App\Jobs;

use App\Enums\ReadingPlanStatus;
use App\Models\ReadingPlan;
use App\Notifications\ReminderNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class ReminderNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    /**
     * Execute the job.
     */

    /**
     * 通知処理を実行する。
     */
    public function handle(): void
    {
        $today = Carbon::today();
        $limit = Carbon::today()->addDays(3);

        $plans = ReadingPlan::whereBetween('target_date', [$today, $limit])
            ->where('status', ReadingPlanStatus::Reading->value)
            ->get();

        foreach ($plans as $plan) {
            if ($plan->reminded_at) {
                continue;
            }

            Notification::send(
                $plan->user,
                new ReminderNotification($plan->book)
            );

            $plan->update([
                'reminded_at' => now(),
            ]);
        }

    }
}
