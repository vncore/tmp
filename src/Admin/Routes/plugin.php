<?php
if (file_exists(app_path('Vncore/Admin/Controllers/AdminPluginsController.php'))) {
    $nameSpaceAdminPlugin = 'App\Vncore\Admin\Controllers';
} else {
    $nameSpaceAdminPlugin = 'Vncore\Core\Admin\Controllers';
}
Route::group(['prefix' => 'plugin'], function () use ($nameSpaceAdminPlugin) {
    //Process import
    Route::get('/import', $nameSpaceAdminPlugin.'\AdminPluginsController@importPlugin')
        ->name('admin_plugin.import');
    Route::post('/import', $nameSpaceAdminPlugin.'\AdminPluginsController@processImport')
        ->name('admin_plugin.process_import');
    //End process
    
    Route::get('', $nameSpaceAdminPlugin.'\AdminPluginsController@index')
        ->name('admin_plugin');
    Route::post('/install', $nameSpaceAdminPlugin.'\AdminPluginsController@install')
        ->name('admin_plugin.install');
    Route::post('/uninstall', $nameSpaceAdminPlugin.'\AdminPluginsController@uninstall')
        ->name('admin_plugin.uninstall');
    Route::post('/enable', $nameSpaceAdminPlugin.'\AdminPluginsController@enable')
        ->name('admin_plugin.enable');
    Route::post('/disable', $nameSpaceAdminPlugin.'\AdminPluginsController@disable')
        ->name('admin_plugin.disable');

    if (config('vncore-config.admin.settings.api_plugin')) {
        Route::get('/online', $nameSpaceAdminPlugin.'\AdminPluginsOnlineController@index')
        ->name('admin_plugin_online');
        Route::post('/install/online', $nameSpaceAdminPlugin.'\AdminPluginsOnlineController@install')
            ->name('admin_plugin_online.install');
    }
});
