<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // echo date('Y-m-d H:i:s');
    return view('1805');
});
//Route::post('/abc/{id}/{name}','TestController@test')->where('id','\d+')->name('a');
Route::post('/add','TestController@add');
Route::get('/add_list','TestController@add_list');
Route::get('/delete','TestController@delete');
Route::get('/update','TestController@update');
Route::post('/update_add','TestController@update_add');
Route::any('/zhu','Vip\IndexController@zhu');
Route::any('/zhuce','Vip\IndexController@zhuce');
Route::any('/login','Vip\IndexController@login');
Route::post('/loginadd','Vip\IndexController@loginadd');
Route::any('/ce','Vip\IndexController@ce');
Route::any('/center','Vip\IndexController@center');
Route::get('/check_cookie','TestController@checkCookie')->middleware('check.cookie');
Route::get('/cart','Cart\IndexController@index')->middleware('check.login.token');
Route::get('/add/{goods_id}','Cart\IndexController@add')->middleware('check.login.token');
Route::get('/delete/{goods_id}','Cart\IndexController@delete')->middleware('check.login.token');
Route::get('/request','Cart\IndexController@cart')->middleware('check.login.token');
Route::get('/goodsadd/{goods_id}','Good\GoodsController@goodsadd');
Route::get('/goods','Good\GoodsController@goods');
Route::get('/del/{goods_id}','Cart\IndexController@del')->middleware('check.login.token');
Route::any('/add2','Cart\IndexController@add2')->middleware('check.login.token');
Route::get('/order','Order\OrderController@order');
Route::get('/orderlist','Order\OrderController@orderlist');
Route::get('/orderzhi/{o_id}','Order\OrderController@orderzhi')->middleware('check.login.token');
Route::get('/centeradd','Vip\IndexController@centeradd')->middleware('check.login.token');
//支付
Route::get('/test','Pay\AlipayController@test');
Route::get('/pay/o/{oid}','Pay\IndexController@order')->middleware('check.login.token');
Route::post('/pay/alipay/notify','Pay\AlipayController@notify');