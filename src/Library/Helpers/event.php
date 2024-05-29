<?php
use \Vncore\Core\Events\AdminLogin;
if (!function_exists('vncore_event_admin_login') && !in_array('vncore_event_admin_login', config('helper_except', []))) {
    /**
     * [vncore_event_admin_login description]
     *
     * @param   string  $str  [$str description]
     *
     * @return  [type]        [return description]
     */
    function vncore_event_admin_login(\Vncore\Core\Admin\Models\AdminUser $user)
    {
        AdminLogin::dispatch($user);
    }
}