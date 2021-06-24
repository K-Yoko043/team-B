<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * userに所属する役目を取得
     */
    public function roles()
    {
        return $this->belongsToMany('App\Role', 'user_role');
    }

    /**
     * 従業員を取得
     */
    public function goriller()
    {
        return $this->hasOne('App\Goriller');
    }

    /**
     * 投稿内容を取得
     */
    public function contents()
    {
        return $this->hasMany('App\Content');
    }

    /**
     * プロフィールを取得
     */
    public function profile()
    {
        return $this->hasOne('App\Profile');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }
}