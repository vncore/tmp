<?php

use Illuminate\Support\Facades\Route;
//Process namespace
if (file_exists(app_path('Vncore/Front/Controllers/HomeController.php'))) {
    $nameSpaceFrontContent = 'App\Vncore\Front\Controllers';
} else {
    $nameSpaceFrontContent = 'Vncore\Core\Front\Controllers';
}

//Route customize
Route::group(
    [
        'middleware' => VNCORE_FRONT_MIDDLEWARE,
    ],
    function () use($langUrl){
        //Include route custom
        if (file_exists(base_path('routes/myroute.php'))) {
            require_once base_path('routes/myroute.php');
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
