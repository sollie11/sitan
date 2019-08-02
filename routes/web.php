<?php

Auth::routes(['verify' => true]);
Route::get('/', function () {
	return view('welcome');
});
Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');
	
// Thou shalt be logged in hereonbelow
Route::group(['middleware' => ['auth']], function () {

	Route::get( 'user/changepassword',           'UserController@password')                    ->name('user-password');
	Route::post('user/password'      ,           'UserController@passwordsave')                ->name('user-password-save');
	
});		