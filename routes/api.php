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

Route::get('profile','API\UserController@profile'); 

Route::get('profile','API\UserController@profile'); 
Route::put('profile','API\UserController@updateProfile'); 


Route::get('findUser','API\UserController@search'); 

Route::put('punch','API\UserController@punch');     


Route::get('timesheet','API\UserController@timesheet');  
Route::get('timesheetmanager','API\UserController@timesheetmanager');  

Route::get('year','API\UserController@year'); 
Route::get('month','API\UserController@month'); 
Route::get('week','API\UserController@week'); 

// Route::apiResource('date','API\UserTimesheetController');   


Route::get('date','API\UserTimesheetController@date'); 

Route::apiResource('time','API\UserTimesheetController');

Route::get('name','API\UserController@name'); 