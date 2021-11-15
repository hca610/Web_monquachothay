<?php

use App\Http\Controllers\CategoryController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post('categories', 'CategoryController@index');
// Route::resource('user', UserController::class);
// Route::resource('employer', EmployerController::class);

# Notification
// Route::resource('/notification', NotificationController::class);
Route::post('notification', 'NotificationController@store')->name('Store Notification');
Route::put('notification/{notification}', 'NotificationController@update')->name('Update Notification');
Route::get('notification/{notification}', 'NotificationController@show')->name('Show Notification');
Route::get('/notification/user/{user_id}', 'NotificationController@showUserNoti')->name('Show user Notifications');

# Message
// Route::resource('/message', MessageController::class);
Route::post('message', 'MessageController@store')->name('Store Message');
Route::put('message/{message}', 'MessageController@update')->name('Update Message');
Route::get('message/{message}', 'MessageController@show')->name('Show Message');
Route::get('/message/between/sender={sender_id}&receiver={receiver_id}', 'MessageController@showUserMessage')->name('Show Messages between users');
// Route::redirect('/message/user/sender={sender_id}', '/message/between/sender={sender_id}&receiver=0');
Route::get('/report/receiver={receiver_id}', 'MessageController@reportCount')->name('Count user Reported');

# User
Route::get('/user', 'UserController@search');
Route::post('/user/{user}', 'UserController@banUser');
Route::get('/user/{user}','UserController@show');

# Employer
Route::get('/employer', 'EmployerController@search');
Route::post('/employer/create', 'EmployerController@store');
Route::put('/employer/{employer}', 'EmployerController@show');

# Jobseeker
Route::get('/jobseeker','JobSeekerController@search');
Route::post('/jobseeker/create', 'JobSeekerController@store');
Route::get('/jobseeker/{jobseeker}','JobSeekerController@show');
Route::put('/jobseeker/{jobseeker}', 'JobSeekerController@update');

# Category
Route::get('/category', 'JobSeekerController@search');
