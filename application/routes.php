<?php
Route::get('/', function()
{
  if(Auth::guest()){
    return View::make('home.index');
  }else{
    return View::make('home.main')->with('post_stream', Post::get_posts());
  }
});

#User_Control Routes
// Route::get('home/main', array('as'=>'home/main', 'uses'=>'home@main'));
Route::get('logout', array('as'=>'logout', 'uses'=>'user_control@logout'));

#Ajax Controller Routes
Route::post('ajax/validate_registration', array('as'=>'ajax/validate_registration', 'uses'=>'ajax@validate_registration'));
Route::post('ajax/validate_login', array('as'=>'ajax/validate_login', 'uses'=>'ajax@validate_login'));
Route::post('ajax/compose_post', array('as'=>'ajax/compose_post', 'uses'=>'ajax@compose_post'));
Route::post('ajax/upload_photo_post', array('as'=>'ajax/upload_photo_post', 'uses'=>'ajax@upload_photo_post'));
Route::post('ajax/inserted_post', array('as'=>'ajax/inserted_post', 'uses'=>'ajax@inserted_post'));

Route::post('ajax/delete_post', array('as'=>'ajax/delete_post', 'uses'=>'ajax@delete_post'));

Route::post('ajax/add_comment', array('as'=>'ajax/add_comment', 'uses'=>'ajax@add_comment'));
Route::post('ajax/edit_comment', array('as'=>'ajax/edit_comment', 'uses'=>'ajax@edit_comment'));

Route::post('ajax/test_push', array('as'=>'ajax/test_push', 'uses'=>'ajax@test_push'));

#Profile Controller Routes
Route::get('profile/(:num)', array('as' => 'profile/(:num)', 'uses'=>'profile@index'));

/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
|
| To centralize and simplify 404 handling, Laravel uses an awesome event
| system to retrieve the response. Feel free to modify this function to
| your tastes and the needs of your application.
|
| Similarly, we use an event to handle the display of 500 level errors
| within the application. These errors are fired when there is an
| uncaught exception thrown in the application.
|
*/

Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function()
{
	return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in before and after filters are called before and
| after every request to your application, and you may even create
| other filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|		Route::filter('filter', function()
|		{
|			return 'Filtered!';
|		});
|
| Next, attach the filter to a route:
|
|		Router::register('GET /', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

Route::filter('before', function()
{
	// Do stuff before every request to your application...
});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Route::filter('non-auth', function()
{
  if (Auth::guest()) return Redirect::to('login');
});

Route::filter('auth', function()
{
  if (Auth::user()) return Redirect::to('dashboard');
});
