<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/home', 'HomeController@index')->name('home');

//Auth::routes();
Route::get('/login', function(){return redirect('/login/google');})->name('login');
Route::get('/login/{provider_name}', 'Auth\LoginController@redirectToProvider');
Route::get('/login/{provider_name}/callback', 'Auth\LoginController@handleProviderCallback');
Route::match(array('GET', 'POST'),'/logout', 'Auth\LoginController@logout')->name('logout');

//User::routes();
//Route::get('/user/{user_id}/edit', 'Auth\UserController@edit')->name('edit_user');
//Route::post('/user', 'Auth\UserController@update')->name('update_user');
Route::patch('users/{user}/roles', 'Auth\UserController@updateRoles')->name('users.roles');
Route::resource('users', 'Auth\UserController');

//Room::routes()
Route::resource('rooms', 'RoomsController');

//Chat::routes()
Route::get('chats/', 'ChatPostsController@index')->name('chats');
Route::post('chats/', 'ChatPostsController@store')->name('chats.store');
Route::get('chats/{room}', 'ChatPostsController@show')->name('chats.show');
Route::get('chats/logout/{room}', 'ChatPostsController@logout')->name('chats.logout');
