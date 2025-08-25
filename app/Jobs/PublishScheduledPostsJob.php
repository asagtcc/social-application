<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Post;
use App\Services\SocialPublisher;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class PublishScheduledPostsJob implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
       $now = Carbon::now();

        $posts = Post::where('status', 'queue')
            ->where('published_at', '<=', $now)
            ->get();


        foreach ($posts as $post) {
            try {
       
                app(SocialPublisher::class)->publish($post);
   
                $post->update([
                    'status' => 'sent',
                    'published_at' => $now,
                ]);
            } catch (\Exception $e) {
                $post->update([
                    'status' => 'failed',
                ]);
            }
        }
    }
}
