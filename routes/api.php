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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('users', 'ApiController@index');
Route::get('users/{id}', 'ApiController@show');
Route::post('create', 'ApiController@store');
Route::put('profile/{id}', 'ApiController@update');
Route::delete('delete/{id}', 'ApiController@delete');

//Invite Email User
Route::post('inviteusr', 'ApiController@inviteusr');
Route::post('login', 'ApiController@login');