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

Auth::routes();
Route::get('logout', 'Auth\LoginController@logout');
Route::get('register', function() {abort(404);});
Route::post('register', function() {abort(404);});

Route::group(['middleware' => ['auth']], function() {
	Route::get('/', function () {return view('app');})->name('app');

	Route::group(['prefix' => 'api'], function() {
		Route::resource('users', 'ApiUserController', ['only' => [
			'index', 'store', 'update', 'destroy'
		]]);

		Route::resource('spendings', 'ApiSpendingController', ['only' => [
			'index', 'store', 'update', 'destroy'
		]]);

		Route::resource('categories', 'ApiCategoryController', ['only' => [
			'index', 'store', 'update', 'destroy'
		]]);

		Route::get('charts/spendings', 'ApiChartController@spendings');
		Route::get('charts/dividing', 'ApiChartController@dividing');
	});
});
