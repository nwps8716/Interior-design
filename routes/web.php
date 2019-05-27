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
## 工程單價
Route::get('/engineering/unitprice', 'UnitPrice\UnitPriceController@getEngineeringList');
Route::post('/engineering', 'UnitPrice\UnitPriceController@createEngineering');
Route::put('/engineering', 'UnitPrice\UnitPriceController@putEngineering');
Route::delete('/engineering', 'UnitPrice\UnitPriceController@deleteEngineering');
Route::post('/subengineering', 'UnitPrice\UnitPriceController@createSubEngineering');
Route::put('/subengineering', 'UnitPrice\UnitPriceController@putSubEngineering');
Route::delete('/subengineering', 'UnitPrice\UnitPriceController@deleteSubEngineering');
## 工程預算
Route::get('/engineering/budget', 'Budget\BudgetController@getEngineering');
Route::put('/engineering/budget/{budget_id}', 'Budget\BudgetController@putUserEngineering');
Route::delete('/engineering/budget/{budget_id}', 'Budget\BudgetController@deleteUserEngineering');
## 系統單價
Route::get('/system/unitprice', 'UnitPrice\UnitPriceController@getSystemList');
Route::post('/system', 'UnitPrice\UnitPriceController@createSystem');
Route::post('/subsystem', 'UnitPrice\UnitPriceController@createSubSystem');
## 系統預算
Route::get('/system/budget', 'Budget\BudgetController@getSystem');
## 坪數估價
Route::get('/pings', 'Pings\PingsController@index');
Route::put('/pings/percent', 'Pings\PingsController@editPercent');
