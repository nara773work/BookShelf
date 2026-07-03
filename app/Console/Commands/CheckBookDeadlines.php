<?php

namespace App\Console\Commands;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\ReadingPlan;
class CheckBookDeadlines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'books:check-deadlines';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description =  'Check book deadlines and send notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        $limit = Carbon::today()->addDays(3);

        $plans = ReadingPlan::whereBetween('target_date', [$today, $limit])->get();

        foreach ($plans as $plan) {
            $plan->user->notify(
                new \App\Notifications\BookDeadlineNotification($plan->book)
            );
        }

        $this->info('Deadline notifications sent.');

    }
}
