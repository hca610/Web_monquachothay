<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ImageController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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
Route::prefix('notification')->middleware('auth:api')->group(function ($router) {
    Route::get('', 'NotificationController@showAllNotifications')->name('Show All Notifications');
    Route::post('create', 'NotificationController@createNotification')->name('Create Notification');
    Route::post('update', 'NotificationController@updateNotification')->name('Update Notification');
    Route::get('notification', 'NotificationController@showNotification')->name('Show Notification');
    Route::get('user', 'NotificationController@showUserNotifications')->name('Show user Notifications');
    Route::get('user/status', 'NotificationController@showUserNotificationsByStatus')->name('Show User Notifications by Status');
    Route::get('user/status/count', 'NotificationController@countUserNotificationsByStatus')->name('Count User Notifications by Status');
});

# Chat
Route::prefix('chat')->middleware('auth:api')->group(function ($router) {
    Route::get('', 'MessageController@showAllChat')->name('Show All Chat Messages');
    Route::post('create', 'MessageController@createMessage')->name('Create Chat Message');
    Route::post('update', 'MessageController@updateMessage')->name('Update Chat Message');
    Route::get('message', 'MessageController@showMessage')->name('Show Chat Message');
    Route::get('between', 'MessageController@showChat')->name('Show Chat Between 2 users');
    Route::get('between/count', 'MessageController@countInChatByStatus')->name('Count Message in Chat by Status');
    Route::get('lastest', 'MessageController@showLastestChats')->name('Show Lastest Users Chats');
    Route::get('count/unseen', 'MessageController@countUserUnseenChatWith')->name('Count Users being UNSEEN');
});

# Report
Route::prefix('report')->group(function ($router) {
    # Do not need to login
    Route::get('to/{user_id}/count', 'ReportController@countReportstoUser')->name('Count Reports to User');
    Route::get('to/{user_id}', 'ReportController@showReportstoUser')->name('Show Reports to User');

    # Need to login
    Route::middleware('auth:api')->group(function ($router) {
        Route::get('', 'ReportController@showAllReports')->name('Show All Reports');
        Route::post('create', 'ReportController@createReport')->name('Create Report');
        Route::post('update', 'ReportController@updateReport')->name('Update Report');
        Route::get('report_id/{report_id}', 'ReportController@showReport')->name('Show Report');
        Route::get('from/{user_id}/count', 'ReportController@countReportsfromUser')->name('Count Reports from User');
        Route::get('from/{user_id}', 'ReportController@showReportsfromUser')->name('Show Reports from User');
    });
});

# Admin
Route::prefix('admin')->group(function ($router) {
    Route::get('user', 'AdminController@getUserList')->middleware('auth:api');
    Route::post('/user/{user}', 'AdminController@changeAccountStatus')->middleware('auth:api');
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

# Recruitment
Route::get('recruitment', 'RecruitmentController@showAllRecruitment');
Route::get('recruitment/{recruitmentId}', 'RecruitmentController@show');
Route::post('recruitment/{recruitmentId}', 'EmployerController@updateRecruitment')->middleware('auth:api');

# User
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [UserController::class, 'login'])->name('login');
    Route::post('/register', [UserController::class, 'register'])->name('register');
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    Route::post('/refresh', [UserController::class, 'refresh']);
    Route::get('/user-profile', [UserController::class, 'userProfile']);
    Route::post('/user-profile', [UserController::class, 'updateProfile']);
    Route::post('/change-password ', [UserController::class, 'changePassWord']);
    Route::post('/user-profile', [UserController::class, 'updateProfile']);
    Route::get('/users', [UserController::class, 'searchUser']);
    Route::post('upload-image', [ImageController::class, 'uploadImage']);
});

Route::get('get-image/{id}', [ImageController::class, 'getImage']);

// Guest
Route::get('/user/{user}', 'AdminController@showDetailOfAUser');
Route::get('/employers', 'EmployerController@showAllEmployer');

