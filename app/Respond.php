<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Respond extends Model
{
    protected $primaryKey = 'id';

    /**
     * 投稿を取得
     */
    public function content()
    {
        return $this->belongsTo('App\Content');
    }

    /**
     * 投稿者を取得
     */
    public function goriller()
    {
        return $this->belongsTo('App\Goriller');
    }

    /**
     * ユーザを取得
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
