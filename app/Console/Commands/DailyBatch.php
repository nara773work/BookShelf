<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DailyBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:daily-batch';

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
        $limit = Carbon::today();

        $plans = ReadingPlan::whereBetween('target_date', [$today, $limit])->get();

        foreach ($plans as $plan) {
            $plan->user->notify(
                new \App\Notifications\BookDeadlineNotification($plan->book)
            );
        }

        $this->info('Deadline notifications sent.');
    }
}
