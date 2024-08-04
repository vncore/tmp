<?php
if (file_exists(app_path('Vncore/Admin/Controllers/AdminReportController.php'))) {
    $nameSpaceAdminReport = 'App\Vncore\Admin\Controllers';
} else {
    $nameSpaceAdminReport = 'Vncore\Core\Admin\Controllers';
}
Route::group(['prefix' => 'report'], function () use ($nameSpaceAdminReport) {
    Route::get('/product', $nameSpaceAdminReport.'\AdminReportController@product')->name('admin_report.product');
});