<?php

Auth::routes(['verify' => true]);
Route::get('/', function () {return view('welcome');});
Route::get('/home', 'HomeController@home')->name('home')->middleware('verified');
	
// Thou shalt be logged in hereonbelow
Route::group(['middleware' => ['auth']], function () {

// Client menu
Route::get( 'form/page/{iPageID}',
	'ClientController@formpage')					->name('client-form-page');
Route::get( 'form/results',
	'ClientController@formresults')					->name('client-form-results');
Route::post('form/page/{iPageID}',
	'ClientController@formpageoptionsajax')			->name('client-form-page-options-ajax');
Route::get('form/submit',
	'ClientController@submitted')					->name('client-form-submitted');

// Admin menu
Route::get( 'admin/emailforms',
	'GridController@emailforms')					->name('admin-emailforms');
Route::get( 'admin/programmes',
	'GridController@programmes')					->name('admin-programmes');
Route::get( 'admin/uploads',
	'GridController@uploads')						->name('admin-uploads');
Route::get( 'admin/users',
	'GridController@users')							->name('admin-users');

// Clients menu
Route::get( 'clients/clients',
	'GridController@clients')						->name('clients-clients');
Route::get( 'clients/clients/{iUserID}',
	'EditController@clients')						->name('clients-edit');
Route::post('clients/clients/save',
	'EditController@clientssave')					->name('clients-edit-save');
Route::get( 'clients/download/{iUserID}',
	'ClientsController@download')					->name('clients-download');
Route::get( 'clients/new',
	'GridController@newclients')					->name('clients-new');
Route::get( 'clients/new/import',
	'ClientsController@newclientsimport')			->name('clients-new-import');
Route::post('clients/new/import',
	'ClientsController@newclientsimportsave')		->name('clients-new-import-save');
Route::get( 'clients/results/{iUserID}',
	'ClientsController@results')					->name('clients-results');
Route::get( 'clients/upload', 
	'ClientsController@upload')						->name('clients-upload');
Route::get( 'clients/uploaded', 
	'ClientsController@uploaded')					->name('clients-uploaded');
Route::get( 'clients/upload/new/{sAction}',
	'ClientsController@uploadnew')					->name('clients-uploadednew');

// Questions menu
Route::get( 'questions/categories',
	'GridController@questionscategories')			->name('questions-categories');
Route::get( 'questions/new',
	'GridController@newquestions')					->name('questions-new');
Route::get( 'questions/new/import',
	'QuestionsController@newquestionsimport')		->name('questions-new-import');
Route::post('questions/new/import',
	'QuestionsController@newquestionsimportsave')	->name('questions-new-import-save');
Route::get( 'questions/options',
	'GridController@questionsoptions')				->name('questions-options');
Route::get( 'questions/questionnaires',
	'GridController@questionnaires')				->name('questions-questionnaires');
Route::get( 'questions/questions',
	'GridController@questions')						->name('questions-questions');
Route::get( 'questions/upload',
		'QuestionsController@upload')				->name('questions-upload');
Route::get( 'questions/upload/new/{sAction}',
		'QuestionsController@uploadnew')			->name('questions-uploadednew');
Route::get( 'questions/uploaded',
		'QuestionsController@uploaded')				->name('questions-uploaded');

//User menu
Route::get( 'user/changepassword',
	'UserController@password')						->name('user-password');
Route::post('user/password', 
	'UserController@passwordsave')					->name('user-password-save');
Route::get( 'user/personaldetails', 
	'UserController@personaldetails')				->name('user-personaldetails');
Route::post('user/personaldetails', 
	'UserController@personaldetailssave')			->name('user-personaldetails-save');
	
});		