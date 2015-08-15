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
Route::group(['prefix' => '/api/v1'], function () {
    Route::controller('tag','TagController');
    
    Route::resource('snippet','SnippetController');
    Route::get('search','KeywordsController@index');
    
    Route::post('feedback','FeedbackController@send');

    Route::group(['middleware' => 'auth'], function() {
        Route::post('images/upload', 'ImageController@upload');
        Route::resource('snippet/{snippetId}/comment', 'CommentController');
        Route::put('snippet/draft/{id?}', 'SnippetController@saveDraft');
        Route::put('workbook/{id}/rename','WorkbookController@rename');
        Route::get('workbook/permission/{workbookId}','WorkbookController@showPermission');
        Route::put('workbook/permission/{workbookId}','WorkbookController@grantPermission');
        Route::resource('workbook', 'WorkbookController');

        Route::post('follow', 'FollowController@follow');
        Route::delete('follow', 'FollowController@unfollow');
    });
    


    // Account routing
    // All kinds of user auth is using in this method
    Route::controller('account','AccountController');

    Route::get("test",function() {
        $u = \App\Model\Account::all();
        echo "<pre>";
        echo "id,name,email,level,locate,lang,google_id,created_at,updated_at\n";
        foreach ($u as $key => $value) {
            echo "{$value->id},{$value->name},{$value->email},{$value->level},{$value->locate},{$value->lang},{$value->google_id},{$value->created_at},{$value->updated_at}\n";
        }
        echo "</pre>";
    });
});

// extension
Route::group(['middleware' => 'auth'], function() {
    Route::resource('copy/snippet', 'ExtensionController');
});


Route::get('/thisistest/json/get', 'HomeController@getPlayground');
Route::post('/thisistest/json/post', 'HomeController@postPlayground');

// view search logs
Route::get('/log/kws', 'LogController@getKeywordLog');


Route::get('/_p/', function(){
	return redirect('/');
});

// old version redirect
Route::get('/_p/snippet', function() { return redirect('/snippet'); });
Route::get('/_p/snippet/{id?}', function($id = '') { return redirect('/snippet/'.$id); });
Route::get('/snippets', function() { return redirect('/snippet'); });
Route::get('/snippets/{id?}', function($id = '') { return redirect('/snippet/'.$id);});

// AngularJS 
Route::group(['middleware' => ['auth.autologin']], function() {

    Route::get('/snippet/{a?}/{b?}/{c?}', 'HomeController@index');
    Route::get('/', 'HomeController@index');

});
