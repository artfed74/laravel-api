<?php

namespace App\Listeners;

use App\Events\ScoreUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Pusher\Pusher;

class SendScoreUpdateNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ScoreUpdated $event)
    {
        $options = [
            'cluster' => 'ap1',
            'useTLS' => true
        ];

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        $pusher->trigger('admin', 'addScore', [
            'user' => [
                'id' => $event->user->id,
                'score' => $event->user->score_correct_task
            ],
            'group' => [
                'id' => $event->group->id,
                'total_score' => $event->group->total_score
            ]
        ]);
    }
}
