<?php


use App\Jobs\ClearExpiredOtpsJob;
use Illuminate\Foundation\Inspiring;
use App\Jobs\PublishScheduledPostsJob;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::job(new ClearExpiredOtpsJob)->hourly();
Schedule::job(new PublishScheduledPostsJob)->everyMinute();
