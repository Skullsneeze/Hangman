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
Route::get('/play/{game?}', 'PlayController@index')->name('play');
Route::get('/play/{game}/guess/{char}', 'PlayController@update')->name('guess');
Route::get('/load', 'LoadController@index')->name('load');
