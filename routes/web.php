<?php

use Illuminate\Support\Facades\Route;

// Dashboard Routes...
Route::get('/api/stats', 'StatsController@index');

Route::get('/', 'HomeController@index');
Route::get('{view}', 'HomeController@index')->where('view', '(.*)');
