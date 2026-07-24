<?php

namespace App\Console\Commands;

use App\Jobs\ExpiredNotificationJob;
use Illuminate\Console\Command;

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
     * 期限切れの読書計画へ期限切れ通知を発火する。
     */
    public function handle()
    {

        ExpiredNotificationJob::dispatch();

        $this->info('Expired Job dispatched.');

    }
}
