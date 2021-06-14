<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Content extends Model
{
    use SoftDeletes;

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
}
