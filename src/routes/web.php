<?php

use Illuminate\Support\Facades\Route;
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

Route::get('/', function () {
  return view('index'); //the view for the spa base-html
})->name('login');

Route::get('/reset-password/{token}', function () {
  return view('index');     //the view for the spa base-html
})->name('password.reset'); //laravel needs a route with this name for password reset
Route::get('/{any?}', fn () => view('index'))->where('any', '.+');
