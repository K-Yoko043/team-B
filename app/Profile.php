<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'title',
        'path',
    ];

    /**
     * ゴリラーを取得
     */
    public function goriller()
    {
        return $this->belongsTo('App\Goriller');
    }
}
