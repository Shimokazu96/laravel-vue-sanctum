<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\NoteToUser;

class UserProfileInformationController extends Controller
{
    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Fortify\Contracts\UpdatesUserProfileInformation  $updater
     * @return \Illuminate\Http\Response
     */
    public function update(
        Request $request,
        User $user,
        UpdatesUserProfileInformation $updater
    ) {
        $updater->update($user, $request->all());
        return $request->wantsJson()
            ? new JsonResponse(200)
            : back()->with('status', 'profile-information-updated');
    }

    /**
     * ユーザープロフィール
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function UserProfileShow($id)
    {
        $user_profile = User::where('id', $id)->with('user_profile')->withTrashed()->first();
        return response()->json($user_profile, 200) ?? abort(404);
    }
    /**
     * // 追加or更新処理
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function UserProfileUpdate(Request $request, UserProfile $user_profile, User $user)
    {
        $profile_info = $request->all();
        $profile_info['user_id'] = $user->id;
        $user_profile->updateOrCreate(['user_id' => $user->id], $profile_info);

        return response()->json($user->user_profile()->first(), 200) ??  response()->json([], 500);
    }

    /**
     * ユーザーへのメモ
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function NoteToUserShow($id)
    {
        $note_to_user = User::where('id', $id)->with('note_to_user')->withTrashed()->first();
        return response()->json($note_to_user, 200) ?? abort(404);
    }
    /**
     * // 追加or更新処理
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function NoteToUserUpdate(Request $request, NoteToUser $note_to_user, User $user)
    {
        $profile_info = $request->all();
        $profile_info['user_id'] = $user->id;
        $note_to_user->updateOrCreate(['user_id' => $user->id], $profile_info);

        return response()->json($user->note_to_user()->first(), 200) ??  response()->json([], 500);
    }
}
