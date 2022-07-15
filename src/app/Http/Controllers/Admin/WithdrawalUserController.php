<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Events\Registered;
use App\Actions\Fortify\CreateNewUser;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class WithdrawalUserController extends Controller
{
    /**
     * 退会ユーザー一覧
     *
     */
    public function index(Request $request, User $user)
    {
        $per_page = 10;
        if ($request->per_page) {
            $per_page = $request->per_page;
        }
        if ($request->query()) {
            $users = $user::serach($request->query())->onlyTrashed()->paginate($per_page);
        } else {
            $users = $user->onlyTrashed()->paginate($per_page);
        }
        return response()->json(["users" => $users, "params" => $request->query()] ?? abort(404));
    }
}
