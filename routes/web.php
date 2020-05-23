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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/1', function () {
//     return 'Hi';
// });

use App\Http\Controllers\DomainController;

Route::get('/', 'DomainController@index');

Route::get('/domains', 'DomainController@show')->name('domains');

Route::post('/', 'DomainController@validateForm');

Route::get('/domains/{id}', 'DomainController@view');
