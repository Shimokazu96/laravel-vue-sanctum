<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    // protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'followed_by_user',
        'deleted_at',
    ];

    protected $visible = [
        'id',
        'created_at',
        'deleted_at',
        'last_login_at',
        'name',
        'email',
        'email_verified_at',
        'available',
        'user_detail',
        'followed_by_user',
        'count_followings',
        'count_followers'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /** JSONに含める属性 */
    protected $appends = [
        'followed_by_user', 'count_followings', 'count_followers'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function user_detail()
    {
        return $this->hasOne('App\Models\UserDetail');
    }
    public function user_article()
    {
        return $this->hasMany('App\Models\UserArticle');
    }
    public function user_profile()
    {
        return $this->hasOne('App\Models\UserProfile');
    }
    public function note_to_user()
    {
        return $this->hasOne('App\Models\NoteTouser');
    }
    public function user_bank()
    {
        return $this->hasOne('App\Models\UserBank');
    }

    public function followers()
    {
        return $this->belongsToMany('App\Models\User', 'follows', 'followee_id', 'follower_id')->withTimestamps();
    }

    public function followings()
    {
        return $this->belongsToMany('App\Models\User', 'follows', 'follower_id', 'followee_id')->withTimestamps();
    }

    public function getFollowedByUserAttribute()
    {
        if (Auth::guest()) {
            return false;
        }
        return $this->followers->contains(function ($user) {
            return $user->id === Auth::user()->id;
        });
    }

    public function getCountFollowersAttribute()
    {
        return $this->followers->count();
    }

    public function getCountFollowingsAttribute()
    {
        return $this->followings->count();
    }

    /**
     * こだわり検索
     * @param array
     */
    public function scopeSerach($query, array $params)
    {
        $query->where(function ($query) use ($params) {
            if(isset($params['created_at_start']) && !isset($params['created_at_end'])){
                $query->whereDate('created_at', $params['created_at_start']);
            }
            if(isset($params['last_login_at_start']) && !isset($params['last_login_at_end'])){
                $query->whereDate('last_login_at', $params['last_login_at_start']);
            }
            if(isset($params['deleted_at_start']) && !isset($params['deleted_at_end'])){
                $query->whereDate('deleted_at', $params['deleted_at_start']);
            }
            if(isset($params['created_at_start']) && isset($params['created_at_end'])){
                $query->whereBetween('created_at', [$params['created_at_start'], $params['created_at_end']]);
            }
            if(isset($params['last_login_at_start']) && isset($params['last_login_at_end'])){
                $query->whereBetween('last_login_at', [$params['last_login_at_start'], $params['last_login_at_end']]);
            }
            if(isset($params['deleted_at_start']) && isset($params['deleted_at_end'])){
                $query->whereBetween('deleted_at', [$params['deleted_at_start'], $params['deleted_at_end']]);
            }
            if(isset($params['name'])){
                $query->where('name', 'like', "%{$params['name']}%")->orWhereHas(
                    'user_detail',
                    function ($q)use($params) {
                        $q->where('realname', $params['name']);
                    }
                );
                // $query->where('name', 'like', "%{$params['name']}%");
            }
            if(isset($params['email'])){
                $query->where('email', 'like', "%{$params['email']}%");
            }
            if(isset($params['available'])){
            $query->where('available',$params['available']);
            }
        });

        return $query;
    }
}
