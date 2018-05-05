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
});

Auth::routes();

Route::get('/admin', 'Backend\AdminController@index')->name('admin');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('post/pdf_test','PostController@pdf_stream');
Route::get('generate-pdf', 'PostController@pdfview')->name('generate-pd');

Route::group( ['domain'=>'', 'prefix' => '' , 'middleware'=>'auth'] , function() {

	Route::resource('post','PostController', ['names'=>[]] );
	Route::resource('tag','TagController');
	Route::get('post','PostController@index');

});


Route::resource('category','CategoryController');


