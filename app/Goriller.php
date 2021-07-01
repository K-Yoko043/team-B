<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Goriller extends Model
{
    use SoftDeletes;

    /**
     * フルネーム取得
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->last_name} {$this->first_name}";
    }

    /**
     * ユーザを取得
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * 投稿内容を取得
     */
    public function contents()
    {
        return $this->hasMany('App\Content');
    }
    /**
     * 返信情報を取得
     */
    public function responds()
    {
        return $this->hasMany('App\Responds');
    }
    /**
     * プロフィール画像を取得
     */
    public function profile()
    {
        return $this->hasOne('App\Profile');
    }

    // /**
    //  * いいねを取得
    //  */
    // public function likes()
    // {
    //     return $this->hasMany('App\Like','user_id');
    // }
}
