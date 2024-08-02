<?php

namespace Vncore\Core\Listeners;

use Vncore\Core\Events\AdminDeleting as EventAdminDeleting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Events\Dispatcher;

class AdminDeleting
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(EventAdminDeleting $event)
    {
        $user = $event->user;
        if (function_exists('pmo_sync_user_mapping')) {
            pmo_sync_user_mapping($user, $action ="delete");
        }
    }


    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            EventAdminDeleting::class,
            [AdminDeleting::class, 'handle']
        );
    }
}
