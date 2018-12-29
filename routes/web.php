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
    return view('1805');
});
//Route::post('/abc/{id}/{name}','TestController@test')->where('id','\d+')->name('a');
Route::post('/add','TestController@add');
Route::get('/add_list','TestController@add_list');
Route::get('/delete','TestController@delete');
Route::get('/update','TestController@update');
Route::post('/update_add','TestController@update_add');