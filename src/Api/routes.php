use Illuminate\Support\Facades\Route;

<?php


// Route api
Route::group(
    [
        'middleware' => VNCORE_API_MIDDLEWARE,
        'prefix' => 'api',
        'namespace' => '\Vncore\Core\Api\Controllers',
    ],
    function () {

        // Admin
        Route::group(['prefix' => config('vncore.VNCORE_ADMIN_PREFIX')], function () {
            Route::post('login', 'AdminAuthController@login');
            Route::group([
                'middleware' => ['auth:admin-api', config('vncore-config.api.auth.api_scope_type_admin').':'.config('vncore-config.api.auth.api_scope_admin')]
            ], function () {
                Route::get('logout', 'AdminAuthController@logout');
                Route::get('info', 'AdminController@getInfo');
                Route::get('countries', 'AdminShopFront@allCountry');
                Route::get('countries/{id}', 'AdminShopFront@countryDetail');
                Route::get('languages', 'AdminShopFront@allLanguage');
                Route::get('languages/{id}', 'AdminShopFront@LanguageDetail');
            });
        });
    }
);
