<?php

namespace App\Jobs;

use App\Enums\ReadingPlanStatus;
use App\Models\ReadingPlan;
use App\Notifications\ExpiredNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class ExpiredNotificationJob implements ShouldQueue
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

        ReadingPlan::whereDate('target_date', '<', $today)
            ->whereIn('status', [
                ReadingPlanStatus::Reading->value,
            ])
            ->update([
                'status' => ReadingPlanStatus::Expired,
            ]);

        $plans = ReadingPlan::whereDate('target_date', '<', $today)
            ->where('status', ReadingPlanStatus::Expired->value)
            ->get();

        foreach ($plans as $plan) {
            if ($plan->reminded_at) {
                continue;
            }

            $plan->update([
                'status' => ReadingPlanStatus::Expired,
                'reminded_at' => now(),
            ]);

            Notification::send(
                $plan->user,
                new ExpiredNotification($plan->book)
            );

        }
    }
}
