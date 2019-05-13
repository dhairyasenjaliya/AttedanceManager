<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();   
// });

Route::apiResource('user','API\UserController');
Route::apiResource('time','API\UserTimesheetController');  

Route::get('profile','API\UserController@profile'); 
Route::put('profile','API\UserController@updateProfile'); 


Route::get('findUser','API\UserController@search'); 
Route::get('name','API\UserController@name');      

Route::get('timesheet','API\UserController@timesheet');  
Route::get('timesheetmanager','API\UserController@timesheetmanager');  


Route::get('daily','API\UserController@daily'); 
Route::get('year','API\UserController@year'); 
Route::get('month','API\UserController@month'); 
Route::get('week','API\UserController@week'); 


Route::get('leave','API\UserController@leave');

Route::put('casule_leave','API\UserController@casule_leave');
Route::get('getleavesybyid','API\UserController@getleavesybyid'); 

Route::get('removeleavesybyid','API\UserController@removeleavesybyid'); 



Route::get('getuserleave','API\UserController@getuserleave'); 
 
Route::get('authuser','API\UserController@authuser');
 
Route::put('adminpunch','API\UserController@adminpunch'); 
Route::put('punch','API\UserController@punch');


Route::get('userid','API\UserTimesheetController@userid');  