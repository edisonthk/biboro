<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Snippet and Tags routing
Route::controller('/json/tag','TagController');
Route::put('/json/snippet/draft/{id?}', ['middleware'=>'auth.login','uses'=>'SnippetController@saveDraft']);
Route::resource('/json/snippet','SnippetController');
Route::get('/json/search','KeywordsController@index');
Route::post('/json/images/upload', 'ImageController@upload');
Route::post('/json/feedback','FeedbackController@send');

Route::get('/thisistest/json/get', 'HomeController@getPlayground');
Route::post('/thisistest/json/post', 'HomeController@postPlayground');

// view search logs
Route::get('/log/kws', 'LogController@getKeywordLog');

// Account routing
// All kinds of user auth is using in this method
Route::controller('/account','AccountController');

Route::get('/_p/', function(){
	return redirect('/');
});

// old version redirect
Route::get('/_p/snippet', function() {
	return redirect('/snippet');
});
Route::get('/_p/snippet/{id?}', function($id = '') {
	return redirect('/snippet/'.$id);
});
// old version redirect
Route::get('/snippets', function() {
	return redirect('/snippet');
});
Route::get('/snippets/{id?}', function($id = '') {
	return redirect('/snippet/'.$id);
});

// AngularJS 
Route::get('/snippet/{a?}/{b?}/{c?}', 'HomeController@index');
Route::get('/', 'HomeController@index');
