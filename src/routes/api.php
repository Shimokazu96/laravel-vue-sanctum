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

// 認証不要
Route::group([
    'namespace' => 'App\Http\Controllers',
], function () {
    Route::get('/user-list', 'UserController@index')->name('user.list');
});

Route::get('/', function () {
    return "Hello World";
});
Route::get('/reflesh-token', function (Illuminate\Http\Request $request) {
    $request->session()->regenerateToken();

    return response()->json();
});


// user ログイン認証後
Route::group([
    'namespace' => 'App\Http\Controllers',
    'middleware' => ['auth:sanctum']
], function() {
    Route::get('/user',function (Request $request) {
        return Auth::guard('web')->user();
    })->name('user');
    // ユーザー詳細
    Route::get('/user/{user}', 'UserController@show')->name('user.show');
    Route::put('/user/{user}/update', 'UserController@update')->name('user.update');
    Route::get('/', function (Request $request) {
        return "hello";
    });
    //メールアドレス更新
    Route::put('/user/profile-information', 'ProfileInformationController@update')->name('user-profile-information.update'); // Laravel\Fortify\Http\Controllers\ProfileInformationControllerからコピー

    Route::put('/user/{user}/follow', 'UserController@follow')->name('user.follow');
    Route::delete('/user/{user}/follow', 'UserController@unfollow')->name('user.unfollow');
});
