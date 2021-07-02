<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Bookmark extends Model
{
    protected $bkmtable = 'bookmarks';
    protected $primary = array('id');
    //protected $forign = array('tweet_id');

    public function Bookmark(){
        // $data = DB::table($this->table)->get();
        // return $data;
        
    }

    // contentに対して1:多
    public function Content(){
        return $this->belongsTo('App\Content');
    }

    // userに対して1:多
    public function user(){
        return $this->belongsTo('App\User');
    }
}
