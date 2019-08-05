<?php

Auth::routes(['verify' => true]);
Route::get('/', function () {
	return view('welcome');
});
Route::get('/home', 'HomeController@home')->name('home')->middleware('verified');
	
// Thou shalt be logged in hereonbelow
Route::group(['middleware' => ['auth']], function () {

Route::get( 'clients/clients/{iUserID}',
	'EditController@clients')                    ->name('clients-edit');
Route::post('clients/clients/save',
	'EditController@clientssave')                    ->name('clients-edit-save');
Route::get( 'clients/clients',
	'GridController@clients')                    ->name('clients-clients');
Route::get( 'clients/new',
	'GridController@newclients')             ->name('clients-new');
Route::get( 'clients/upload', 
	'ClientsController@upload')                  ->name('clients-upload');
Route::get( 'clients/upload/new/{sAction}',  
	'ClientsController@uploadnew')               ->name('clients-uploadednew');
Route::get( 'clients/uploaded', 
	'ClientsController@uploaded')                ->name('clients-uploaded');
Route::get( 'clients/new/import',
	'ClientsController@newclientsimport')           ->name('clients-new-import');
Route::post('clients/new/import', 
	'ClientsController@newclientsimportsave')       ->name('clients-new-import-save');


Route::get( 'user/changepassword',
	'UserController@password')                   ->name('user-password');
Route::post('user/password', 
	'UserController@passwordsave')               ->name('user-password-save');
Route::get( 'user/personaldetails', 
	'UserController@personaldetails')            ->name('user-personaldetails');
Route::post('user/personaldetails', 
	'UserController@personaldetailssave')        ->name('user-personaldetails-save');
	
});		