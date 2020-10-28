<?php

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/dashboard', 'DashboardController@index');
Route::get('/', 'DashboardController@index');
Auth::routes();