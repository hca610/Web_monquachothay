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
Route::prefix('notification')
->middleware('auth:api')
->group(function ($router) {
    Route::get('/', 'NotificationController@showAllNotification')->name('Show All Notifications');
    Route::post('/', 'NotificationController@createAndUpdateNotification')->name('Create and Update Notification');
    Route::get('/notification_id={notification}', 'NotificationController@showNotification')->name('Show Notification');
    Route::get('/user', 'NotificationController@showUserNotification')->name('Show user Notifications');
});

# Message eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYzODI1MDQwNSwiZXhwIjoxNjM4MjU0MDA1LCJuYmYiOjE2MzgyNTA0MDUsImp0aSI6IkFBNEppb29Ka1J2dUJ0ODAiLCJzdWIiOjUxLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.MWhsUqf_-hoMhGyHu52ixoZqhHExGDqnJhL_1m4hZao
Route::prefix('message')->middleware('auth:api')->group(function ($router) {
    Route::post('/', 'MessageController@store')->name('Store Message');
    Route::put('/{message}', 'MessageController@update')->name('Update Message');
    Route::get('/{message}', 'MessageController@show')->name('Show Message');
    Route::get('/between/sender={sender_id}&receiver={receiver_id}', 'MessageController@showUsersMessage')->name('Show Messages between users');
});

# Report
Route::prefix('report')->middleware('auth:api')->group(function ($router) {
    Route::get('/receiver={receiver_id}/count', 'MessageController@reportCount')->name('Count user Reported');
    Route::get('/receiver={receiver_id}/all', 'MessageController@showReports')->name('Show all reports of an user');
});

# Admin
Route::prefix('admin')->middleware('auth:api')->group(function ($router) {
    Route::get('user', 'AdminController@getUserList');
    Route::post('/user/{user}', 'AdminController@changeAccountStatus');
    Route::get('/user/{user}', 'AdminController@showDetailOfAUser');
});

# Employer
Route::post('employer/createRecruitment', 'EmployerController@createRecruitment')->middleware('auth:api');
Route::get('employer/recruitments', 'EmployerController@showRecruitments')->middleware('auth:api');
Route::post('employer/recruitments', 'EmployerController@changeStatusOfApplication')->middleware('auth:api');
Route::get('employer/getApplications', 'EmployerController@getApplications')->middleware('auth:api');

# Jobseeker
Route::post('jobseeker/follow', 'JobSeekerController@followRecruitment')->middleware('auth:api');
Route::post('jobseeker/unfollow', 'JobSeekerController@unfollowRecruitment')->middleware('auth:api');
Route::post('jobseeker/apply', 'JobSeekerController@applyRecruitment')->middleware('auth:api');
Route::post('jobseeker/unApply', 'JobSeekerController@UnApplyRecruitment')->middleware('auth:api');
Route::get('/jobseeker/interestedRecruitments', 'JobSeekerController@interestedRecruitments')->middleware('auth:api');

# Category
Route::get('/category', 'CategoryController@search');

#Recruitment
Route::get('recruitment', 'RecruitmentController@showAllRecruitment');
Route::get('recruitment/{recruitmentId}', 'RecruitmentController@show');

# User
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::post('/change-password ', [AuthController::class, 'changePassWord']);
    Route::post('/user-profile', [AuthController::class, 'updateProfile']);
});
