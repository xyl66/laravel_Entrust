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

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/test', 'HomeController@test')->name('test');
Route::group(['prefix' => 'admin','namespace' => 'Admin'], function() {
    Route::resource('role', 'RoleController');
    Route::put('role','RoleController@update');
    Route::delete('role','RoleController@destroy');
    Route::get('/', function(){
    	return redirect()->route('role');
    });
    Route::resource('user','UserController');
    Route::put('user','UserController@update');
    Route::delete('user','UserController@destroy');
    Route::resource('permission','PermissionController');
    Route::put('permission','PermissionController@update');
    Route::delete('permission','PermissionController@destroy');
    Route::get('/manage', ['middleware' => ['permission:manage-admins'], 'uses' => 'AdminController@manageAdmins']);
});