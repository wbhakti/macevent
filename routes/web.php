<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', 'App\Http\Controllers\HomeController@index');
Route::get('/login', 'App\Http\Controllers\UserController@login')->name('login');
Route::get('/logout', 'App\Http\Controllers\UserController@logout')->name('logout');
Route::post('/postlogin', 'App\Http\Controllers\UserController@postlogin');

Route::get('/register', 'App\Http\Controllers\RegisterController@register');
Route::post('/postregister', 'App\Http\Controllers\RegisterController@postregister');
Route::get('/dashboard/listregistration', 'App\Http\Controllers\RegisterController@listregistration');
Route::get('/getreportevent', 'App\Http\Controllers\RegisterController@reportregistration');
Route::post('/postbuktitransfer', 'App\Http\Controllers\RegisterController@postbuktitransfer');
Route::post('/postapproveuser', 'App\Http\Controllers\RegisterController@postapproveuser');
Route::get('/cekstatus', 'App\Http\Controllers\RegisterController@cekstatus');
Route::get('/statustransaksi/{id_transaksi}', 'App\Http\Controllers\RegisterController@statustransaksi')->name('statusTransaksi');

Route::get('/dashboard', 'App\Http\Controllers\HomeController@dashboard')->name('dashboard');
Route::get('/dashboard/slider', 'App\Http\Controllers\MasterController@slider');
Route::get('/dashboard/event', 'App\Http\Controllers\MasterController@event');
Route::get('/dashboard/kategori', 'App\Http\Controllers\MasterController@kategori');
Route::post('/postslider', 'App\Http\Controllers\MasterController@postslider');
Route::post('/postevent', 'App\Http\Controllers\MasterController@postevent');
Route::post('/postkategori', 'App\Http\Controllers\MasterController@postkategori');


