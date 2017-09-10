<?php

use Illuminate\Support\Facades\Route;

// Balance routes...
Route::get('/balance', 'BalanceController@index');

// Charge routes...
Route::get('/charges', 'ChargeController@index');
Route::get('/charges/{id}', 'ChargeController@show');

Route::get('/', 'HomeController@index');
Route::get('{view}', 'HomeController@index')->where('view', '(.*)');
