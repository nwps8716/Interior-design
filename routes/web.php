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
Route::get('/noservice', function () {
    return view('noservice');
});

Route::get('/home', 'HomeController@index');
Route::get('/login', 'User\UserController@getLogin');
Route::post('/login', 'User\UserController@postLogin');
Route::get('/logout', 'User\UserController@getLogout');
Route::get('/adduser', 'User\UserController@getCreateUser');
Route::post('/user/create', 'User\UserController@createUser');
Route::put('/user/password', 'User\UserController@putUserPassword');
## 工程單價
Route::get('/engineering/unitprice', 'UnitPrice\UnitPriceController@getEngineeringList');
Route::post('/engineering', 'UnitPrice\UnitPriceController@createEngineering');
Route::put('/engineering', 'UnitPrice\UnitPriceController@putEngineering');
Route::delete('/engineering', 'UnitPrice\UnitPriceController@deleteEngineering');
Route::post('/subengineering', 'UnitPrice\UnitPriceController@createSubEngineering');
Route::put('/subengineering', 'UnitPrice\UnitPriceController@putSubEngineering');
Route::delete('/subengineering', 'UnitPrice\UnitPriceController@deleteSubEngineering');
## 系統單價
Route::get('/system/unitprice', 'UnitPrice\UnitPriceController@getSystemList');
Route::post('/system', 'UnitPrice\UnitPriceController@createSystem');
Route::put('/system', 'UnitPrice\UnitPriceController@putSystem');
Route::delete('/system', 'UnitPrice\UnitPriceController@deleteSystem');
Route::post('/subsystem', 'UnitPrice\UnitPriceController@createSubSystem');
Route::put('/subsystem', 'UnitPrice\UnitPriceController@putSubSystem');
Route::delete('/subsystem', 'UnitPrice\UnitPriceController@deleteSubSystem');
## 工程預算
Route::get('/engineering/budget', 'Budget\BudgetController@getEngineering');
## 好禮贈送
Route::get('/system/free_gift', 'Budget\BudgetController@getFreeGift');
## 系統預算
Route::get('/system/budget', 'Budget\BudgetController@getSystem');
## 工程、系統、好禮共用
Route::put('/user/budget/{level_id}', 'Budget\BudgetController@putUserBudget');
Route::delete('/user/budget/{level_id}', 'Budget\BudgetController@deleteUserBudget');
## 坪數估價
Route::get('/pings', 'Pings\PingsController@index');
Route::get('/pings/{pings}/trial/amount', 'Pings\PingsController@getTrialAmount');
Route::put('/pings/percent', 'Pings\PingsController@editPercent');
Route::put('/user/pings', 'Pings\PingsController@editUserPings');
Route::put('/user/total/budget', 'Pings\PingsController@editUserTotalBudget');
