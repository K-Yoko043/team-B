<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Resources\ContentForList as ContentForListResource;
use App\Http\Resources\ContentForShow as ContentForShowResource;
use App\Http\Resources\ContentSelector as ContentSelectorResource;
use App\Goriller;
use App\Content;
use App\User;
use App\Like;

class ContentController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$keyword = $request->keyword;
		$content_text = $request->content_text;	

		$rets = [];
		if (!empty($keyword)) {
			$keyword = str_replace('+', ' ', $keyword);
			$keyword = str_replace('　', ' ', $keyword);
			$keyword = str_replace('%', ' ', $keyword);
			$keyword = preg_replace('/\s(?=\s)/', '', $keyword);
			$keyword = trim($keyword);

			if (!empty($keyword) || $keyword !== '') {
				$keyword = mb_convert_kana($keyword, 'KV');
				$rets = array_unique(explode(' ', $keyword));
			}
		} 

		$content_text = Content::groupby('content_text')->pluck('content_text');

		if (!empty($rets)) {
			$contents = [];
			foreach ($rets as $ret) {
				$contents = Content::where('content_text', 'like', '%'.$ret.'%')
					->get();
			}
		} else {
			$contents = Content::all();
		}

		\Log::info($contents);

		return response()->json([
			'content_text' => $content_text,
			'contents' => ContentForListResource::collection($contents),
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		DB::transaction(function () use ($request) {
			$position = Content::max('position');
			$position++;

			$content = new Content;
			$content->tag = $request->content['tag'];
			$content->content_text = $request->content['content_text'];
			$content->position = $position;

			$content->user_id = Auth::id();
			$content->save();
		});

		return response()->json([
			'result' => true,
		]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show(Content $content)
	{
		return new ContentForShowResource($content);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Content $content)
	{
		$user->Auth::user();
		if ($user->id != $content->user_id) {
			if ($content->isLiked(Auth::id())) {
				$delete_record = $content->getLike($user->id);
				$delete_record->delete();
			} else {
				$like = Like::firstOrCreate(
					array(
						'user_id' => Auth::user()->id,
						'content_id' => $content_id
					)
					);
			}
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Content $content)
	{
		DB::transaction(function () use ($content) {
			$content->delete();
		});

		return response()->json([
			'result' => true,
		]);
	}
	public function addgood(Request $request,$id,$mark)
    {      
        $like= new Like;
        $like->content_id = $id;
        $like->user_id = Auth::id();
        $like->reaction_no = $mark;
        $like->save();
        //session()->flash('success', 'You Liked the Reply.');
        //return redirect()->back();
        return response()->json([
            'result' => true,
        ]);
    }
    public function deletegood(Request $request,$id,$mark)
    {
       $like = Like::where('content_id', $id)->where('user_id', Auth::id())->where('reaction_no',$mark)->first();
       $like->delete();
   
       //session()->flash('success', 'You Unliked the Reply.');
   
       //return redirect()->back();
        return response()->json([
            'result' => true,
        ]);
    }

}
