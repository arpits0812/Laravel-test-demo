<?php

/*
   Web Routes
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');


// Auth scaffolding
Auth::routes();

//Register Routes
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register')->middleware('hasInvitation');
Route::post('/getotp', 'Auth\RegisterController@generateOTP')->name('getotp');

//user profile
Route::get('/profileuser', 'ProfileController@index')->name('profileuser');

Route::post('/usrUpdate', 'ProfileController@UserProfileup')->name('usrUpdate');


//Admin Routes
Route::group(['middleware' => ['auth', 'admin'], 'prefix' => 'invitations'], function() {
    Route::get('', 'InvitationsController@index')->name('showInvitations');
});

Route::group(['middleware' => ['auth', 'admin'], 'prefix' => 'request'], function() {
    Route::get('', 'InvitationsController@requestInvitation')->name('requestInvitation');
});

Route::group(['middleware' => ['auth', 'admin'], 'prefix' => 'profile'], function() {
    Route::get('', 'InvitationsController@profile')->name('profile');
});

//invite send
Route::group(['middleware' => ['auth', 'admin'], 'prefix' => 'invitations'], function() {
    Route::post('', 'InvitationsController@store')->name('storeInvitation');
});


Route::group(['middleware' => ['auth', 'admin'], 'prefix' => 'profileUpdate'], function() {
    Route::post('', 'InvitationsController@profileUpdate')->name('profileUpdate');
});


