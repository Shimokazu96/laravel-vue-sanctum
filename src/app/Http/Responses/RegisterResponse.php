<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Illuminate\Support\Facades\Auth;
use App\Models\UserDetail;



class RegisterResponse implements RegisterResponseContract
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

        $user_detail = new UserDetail();
        $user_detail->user_id = $user->id;
        $user_detail->save();

        return $request->wantsJson()
                    ? new JsonResponse($user, 201)
                    : redirect('/');
    }
}
