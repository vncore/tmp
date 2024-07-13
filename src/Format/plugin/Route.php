<?php
/**
 * Route front
 */
if(vncore_config_exist('Plugin_Key')) {
Route::group(
    [
        'prefix'    => 'plugin/PluginUrlKey',
        'namespace' => 'App\Plugins\Plugin_Code\Plugin_Key\Controllers',
    ],
    function () {
        Route::get('index', 'FrontController@index')
        ->name('PluginUrlKey.index');
    }
);
}
/**
 * Route admin
 */
if(vncore_config_exist('Plugin_Key', VNCORE_ID_ROOT)) {
Route::group(
    [
        'prefix' => VNCORE_ADMIN_PREFIX.'/PluginUrlKey',
        'middleware' => VNCORE_ADMIN_MIDDLEWARE,
        'namespace' => 'App\Plugins\Plugin_Code\Plugin_Key\Admin',
    ], 
    function () {
        Route::get('/', 'AdminController@index')
        ->name('admin_PluginUrlKey.index');
    }
);
}