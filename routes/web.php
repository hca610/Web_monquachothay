<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::resource('/category', 'CategoryController');

Route::resource('/recruitment', 'RecruitmentController');

Route::resource('/user', 'UserController')->except('update');
Route::post('/user/{user}', 'UserController@update')->name('user.update');
Route::post('/user', 'UserController@findUserByName')->name('user.findUserByName');
// Route::post('/user/{user}', 'UserController@banUser')->name('user.banUser');

Route::resource('/jobseeker', 'JobSeekerController');
Route::post('/jobseeker/{jobseeker}', 'JobSeekerController@update')->name('jobseeker.update');
