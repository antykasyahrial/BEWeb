<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('user', 'UserApiController@store');
Route::get('user', 'UserApiController@index');
Route::get('user/{id}', 'UserApiController@show');
Route::put('user/{id}', 'UserApiController@update');
Route::delete('user/{id}', 'UserApiController@destroy');
Route::post('user/login', 'UserApiController@ceklogin');
Route::get('logout', 'UserApiController@logout');

Route::post('hospital', 'HospitalApiController@store');
Route::put('hospital/{id}', 'HospitalApiController@update');
Route::get('hospital', 'HospitalApiController@index');
Route::get('hospital/{id}', 'HospitalApiController@show');
Route::delete('hospital/{id}', 'HospitalApiController@destroy');

Route::post('tulisan', 'TulisanApiController@store');
Route::get('tulisan', 'TulisanApiController@index');
Route::get('tulisan/{id}', 'TulisanApiController@show');
Route::put('tulisan/{id}', 'TulisanApiController@update');
Route::delete('tulisan/{id}', 'TulisanApiController@destroy');

Route::get('edukasi', 'EdukasiApiController@index');
Route::get('edukasi/{id}', 'EdukasiApiController@show');
Route::post('edukasi', 'EdukasiApiController@store');
Route::put('edukasi/{id}', 'EdukasiApiController@update');
Route::delete('edukasi/{id}', 'EdukasiApiController@destroy');