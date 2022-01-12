<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;

class RegisterController extends Controller
{
    use RegistersUsers;

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' =>  $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $this->guard()->login($user);
        return response()->json($user, Response::HTTP_OK);
    }

    /**
     * オーバーライド（src/vendor/laravel/framework/src/Illuminate/Foundation/Auth/RegistersUsers.php）
     * 登録成功後は登録ユーザーの情報を返す
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    public function registered(Request $request, $user)
    {
        return $user;
    }
}
