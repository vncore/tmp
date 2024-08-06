<?php

use Illuminate\Support\Facades\Route;
//Process namespace
if (file_exists(app_path('Vncore/Front/Controllers/HomeController.php'))) {
    $nameSpaceFrontContent = 'App\Vncore\Front\Controllers';
} else {
    $nameSpaceFrontContent = 'Vncore\Core\Front\Controllers';
}
$langUrl = config('vncore-config.route.VNCORE_PREFIX_LANG');

//Route customize
Route::group(
    [
        'middleware' => VNCORE_FRONT_MIDDLEWARE,
    ],
    function () use($langUrl){
        //Include route customize
        if (file_exists(app_path('Vncore/myroute.php'))) {
            require_once app_path('Vncore/myroute.php');
        }
    }
);


//Content without prefix
Route::group(
    [
        'middleware' => VNCORE_FRONT_MIDDLEWARE
    ],
    function () use ($nameSpaceFrontContent) {
        //Route home
        Route::get('/', $nameSpaceFrontContent.'HomeController@index')
            ->name('home');
    }
);
