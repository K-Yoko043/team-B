<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'text', 'user_id', 'content_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function content()
    {
        return $this->belongsTo('App\Content');
    }
}
