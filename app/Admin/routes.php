<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->get('/weixin/sendmsg','WeixinController@sendMsgView');
    $router->post('/weixin/sendmsg','WeixinController@sendMsg');

    $router->resource('/goods',GoodsController::class);
    $router->resource('/weixin',WeixinController::class);
    $router->resource('/wxmedia',WeixinMediaController::class);
    $router->resource('/wxqf',WeixinMediaController::class);
    $router->resource('/yongjiulist',WxyongController::class);

});
