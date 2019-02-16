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
<<<<<<< HEAD
Route::get('/info',function(){
    phpinfo();
=======
//查看扩展
Route::get('/info', function () {
<<<<<<< HEAD
	phpinfo();
});

Route::middleware(['log.click'])->group(function(){
	Route::get('/test/cookie1','Ce\CeController@cookieTest1');
=======
    phpinfo();
>>>>>>> houtai
>>>>>>> ea52b742f57db35e614306f39a32672887952f17
});
//Route::post('/abc/{id}/{name}','TestController@test')->where('id','\d+')->name('a');
Route::post('/add','TestController@add');
Route::get('/add_list','TestController@add_list');
Route::get('/delete','TestController@delete');
Route::get('/update','TestController@update');
Route::post('/update_add','TestController@update_add');
Route::any('/zhu','Vip\IndexController@zhu');
Route::any('/zhuce','Vip\IndexController@zhuce');
Route::any('/login','Vip\IndexController@login')->name('login');
Route::post('/loginadd','Vip\IndexController@loginadd');
Route::any('/ce','Vip\IndexController@ce');
Route::any('/center','Vip\IndexController@center');
Route::get('/check_cookie','TestController@checkCookie')->middleware('check.cookie');
Route::get('/cart','Cart\IndexController@index');
Route::get('/add/{goods_id}','Cart\IndexController@add')->middleware('check.login.token');
Route::get('/delete/{goods_id}','Cart\IndexController@delete')->middleware('check.login.token');
Route::get('/request','Cart\IndexController@cart')->middleware('check.login.token');
Route::get('/goodsadd/{goods_id}','Good\GoodsController@goodsadd');
Route::get('/goods','Good\GoodsController@goods');
<<<<<<< HEAD
Route::get('/goodsa','Good\GoodsController@goodsa');
Route::post('/Redisbuy','Good\GoodsController@Redisbuy');
Route::get('/Rediszuo','Good\GoodsController@Rediszuo');
Route::get('/updateGoodsInfo','Good\GoodsController@updateGoodsInfo');
Route::post('/goodsou','Good\GoodsController@goodsou');
=======
Route::get('/upload','Good\GoodsController@upload');
Route::post('/uploadpdf','Good\GoodsController@uploadpdf');
>>>>>>> ea52b742f57db35e614306f39a32672887952f17
Route::get('/del/{goods_id}','Cart\IndexController@del');
Route::any('/add2','Cart\IndexController@add2');
Route::get('/order','Order\OrderController@order');
Route::get('/orderlist','Order\OrderController@orderlist');
Route::get('/orderzhi/{o_id}','Order\OrderController@orderzhi');
Route::get('/centeradd','Vip\IndexController@centeradd');
Route::get('/ce','Ce\CeController@ce');
//支付
Route::get('/pay/alipay/test/{o_id}','Pay\AlipayController@test');
// Route::get('/pay/o/{oid}','Pay\IndexController@order')->middleware('check.login.token');
Route::post('/pay/alipay/notify','Pay\AlipayController@notify');
Route::get('/pay/alipay/return','Pay\AlipayController@Return');
Route::get('/pay/alipay/orderdelete','Pay\AlipayController@orderdelete');
Auth::routes();
<<<<<<< HEAD

Route::get('/home', 'HomeController@index')->name('home');
//微信
Route::get('/weixin','weixin\WeixinController@validToken1');
Route::post('/weixin/valid1','weixin\WeixinController@wxEvent');
=======
Route::get('/home', 'HomeController@index')->name('home');
>>>>>>> ea52b742f57db35e614306f39a32672887952f17
