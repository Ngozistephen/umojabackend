<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PublishScheduledPosts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

 
    public function __construct()
    {
      
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $posts = Post::whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', now())
            ->whereNull('published_at')
            ->where('is_draft', false)
            ->get();

            foreach ($posts as $post) {
                if ($post->shouldPublish()) {
                    $post->publish();
                }
            }
    }
}
