<?php

use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;

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
    // コメント（返信）
    Route::get('content/{content}/get_comments', 'API\CommentController@index');
    
    // プロフィール
    Route::get('profile', 'API\ProfileController@index');
    Route::get('profile/{profile}', 'API\ProfileController@show');
    Route::post('profile', 'API\ProfileController@store');

});
