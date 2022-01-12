<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return response()->json($user, 200);
        }
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['パスワードまたはEmailが間違っています。'],
            ]);
        }
    }

    protected function authenticated(Request $request, $user)
    {
        return $user;
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();

        $request->session()->regenerateToken();
        return response()->json(['status_code' => 200, 'message' => 'Logged out'], 200);
    }
}
