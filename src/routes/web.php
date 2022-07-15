<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AwsPersonalizeController;
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
  return view('index');
})->name('login');

Route::get('/reset-password/{token}', function () {
  return view('index');
})->name('password.reset'); //Laravel Fortifyではパスワードリセットをするには名前付きのルートが必要のため

//管理画面は全てadmin/index.blade.phpに
Route::get('/admin/{any?}', fn () => view('admin.index'))->where('any', '.*');
//フロント画面はindex.blade.phpに
Route::get('/{any?}', fn () => view('index'))->where('any', '^(?!admin\/).*');
