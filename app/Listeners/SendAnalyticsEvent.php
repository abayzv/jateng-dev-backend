<?php

namespace App\Listeners;

use App\Events\AnalyticsEvent;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendAnalyticsEvent
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
    public function handle(AnalyticsEvent $event): void
    {
        Notification::make()
            ->body($event->event['message'])
            ->success()
            ->send();
    }
}
