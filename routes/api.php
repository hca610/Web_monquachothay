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


Route::get('/user', 'UserController@search');
Route::post('/user/{user}', 'UserController@banUser');
Route::get('/user/{user}','UserController@show');

Route::apiResource('/employer','EmployerController');
Route::get('/employer', 'EmployerController@search');
Route::post('/employer/create', 'EmployerController@store');
Route::put('/employer/{employer}', 'EmployerController@show');

Route::get('/jobseeker','JobSeekerController@search');
Route::post('/jobseeker/create', 'JobSeekerController@store');
Route::get('/jobseeker/{jobseeker}','JobSeekerController@show');
Route::put('/jobseeker/{jobseeker}', 'JobSeekerController@update');

Route::get('/category', 'JobSeekerController@search');
