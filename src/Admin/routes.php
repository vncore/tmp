<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => VNCORE_ADMIN_PREFIX,
        'middleware' => VNCORE_ADMIN_MIDDLEWARE,
    ],
    function () {
        foreach (glob(__DIR__ . '/Routes/*.php') as $filename) {
            require_once $filename;
        }
        if (file_exists(app_path('Vncore/Admin/Controllers/DashboardController.php'))) {
            $nameSpaceAdminDashboard = 'App\Vncore\Admin\Controllers';
        } else {
            $nameSpaceAdminDashboard = 'Vncore\Core\Admin\Controllers';
        }
        Route::get('/', $nameSpaceAdminDashboard.'\DashboardController@index')->name('admin.home');
        Route::get('/deny', $nameSpaceAdminDashboard.'\DashboardController@deny')->name('admin.deny');
        Route::get('/data_not_found', $nameSpaceAdminDashboard.'\DashboardController@dataNotFound')->name('admin.data_not_found');
        Route::get('/deny_single', $nameSpaceAdminDashboard.'\DashboardController@denySingle')->name('admin.deny_single');

        //Language
        Route::get('locale/{code}', function ($code) {
            session(['locale' => $code]);
            return back();
        })->name('admin.locale');

        //theme
        Route::get('theme/{theme}', function ($theme) {
            session(['adminTheme' => $theme]);
            if (!\Admin::user()->isViewAll()) {
                \Admin::user()->update(['theme' => $theme]);
            }
            return back();
        })->name('admin.theme');
    }
);