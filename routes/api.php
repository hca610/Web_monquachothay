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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::post('categories', 'CategoryController@index');
// Route::resource('user', UserController::class);
// Route::resource('employer', EmployerController::class);

Route::resource('/notification', NotificationController::class);
Route::get('/notification/user/{user_id}', function($user_id) {
    return App\Http\Controllers\NotificationController::showUserNoti($user_id);
})->name('notification.showUserNoti');

Route::resource('/message', MessageController::class);
Route::get('/message/user/sender={sender_id}', function($sender_id) {
    $receiver_id = 0;
    return App\Http\Controllers\MessageController::showUserMessage($sender_id, $receiver_id);
})->name('message.showUserMessagebySender');
Route::get('/message/between/sender={sender_id}&receiver={receiver_id}', function($sender_id, $receiver_id) {
    return App\Http\Controllers\MessageController::showUserMessage($sender_id, $receiver_id);
})->name('message.showUserMessageBetweenUsers');
Route::get('/report/{receiver_id}', function($receiver_id) {
    return App\Http\Controllers\MessageController::reportCount($receiver_id);
})->name('message.reportCouter');