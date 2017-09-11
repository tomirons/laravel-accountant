<?php

use Illuminate\Support\Facades\Route;

// Balance routes...
Route::get('/balance', 'BalanceController@index');

// Charge routes...
Route::get('/charges', 'ChargeController@index');
Route::get('/charges/{id}', 'ChargeController@show');

// Customer routes...
Route::get('/customers', 'CustomerController@index');
Route::get('/customers/{id}', 'CustomerController@show');

Route::get('/', 'HomeController@index');
Route::get('{view}', 'HomeController@index')->where('view', '(.*)');
