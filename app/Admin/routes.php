<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
    $router->get('/wxsendmsg', 'WxmsgController@sendmsg')->name('admin.home');
//    $router->get('/sendscene', 'SceneController@sendscene')->name('admin.home');
    $router->resource('users', WxUserController::class);
    $router->resource('message', MessageController::class);

    $router->resource('goods', GoodsController::class);

});
