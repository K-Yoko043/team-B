<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

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
     * テキスト内のリンク部分を取得
     */
    // public function getBodyWithLinkAttribute(): string
    // {
    //     $pattern = '/((?:https?|ftp):\/\/[-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+)/';
    //     $replace = '<a href="$1">$1</a>';
    //     return preg_replace($pattern, $replace, $this->body);
    // }
    
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

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    // ブックマークを取得
    public function Bookmark(){
        return $this->hasMany('App\Bookmark');
    }

    // public function isLiked($user_id)
    // {
    //     return $this->likes()->where('user_id', $user_id)->exists();
    // }
    /**
     * いいねを取得
     */
    public function likes()
    {
        return $this->hasMany('App\Like');
    }
    public function responds()
    {
        return $this->hasMany('App\Respond');
    }
}
