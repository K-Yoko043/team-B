<?php

use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\Bookmark as BookmarkResource;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy machine your API!
|
*/

Route::middleware('auth:api')->get('/user/info', function (Request $request) {
    return new UserResource($request->user());
});
Route::middleware(['auth:api'])->group(function () {
    
    // ゴリラー
    Route::get('goriller', 'API\GorillerController@index');
    Route::post('goriller', 'API\GorillerController@store');
    Route::get('goriller/{goriller}', 'API\GorillerController@show');
    Route::put('goriller/{goriller}', 'API\GorillerController@update');
    Route::delete('goriller/{goriller}', 'API\GorillerController@destroy');
    Route::get('goriller/selector', 'API\GorillerController@selector');
    Route::get('goriller/full_name', 'API\GorillerController@fullname');

    // 投稿
    Route::get('content', 'API\ContentController@index');
    Route::post('content', 'API\ContentController@store');
    Route::get('content/like/{like}', 'API\ContentController@like');
    Route::get('content/unlike/{like}', 'API\ContentController@unlike');
    Route::get('content/{content}', 'API\ContentController@show');
    Route::put('content/{content}', 'API\ContentController@update');
    Route::delete('content/{content}', 'API\ContentController@destroy');
    //返信
    Route::get('respond', 'API\RespondController@index');
    Route::post('respond', 'API\RespondController@store');
    Route::get('respond/{respond}', 'API\RespondController@show');
    Route::put('respond/{respond}', 'API\RespondController@update');
    Route::delete('respond/{respond}', 'API\RespondController@destroy');
    Route::get('respond/selector', 'API\RespondController@selector');
    //いいね機能の追加削除
    Route::get('content/addgood/{content}/{mark}', 'API\ContentController@addgood');
    Route::delete('content/deletegood/{content}/{mark}', 'API\ContentController@deletegood');
    Route::get('content/add/{content}','API\ContentController@addbook');
    Route::delete('content/delete/{content}','API\ContentController@deletebook');

    // 投稿の絞り込み
    Route::get('tag', 'API\ContentController@showTag');
    
    
    // コメント（返信）
    Route::get('content/{content}/get_comments', 'API\CommentController@index');
    
    // プロフィール
    Route::get('profile', 'API\ProfileController@index');
    Route::post('profile/{profile}', 'API\ProfileController@store');
    Route::get('profile/{profile}', 'API\ProfileController@show');
    Route::post('profile', 'API\ProfileController@store');
    //現在ログインしているユーザーid及びユーザー名の取得
    Route::get('notice/userid', 'API\NoticeController@userid');
    Route::get('notice/username', 'API\NoticeController@username');
    //通知の自動追加・自動削除
    Route::get('notice', 'API\NoticeController@index');
    Route::post('notice/{contentid}/{contentuserid}', 'API\NoticeController@store');
    Route::delete('notice/{id}', 'API\NoticeController@destroy');
    Route::delete('notice/{userid}/{contentid}', 'API\NoticeController@destroycomment');
    //いいねのルーティング設定設定
    Route::get('like', 'API\LikeController@index');

    //ブックマーク
    Route::get('Bookmark','API\BookmarkController@index');
    Route::get('Bookmark/getuser','API\BookmarkController@getuser');
});
