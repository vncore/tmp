<?php
// list ID admin guard
define('SC_GUARD_ADMIN', ['1']); // admin
// list ID language guard
define('SC_GUARD_LANGUAGE', ['1', '2']); // vi, en
// list ID ROLES guard
define('SC_GUARD_ROLES', ['1', '2']); // admin, only view

/**
 * Admin define
 */
define('SC_ADMIN_MIDDLEWARE', ['web', 'admin']);
define('SC_FRONT_MIDDLEWARE', ['web', 'front']);
define('SC_API_MIDDLEWARE', ['api', 'api.extend']);
define('SC_CONNECTION', 'mysql');
define('SC_CONNECTION_LOG', 'mysql');
//Prefix url admin
define('VNCORE_ADMIN_PREFIX', config('vncore:config-const.ADMIN_PREFIX'));
//Prefix database
define('SC_DB_PREFIX', config('vncore:config-const.DB_PREFIX'));
// Root ID store
define('SC_ID_ROOT', 1);
define('SC_ID_GLOBAL', 0);
