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


// Route::prefix('api/admin')


Route::group([
    'namespace' => 'App\Http\Controllers\Admin',
], function () {
    Route::post('/register', 'RegisteredAdminController@store');
    Route::post('/first-auth', 'LoginController@FirstAuth');
    Route::post('/second-auth', 'LoginController@SecondAuth');
    Route::get('/email/verify/{id}/{hash}', 'VerifyEmailController@__invoke')->name('admin.verification.verify');
});

// admin ログイン認証後
Route::group([
    'namespace' => 'App\Http\Controllers\Admin',
    'middleware' => ['auth:sanctum']
], function () {
    // ログインチェック
    Route::get('/', function (Request $request) {
        return Auth::guard('admin')->user();
    })->name('admin');
    Route::post('/logout', 'LoginController@destroy');
    //メールアドレス更新
    Route::put('/profile-information', 'ProfileInformationController@update');
    Route::put('/password', 'PasswordController@update');
    Route::post('/email/verification-notification', 'EmailVerificationNotificationController@store');

    Route::group(['middleware' => ['can:isAdmin']], function () {
        // 管理者管理
        Route::apiResource('/admins', AdminController::class);
    });
    // 運営管理者ユーザー管理
    Route::apiResource('/users', UserController::class);
    Route::put('/users/{user}/infomation', 'UserController@userInfomationUpdate')->name('user.infomation');
    //メールアドレス更新
    Route::put('/users/{user}/profile-information', 'UserProfileInformationController@update');
    Route::put('/users/{user}/password', 'UserPasswordController@update');
    //退会ユーザー一覧
    Route::get('/withdrawal', 'WithdrawalUserController@index');
});

