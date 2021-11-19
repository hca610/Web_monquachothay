<?php

use App\Http\Controllers\CategoryController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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
Route::get('/message/between/sender={sender_id}&receiver={receiver_id}', 'MessageController@showUsersMessage')->name('Show Messages between users');

# Report
// Route::redirect('/message/user/sender={sender_id}', '/message/between/sender={sender_id}&receiver=0');
Route::get('/report/receiver={receiver_id}/count', 'MessageController@reportCount')->name('Count user Reported');
Route::get('/report/receiver={receiver_id}/all', 'MessageController@showReports')->name('Show all reports of an user');

# User
Route::get('/user', 'UserController@search');
Route::post('/user/{user}', 'UserController@banUser');
Route::get('/user/{user}', 'UserController@show');

# Employer
Route::get('/employer', 'EmployerController@search');
Route::get('/employer/{employer}', 'EmployerController@show');

# Jobseeker
Route::get('/jobseeker', 'JobSeekerController@search');
Route::get('/jobseeker/{jobseeker}', 'JobSeekerController@show');
Route::put('/jobseeker/{jobseeker}', 'JobSeekerController@update');

# Category
Route::get('/category', 'JobSeekerController@search');

# JWT
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::post('/change-pass', [AuthController::class, 'changePassWord']);
});
