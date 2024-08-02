<?php

namespace Vncore\Core\Listeners;

use Vncore\Core\Events\AdminLogin as EventAdminLogin;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Events\Dispatcher;

class AdminLogin
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
    public function handle(EventAdminLogin $event)
    {
        $user = $event->user;
        if (function_exists('pmo_set_session_member_id')) {
            pmo_set_session_member_id($user);
        }
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            EventAdminLogin::class,
            [AdminLogin::class, 'handle']
        );
    }

}
