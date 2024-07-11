<?php
// list ID admin guard
define('VNCORE_GUARD_ADMIN', ['1']); // admin
// list ID language guard
define('VNCORE_GUARD_LANGUAGE', ['1', '2']); // vi, en
// list ID ROLES guard
define('VNCORE_GUARD_ROLES', ['1', '2']); // admin, only view

/**
 * Admin define
 */
define('VNCORE_ADMIN_MIDDLEWARE', ['web', 'admin']);
define('VNCORE_FRONT_MIDDLEWARE', ['web', 'front']);
define('VNCORE_API_MIDDLEWARE', ['api', 'api.extend']);
define('VNCORE_CONNECTION', 'mysql');
define('VNCORE_CONNECTION_LOG', 'mysql');
//Prefix url admin
define('VNCORE_ADMIN_PREFIX', config('vncore:config-const.VNCORE_ADMIN_PREFIX'));
//Prefix database
define('VNCORE_DB_PREFIX', config('vncore:config-const.VNCORE_DB_PREFIX'));
// Root ID store
define('VNCORE_ID_ROOT', 1);
define('VNCORE_ID_GLOBAL', 0);
