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

use Illuminate\Support\Facades\Route;

Route::get('/', 'MainPageController@index')->name('main.index');
Route::post('/', 'MainPageController@store')->name('store');
Route::get('/domains', 'DomainController@index')->name('domains.index');
Route::get('/domains/{id}', 'DomainController@show')->name('domain');
Route::post('/domains/{id}/check', 'DomainCheckController@check')->name('check');

Route::get('/123', function () {
    return view('test');
});
