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

// Subscription routes...
Route::get('/subscriptions', 'SubscriptionController@index');
Route::get('/subscriptions/{id}', 'SubscriptionController@show');

// Invoice routes...
Route::get('/invoices', 'InvoiceController@index');
Route::get('/invoices/{id}', 'InvoiceController@show');
Route::get('/invoices/{type}/{id}', 'InvoiceController@list');

Route::get('/', 'HomeController@index');
Route::get('{view}', 'HomeController@index')->where('view', '(.*)');
