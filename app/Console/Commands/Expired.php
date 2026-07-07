<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\ReadingPlan;
use App\Notifications\ExpiredNotification;
use App\Enums\ReadingPlanStatus;
class Expired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'books:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $today = Carbon::today();

        $plans = ReadingPlan::whereDate('target_date', '<', $today)
        ->where('status', ReadingPlanStatus::Expired->value)
        ->get();

        foreach ($plans as $plan) {
            if ($plan->reminded_at) {
                continue;
            }

            $plan->update([
                'status' => ReadingPlanStatus::Expired,
                'reminded_at' => now()
            ]);

            $plan->user->notify(
                new ExpiredNotification($plan->book)
            );

        }

        $this->info('Expired notifications sent.');
        
    }
}



