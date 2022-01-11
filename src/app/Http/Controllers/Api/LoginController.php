<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return response()->json($user, 200);
        } else {
            return response()->json(['status_code' => 422, 'message' => 'Unauthorized'], 422);
        }
        throw new Exception('ログインに失敗しました。再度お試しください');
    }

    // protected function authenticated(Request $request, $user)
    // {
    //     return $user;
    // }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();

        $request->session()->regenerateToken();
        return response()->json(['status_code' => 200, 'message' => 'Logged out'], 200);
    }
}
