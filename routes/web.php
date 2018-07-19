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

Route::get('weather', 'WeatherController@index')->name('weather.index');
Route::get('cron', 'WeatherController@mail')->name('weather.mail');
Route::post('weather/{city}', 'WeatherController@setSelected')->name('weather.setSelected');
Route::post('store', 'WeatherController@store')->name('weather.store');

