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
	public function __construct()
	{
		$this->middleware(['auth', 'verified'])->only(['like', 'unlike']);
	}

	public function getLink($comment)
	{
		$pattern = '/https?:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+/';
		$comment = preg_replace_callback($pattern, 'replace', htmlspecialchars($comment));
		return $comment;
	}

	public function replace($matches)
	{
		return '<a href="'.$matches[0].'">'.$matches[0].'</a>';
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$keyword = $request->keyword;
		$content_text = $request->content_text;	

		// $content = $this->getLink($content_text);

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
				// $contents = Content::where('content_text', 'like', '%'.$ret.'%')
				// 	->get();
				// $contents = Content::whereRaw('match (`content_text`) against (+'.$ret.' in boolean mode)');
				$contents = Content::whereRaw("match(content_text) against(?)", $ret)
				->get();
				\Log::info('***********************loop***********************');
			}
		} else {
			$contents = Content::all();
		}

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
	public function update(Request $request, Content $content)
	{
		$content = DB::transaction(function () use ($request, $content) {
			// $content->title = $request->content['title'];
			$content->tag = $request->content['tag'];
			$content->content_text = $request->content['content_text'];
			$content->save();
		});

		return response()->json([
			'result' => true,
		]);
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

	public function showTag(Request $request)
	{
		$tag = $request->tag;
		\Log::info('tagの内容');
		\Log::info($tag);		

		// $tag = Content::groupby('tag')->pluck('tag');
		$contents = Content::where('tag', 'like', '%'.$tag.'%')->get();
		// $contents = Content::all();
		\Log::info($contents);

		return response()->json([
			'contents' => ContentForListResource::collection($contents),
		]);
	}

}
