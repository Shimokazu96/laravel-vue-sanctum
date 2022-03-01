<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

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
    ];

    protected $visible = [
        'id',
        'name',
        'email',
        'email_verified_at',
        'user_detail',
        'followed_by_user',
    ];

    protected $appends = [
        'followed_by_user',
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * リレーションシップ - user_detailsテーブル
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user_detail()
    {
        return $this->hasOne('App\Models\UserDetail');
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
        return $this->followers()->count() ? true : false;
    }
}
