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
Route::any('/mylogin','Vip\IndexController@login');
Route::post('/mylogin/add','Vip\IndexController@loginadd');
Route::any('/ce','Vip\IndexController@ce');
Route::any('/center','Vip\IndexController@center');
Route::get('/check_cookie','TestController@checkCookie')->middleware('check.cookie');
Route::get('/cart','Cart\IndexController@index');
Route::get('/add/{goods_id}','Cart\IndexController@add')->middleware('check.mylogin.token');
Route::get('/delete/{goods_id}','Cart\IndexController@delete')->middleware('check.mylogin.token');
Route::get('/request','Cart\IndexController@cart')->middleware('check.mylogin.token');
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
//Route::get('/weixin/valid','weixin\WeixinController@validToken');
//Route::get('/weixin/valid1','weixin\WeixinController@validToken1');
//Route::post('/weixin/valid1','weixin\WeixinController@weixinEven');
//Route::post('/weixin/valid','weixin\WeixinController@validToken');

Route::get('/weixin/create','weixin\WeixinController@create');//创建服务号菜单
Route::post('/weixin/create','weixin\WeixinController@create');//创建服务号菜单
Route::get('/weixin/demo','weixin\WeixinController@demo');//接收永久素材

Route::get('/weixin/Ceshow','weixin\WeixinController@Ceshow');//创建表单
Route::post('/weixin/wxdo','weixin\WeixinController@wxdo');//处理表单数据

Route::get('/weixin/wxlist','weixin\WeixinController@wxlist');//接收永久素材
Route::post('/weixin/upMaterialTest','weixin\WeixinController@upMaterialTest');
Route::get('/weixin/wxpc','weixin\WeixinController@wxpc');
//聊天
Route::get('/weixin/fofa','weixin\WeixinController@fofa');
Route::get('/weixin/wxfofa','weixin\WeixinController@wxfofa');
Route::get('/weixin/wxfofado','weixin\WeixinController@wxfofado');
//微信支付
Route::get('/weixin/firtest','weixin\WxpayController@firtest');
Route::post('/weixin/notice','weixin\WxpayController@notice');

//Route::get('/weixin/pay/test/{order_name}','weixin\PayController@test');     //微信支付测试
//Route::post('/weixin/pay/notice','weixin\PayController@notice');     //微信支付通知回调

Route::get('/weixin/pay/test/{order_name}','weixin\PaysController@test');     //微信支付测试
Route::post('/weixin/pay/notice','weixin\PaysController@notice');     //微信支付通知回调
Route::post('/weixin/pay/payweixin','weixin\PaysController@payweixin');     //微信支付通知回调
Route::get('/weixin/pay/pay111','weixin\PaysController@pay111');     //微信支付通知回调

//微信登录
Route::get('/weixin/login','weixin\WxloginController@wxlogin');
Route::get('/weixin/login/index','weixin\WxloginController@index');

//微信JS SDK调试
Route::get('/weixin/jssdk/test','weixin\WeixinController@jssdk');
Route::get('/ws','websocket\WsController@ws');

//考试
Route::get('/wxtd1','weixin\KaoshiController@wxtd1');
Route::get('/validToken1','weixin\KaoshiController@validToken1');
Route::post('/validToken1','weixin\KaoshiController@wxtd');
Route::post('/wxtd1','weixin\KaoshiController@wxtd1');

Route::get('/wxtoken','weixin\KaoshiController@access_token');
Route::get('/weixin/list','weixin\KaoshiController@weixinlist');
Route::post('/weixin/listadd','weixin\KaoshiController@weixinlistadd');

Route::post('/hbuired/api','test\TestController@test1');
Route::post('/hbuired/api2','test\TestController@test2');
Route::get('/test/start','test\TestController@start');
Route::post('/test/str','test\TestController@str');
Route::post('/test/one','test\TestController@one');
Route::post('/testone','test\TestController@testone');
Route::get('/cs','test\TestController@cs');
Route::get('/firstcenter','test\TestController@firstcenter');

Route::post('/startest','test\TestController@startest');
Route::post('/startest/onstart','test\TestController@onstart');
//考试
Route::get('/ks/login','test\TestController@kslogin');