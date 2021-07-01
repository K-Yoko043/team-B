<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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

    public function responds()
    {
        return $this->hasMany('App\Respond');
    }

}
