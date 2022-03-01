<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        if(Auth::user()) {
            $users = User::where("id", "!=", Auth::user()->id)->with(['followers'])->get();
        } else {
            $users = User::with(['followers'])->get();
        }
        clock($users);
        foreach($users as $user) {
            clock($user);
            clock($user->followed_by_user);
        }
        return $users ?? abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * ユーザー詳細
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return User::where('id', $user->id)->with('user_detail')->first() ?? abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $user->fill([
            "name"  => $request->name,
        ]);
        $user->save();

        $user_detail = $user->user_detail()->first();

        if ($request->File('image')) {
            $extension = $request->image->extension();
            $file_name = Str::random(32) . '.' . $extension;
            $request->file('image')->storeAs('public', $file_name);
            $user_detail->image = $file_name;
        }
        $user_detail->fill($request->except(['name','image']));
        $user_detail->save();

        return response()->json(User::where('id', $user->id)->with('user_detail')->first(), 200) ??  response()->json([], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function follow(Request $request, User $user)
    {
        $followedUser = User::where('id', $user->id)->first();

        if ($followedUser->id === $request->user()->id) {
            return abort('404', 'Cannot follow yourself.');
        }

        $request->user()->followings()->detach($followedUser);
        $request->user()->followings()->attach($followedUser);

        return $followedUser;
    }

    public function unfollow(Request $request, User $user)
    {
        $followedUser = User::where('id', $user->id)->first();

        if ($followedUser->id === $request->user()->id) {
            return abort('404', 'Cannot follow yourself.');
        }

        $request->user()->followings()->detach($followedUser);

        return $followedUser;
    }
}
