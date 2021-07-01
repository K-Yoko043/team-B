<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = ['content_id','user_id','respond_id'];
    public function content()
    {
        return $this->belongsTo('App\Content');
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
