<?php
Route::pattern("snippet",'^(\d+)$');
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
Route::group(['prefix' => '/api/v1'], function () {
    Route::controller('tag','TagController');
    
    Route::resource('snippet','SnippetController');
    Route::get('search','KeywordsController@index');
    
    Route::post('feedback','FeedbackController@send');

    Route::get("workbook/{workbookId}/search", "WorkbookController@search");
    Route::resource('workbook', 'WorkbookController');
    Route::resource('snippet/{snippetId}/comment', 'CommentController');
    

    Route::group(['middleware' => 'auth'], function() {
        
        Route::get('notification','NotificationController@index');
        Route::post('notification','NotificationController@read');

        Route::post('images/upload', 'ImageController@upload');
        
        Route::put('snippet/fork','SnippetController@fork');
        Route::put('snippet/draft/{id?}', 'SnippetController@saveDraft');

        Route::resource("news", "NewsController");


        Route::get('workbook/permission/{workbookId}','WorkbookController@showPermission');
        Route::put('workbook/permission/{workbookId}','WorkbookController@grantPermission');
        
        Route::post('follow', 'FollowController@follow');
        Route::delete('follow', 'FollowController@unfollow');
    });
    
    Route::resource('profile', 'ProfileController');

    // Account routing
    // All kinds of user auth is using in this method
    Route::get('account/userinfo','AccountController@getUserinfo');
    
});

Route::get('/account/oauth2callback', 'AccountController@getOauth2callback');
Route::group(['prefix' => '/auth'], function() {
    // Authentication routes...
    Route::get('login', 'AuthController@getLogin');
    Route::post('login', 'AuthController@postLogin');
    Route::get('logout', 'AuthController@getLogout');

    // Registration routes...
    Route::get('register', 'AuthController@getRegister');
    Route::post('register', 'AuthController@postRegister');

    Route::get('errors', 'AuthController@getErrorAuth');
});

// extension
Route::group(['middleware' => 'auth'], function() {
    Route::resource('copy/snippet', 'ExtensionController');
});


Route::get('/thisistest/json/get', 'HomeController@getPlayground');
Route::post('/thisistest/json/post', 'HomeController@postPlayground');

// view search logs
Route::get('/log/kws', 'LogController@getKeywordLog');


// Route::get('/_p/', function(){
// 	return redirect('/');
// });

// // old version redirect
// Route::get('/_p/snippet', function() { return redirect('/snippet'); });
// Route::get('/_p/snippet/{id?}', function($id = '') { return redirect('/snippet/'.$id); });
// Route::get('/snippets', function() { return redirect('/snippet'); });
// Route::get('/snippets/{id?}', function($id = '') { return redirect('/snippet/'.$id);});

// // AngularJS 
// Route::group(['middleware' => ['auth.autologin']], function() {

//     Route::get('/snippet/{a?}/{b?}/{c?}', 'HomeController@index');
//     Route::get('/', 'HomeController@index');

// });
