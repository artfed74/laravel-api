<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Listeners\SendScoreUpdateNotification;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ScoreUpdated::class => [
            SendScoreUpdateNotification::class,
        ],
    ];

    public function boot()
    {
        //
    }
}
