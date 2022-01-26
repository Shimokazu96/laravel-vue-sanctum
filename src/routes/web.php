<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResetPasswordController;
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

// Route::get('/{any}', function() {
//     return view('app');
// })->where('any', '.*');
Route::get('/', function () {
  return view('index');
})->name('login');

Route::get('/reset-password/{token}', ResetPasswordController::class)
  ->name('password.reset');

Route::get('/{any?}', fn () => view('index'))->where('any', '.+');
