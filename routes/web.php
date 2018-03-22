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

//Auth::routes();
Route::get('/login', function(){return redirect('/login/google');})->name('login');
Route::get('/login/{provider_name}', 'Auth\LoginController@redirectToProvider');
Route::get('/login/{provider_name}/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/manage', 'Auth\UserController@manage')->name('manage_user');
Route::post('/manage', 'Auth\UserController@update')->name('update_user');
