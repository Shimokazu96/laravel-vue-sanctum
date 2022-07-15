<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Fortify;
use Illuminate\Validation\ValidationException;
use App\Models\Admin;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminTwoFactorAuthPassword;
use Illuminate\Support\Facades\Hash;

class AdminLoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $random_password = '';

        for($i = 0 ; $i < 5 ; $i++) {

            $random_password .= strval(rand(0, 9));

        }

        $admin = Admin::where('email', $request->email)->first();
        $admin->two_factor_auth_token = Hash::make($random_password);            // 5桁のランダムな数字
        $admin->two_factor_auth_expiration = now()->addMinutes(10);  // 10分間だけ有効
        $admin->save();

        // メール送信
        Mail::to($admin)->send(new AdminTwoFactorAuthPassword($random_password));

        return response()->json(['admin_id' => $admin->id] ?? abort(404));
        // $admin = Auth::guard('admin')->user();
        // return $request->wantsJson()
        //     ? response()->json($admin, 200)
        //     : redirect()->intended(Fortify::redirects('login'));
    }
}
