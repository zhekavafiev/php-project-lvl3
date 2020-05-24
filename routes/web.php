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

Route::get('/', 'DomainController@index')->name('index');

Route::get('/domains', 'DomainController@show')->name('domains');

Route::post('/', 'DomainController@save')->name('save');

Route::get('/domains/{id}', 'DomainController@view');

Route::post('/domains/{id}/check', 'DomainController@check');
