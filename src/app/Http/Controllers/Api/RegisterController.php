<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' =>  $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        Auth::guard()->login($user);
        return response()->json($user, 201);
    }

    /**
     * オーバーライド（src/vendor/laravel/framework/src/Illuminate/Foundation/Auth/RegistersUsers.php）
     * 登録成功後は登録ユーザーの情報を返す
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    // public function registered(Request $request, $user)
    // {
    //     return $user;
    // }
}
