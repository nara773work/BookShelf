<?php

namespace App\Console\Commands;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\ReadingPlan;
use App\Notifications\ReminderNotification;
class Reminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'books:reminder';

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

        $plans = ReadingPlan::whereBetween('target_date', [$today, $limit])
        ->where('status', 'active')
        ->get();

        foreach ($plans as $plan) {
            if ($plan->reminded_at) {
                continue;
            }
            $plan->user->notify(
                new \App\Notifications\ReminderNotification($plan->book)
            );
            $plan->update([
                'reminded_at' => now()
            ]);
        }

        $this->info('Reminder notifications sent.');

    }
}
