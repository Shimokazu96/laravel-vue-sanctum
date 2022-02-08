<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group([
    'namespace' => 'App\Http\Controllers',
    'middleware' => ['auth:sanctum']
], function() {
    Route::get('/user',function (Request $request) {
        return Auth::user();
    })->name('user');
    // ユーザー詳細
    Route::get('/user/{user}', 'UserController@show')->name('user.show');
    Route::put('/user/{user}/update', 'UserController@update')->name('user.update');
    Route::get('/', function (Request $request) {
        return "hello";
    });
});

// トークンリフレッシュ
Route::get('/reflesh-token', function (Illuminate\Http\Request $request) {
    $request->session()->regenerateToken();

    return response()->json();
});
