<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    public function tweet()
    {
        return $this->belongsTo('App\Tweet');
    }
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
}
