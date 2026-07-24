<?php

namespace App\Console\Commands;

use App\Jobs\ReminderNotificationJob;
use Illuminate\Console\Command;

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
    protected $description = 'Check book deadlines and send notifications';

    /**
     * 期限が近い読書計画へreminder通知を発火する
     */
    public function handle()
    {

        ReminderNotificationJob::dispatch();

        $this->info('Reminder Job dispatched');
    }
}
