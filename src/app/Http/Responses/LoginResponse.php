<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Fortify;
use Illuminate\Validation\ValidationException;

class LoginResponse implements LoginResponseContract
{
  /**
   * Create an HTTP response that represents the object.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function toResponse($request)
  {
    $user = Auth::user();
    if ($user->hasVerifiedEmail()) {
      return $request->wantsJson()
        ? response()->json($user, 200)
        : redirect()->intended(Fortify::redirects('login'));
    }

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    throw ValidationException::withMessages([
      'email' => ['メール認証が終わっておりません'],
    ]);
  }
}
