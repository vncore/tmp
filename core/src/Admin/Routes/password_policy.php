<?php
if (file_exists(app_path('Admin/Controllers/AdminPasswordPolicyController.php'))) {
    $nameSpaceAdminStoreConfig = 'App\Admin\Controllers';
} else {
    $nameSpaceAdminStoreConfig = 'Vncore\Core\Admin\Controllers';
}
Route::group(['prefix' => 'password_policy'], function () use ($nameSpaceAdminStoreConfig) {
    Route::get('/', $nameSpaceAdminStoreConfig.'\AdminPasswordPolicyController@index')->name('admin_password_policy.index');
    Route::post('/update', $nameSpaceAdminStoreConfig.'\AdminPasswordPolicyController@update')->name('admin_password_policy.update');
});