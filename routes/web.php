<?php

use Illuminate\Support\Facades\Route;

// Balance routes...
Route::get('/api/balance', 'BalanceController@index');

// Charge routes...
Route::get('/api/charges', 'ChargesController@index');

Route::get('/', 'HomeController@index');
Route::get('{view}', 'HomeController@index')->where('view', '(.*)');
