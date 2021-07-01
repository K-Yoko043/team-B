<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'path',
    ];

    /**
     * ゴリラーを取得
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
