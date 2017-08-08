<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@index');
Route::get('{view}', 'HomeController@index')->where('view', '(.*)');