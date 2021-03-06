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

Route::middleware(['web.headers'])->group(function () {
    Route::get('/', 'HomeController@index')->name('welcome');
    Route::get('/about', 'HomeController@about')->name('about');
});

Route::middleware(['web.headers', 'auth'])->group(function () {
    Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');
    Route::get('/consumers', 'HomeController@consumers')->name('consumers');
});

