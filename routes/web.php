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
    return redirect('login');
});

Route::get('/home', 'HomeController@index');
Route::get('/login', 'User\UserController@getLogin');
Route::post('/login', 'User\UserController@postLogin');
Route::get('/logout', 'User\UserController@getLogout');
Route::get('/adduser', 'User\UserController@getCreateUser');
Route::post('/user/create', 'User\UserController@createUser');
Route::get('/pings', 'Pings\PingsController@index');
Route::get('/engineering/unitprice', 'UnitPrice\UnitPriceController@getEngineeringList');
Route::post('/subengineering', 'UnitPrice\UnitPriceController@createSubEngineering');
Route::put('/subengineering', 'UnitPrice\UnitPriceController@putSubEngineering');
Route::delete('/subengineering', 'UnitPrice\UnitPriceController@deleteSubEngineering');
Route::post('/engineering', 'UnitPrice\UnitPriceController@createEngineering');
Route::get('/engineering/budget', 'Budget\BudgetController@getEngineering');
Route::get('/system/budget', 'Budget\BudgetController@getSystem');
Route::get('/system/unitprice', 'UnitPrice\UnitPriceController@getSystemList');
