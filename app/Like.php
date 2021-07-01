<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
	protected $fillable = [
		'content_id', 'user_id'
	];

	/**
	 * 投稿を取得
	 */
	public function content()
	{
		return $this->belongsTo('App\Content');
	}
	// /**
    //  * 投稿者を取得
    //  */
    // public function goriller()
    // {
    //     return $this->belongsTo('App\Goriller');
    // }
	/**
	 * ユーザを取得
	 */
	public function user()
	{
		return $this->belongsTo('App\User','user_id');
	}
	// /**
	//  * 既にいいねされているか
	//  */
	// public function like_exist($id, $content_id)
	// {
	// 	$exist = Like::where('user_id', '=', $id)
	// 		->where('content_id', '=', $content_id)
	// 		->get();		

	// 	if (!$exist->isEmpty()) {
	// 		return true;
	// 	} else {
	// 		return false;
	// 	}
	// }
}
