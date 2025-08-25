<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ClearExpiredOtpsJob implements ShouldQueue
{
 use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');
        User::whereNotNull('otp')
            ->where('otp_expires_at', '<', $now)
            ->update([
                'otp' => null,
                'otp_expires_at' => null,
            ]);
    }
}
