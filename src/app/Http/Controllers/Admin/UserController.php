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

class UserController extends Controller
{
    /**
     * ユーザー一覧
     *
     */
    public function index(Request $request, User $user)
    {
        $per_page = 10;
        if ($request->per_page) {
            $per_page = $request->per_page;
        }
        if ($request->query()) {
            $users = $user::serach($request->query())->paginate($per_page);
        } else {
            $users = $user->paginate($per_page);
        }
        return response()->json(["users" => $users, "params" => $request->query()] ?? abort(404));
    }

    /**
     * Store a newly created resource in storage.
     *  ユーザー登録
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, CreateNewUser $creator)
    {
        event(new Registered($user = $creator->create($request->all())));

        return response()->json(200) ?? abort(404);
    }


    /**
     * Update the specified resource in storage.
     *  パスワード・メールアドレス更新
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if (
            $request->email !== $user->email &&
            $user instanceof MustVerifyEmail
        ) {
            $this->updateVerifiedUser($user, $request);
        } else {
            $user->forceFill([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'available' => $request->available,
            ])->save();
        }
        return User::where('id', $user->id)->first() ?? abort(404);
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    protected function updateVerifiedUser($user, Request $request)
    {
        $user->forceFill([
            'name' => $request->name,
            'email' => $request->email,
            'email_verified_at' => null,
            'available' => $request->available,
            'password' => Hash::make($request->password),
        ])->save();

        $user->sendEmailVerificationNotification();
    }

    /**
     * Remove the specified resource from storage.
     *  削除
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(200) ?? abort(404);
    }

    /**
     * ユーザー詳細情報
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::where('id', $id)->with('user_detail')->withTrashed()->first() ?? abort(404);
    }
    /**
     * Store a newly created resource in storage.
     *  ユーザー詳細情報更新
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function userInfomationUpdate(UserRequest $request, User $user)
    {
        $user->fill([
            "name"  => $request->name,
        ]);
        $user->save();
        $user_detail = $user->user_detail()->first();

        if ($request->File('icon_image')) {
            $old_image_path = $user_detail->icon_image;

            // 投稿写真の拡張子を取得する
            $extension = $request->icon_image->extension();
            $file_name = Str::random(32) . '.' . $extension;
            Storage::disk('s3')
                ->putFileAs('', $request->icon_image, $file_name, 'public');

            //古い画像を削除
            Storage::disk('s3')->delete($old_image_path);

            $user_detail->icon_image = $file_name;
        }
        //icon_imageを除かないと名前が更新されてしまう
        $user_detail->fill($request->except(['name', 'icon_image']));
        $user_detail->save();

        return response()->json(User::where('id', $user->id)->with('user_detail')->first(), 200) ??  response()->json([], 500);
    }
}
