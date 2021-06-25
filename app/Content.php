<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Content extends Model
{
    /**
     * 文字列へキャストする属性
     *
     * @var array
     */
    protected $casts = [
        'content_text' => 'string',
    ];

    /**
     * ユーザを取得
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * 投稿者を取得
     */
    public function goriller()
    {
        return $this->belongsTo('App\Goriller');
    }

    /**
     * いいねを取得
     */
    public function likes()
    {
        return $this->hasMany('App\Like');
    }

    // public function isLiked($user_id)
    // {
    //     return $this->likes()->where('user_id', $user_id)->exists();
    // }
    /**
     * いいねを取得
     */
    public function responds()
    {
        return $this->hasMany('App\Respond');
    }

}
