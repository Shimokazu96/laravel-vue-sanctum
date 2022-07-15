<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AdminRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Events\Registered;
use App\Actions\Admin\CreateNewAdmin;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * ユーザー一覧
     *
     */
    public function index()
    {
        if( Auth::guard('admin')->check()) {
            $admins = Admin::where("id" , "!=" , Auth::user()->id)->get();
        }
        return response()->json($admins, 200) ?? abort(404);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, CreateNewAdmin $creator)
    {
        event(new Registered($admin = $creator->create($request->all())));

        return response()->json(200) ?? abort(404);
    }
    /**
     * ユーザー詳細
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        return Admin::where('id', $admin->id)->first() ?? abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdminRequest $request, Admin $admin)
    {
        if ($request->email !== $admin->email &&
            $admin instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($admin, $request);
        } else {
            $admin->forceFill([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'available' => $request->available,
            ])->save();
        }
        return Admin::where('id', $admin->id)->first() ?? abort(404);
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    protected function updateVerifiedUser($admin, Request $request)
    {
        $admin->forceFill([
            'name' => $request->name,
            'email' => $request->email,
            'email_verified_at' => null,
            'available' => $request->available,
            'password' => Hash::make($request->password),
        ])->save();

        $admin->sendEmailVerificationNotification();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        $admin->delete();
        return response()->json(200) ?? abort(404);
    }
}
