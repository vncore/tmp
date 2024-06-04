<?php
use \Vncore\Core\Events\AdminLogin;
use \Vncore\Core\Events\AdminCreated;
use \Vncore\Core\Events\AdminDeleting;
if (!function_exists('vc_event_admin_login') && !in_array('vc_event_admin_login', config('helper_except', []))) {
    /**
     * [vc_event_admin_login description]
     *
     * @param   string  $str  [$str description]
     *
     * @return  [type]        [return description]
     */
    function vc_event_admin_login(\Vncore\Core\Admin\Models\AdminUser $user)
    {
        AdminLogin::dispatch($user);
    }
}
if (!function_exists('vc_event_admin_created') && !in_array('vc_event_admin_created', config('helper_except', []))) {
    /**
     * [vc_event_admin_created description]
     *
     * @param   string  $str  [$str description]
     *
     * @return  [type]        [return description]
     */
    function vc_event_admin_created(\Vncore\Core\Admin\Models\AdminUser $user)
    {
        AdminCreated::dispatch($user);
    }
}
if (!function_exists('vc_event_admin_deleting') && !in_array('vc_event_admin_deleting', config('helper_except', []))) {
    /**
     * [vc_event_admin_deleting description]
     *
     * @param   string  $str  [$str description]
     *
     * @return  [type]        [return description]
     */
    function vc_event_admin_deleting(\Vncore\Core\Admin\Models\AdminUser $user)
    {
        AdminDeleting::dispatch($user);
    }
}