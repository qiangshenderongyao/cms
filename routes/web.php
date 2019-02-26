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
Route::get('/info',function(){
    phpinfo();
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
Route::get('/cart','Cart\IndexController@index');
Route::get('/add/{goods_id}','Cart\IndexController@add')->middleware('check.login.token');
Route::get('/delete/{goods_id}','Cart\IndexController@delete')->middleware('check.login.token');
Route::get('/request','Cart\IndexController@cart')->middleware('check.login.token');
Route::get('/goodsadd/{goods_id}','Good\GoodsController@goodsadd');
Route::get('/goods','Good\GoodsController@goods');
Route::get('/goodsa','Good\GoodsController@goodsa');
Route::post('/Redisbuy','Good\GoodsController@Redisbuy');
Route::get('/Rediszuo','Good\GoodsController@Rediszuo');
Route::get('/updateGoodsInfo','Good\GoodsController@updateGoodsInfo');
Route::post('/goodsou','Good\GoodsController@goodsou');
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

Route::get('/home', 'HomeController@index')->name('home');
//微信
Route::get('/weixin/test','weixin\WeixinController@test');
Route::get('/weixin/valid','weixin\WeixinController@validToken');
Route::get('/weixin/valid1','weixin\WeixinController@validToken1');
Route::post('/weixin/valid1','weixin\WeixinController@weixinEven');
Route::post('/weixin/valid','weixin\WeixinController@validToken');

Route::get('/weixin/create','weixin\WeixinController@create');//创建服务号菜单
Route::post('/weixin/create','weixin\WeixinController@create');//创建服务号菜单
Route::get('/weixin/demo','weixin\WeixinController@demo');//接收永久素材

Route::get('/weixin/Ceshow','weixin\WeixinController@Ceshow');//创建表单
Route::post('/weixin/wxdo','weixin\WeixinController@wxdo');//处理表单数据

Route::get('/weixin/wxlist','weixin\WeixinController@wxlist');
Route::post('/weixin/upMaterialTest','weixin\WeixinController@upMaterialTest');
Route::get('/weixin/wxpc','weixin\WeixinController@wxpc');
//聊天
Route::get('/weixin/fofa','weixin\WeixinController@fofa');
Route::get('/weixin/wxfofa','weixin\WeixinController@wxfofa');
Route::get('/weixin/wxfofado','weixin\WeixinController@wxfofado');
//微信支付
Route::get('/weixin/firtest','weixin\WxpayController@firtest');
Route::post('/weixin/notice','weixin\WxpayController@notice');