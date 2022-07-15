<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Auth\Events\Verified;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AdminVerifyEmailRequest;
use App\Http\Responses\AdminVerifyEmailResponse;
use Illuminate\Support\Carbon;
use App\Models\Admin;

class VerifyEmailController extends Controller
{
    public function __construct(StatefulGuard $guard)
    {
        $this->guard = $guard;
    }
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  App\Http\Requests\AdminVerifyEmailRequest;  $request
     * @return \Laravel\Fortify\Contracts\VerifyEmailResponse
     */
    public function __invoke(AdminVerifyEmailRequest $request)
    {
        $admin = Admin::where('id', $request->id)->first();
        if ($admin->hasVerifiedEmail()) {
            return app(AdminVerifyEmailResponse::class);
        }

        if ($admin->markEmailAsVerified()) {
            $admin->fill(['email_verified_at' => Carbon::now()]);
            $admin->save();
        }

        return app(AdminVerifyEmailResponse::class);
    }
}
