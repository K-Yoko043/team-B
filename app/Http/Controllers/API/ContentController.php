<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ContentForList as ContentForListResource;
use App\Http\Resources\ContentForShow as ContentForShowResource;
use App\Http\Resources\ContentSelector as ContentSelectorResource;
use App\Goriller;
use App\Content;
use App\User;

use Illuminate\Support\Facades\Auth;

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
		// $user_name = $request->user_name;
		$content_text = $request->content_text;	

		$content_text = Content::groupby('content_text')->pluck('content_text');
		// $user_name = Content::groupby('user_name')->pluck('user_name');
		
		$contents = Content::where('content_text', 'like', '%'.$keyword.'%')
			// Contentは投稿者のIDしか持ってない
			// ->orWhere('user_name', 'like', '%'.$user_name.'%')
			->get();

		return response()->json([
			'content_text' => $content_text,
			'contents' => ContentForListResource::collection($contents),
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
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
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		//
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

	/**
	 * いいね
	 */
	public function __construct()
	{
		$this->middleware(['auth', 'verified'])->only(['like', 'unlike']);
	}

	public function like($id)
	{
		Like::create([
			'content_id' => $id,
			'goriller_id' => Auth::id()
		]);

		session()->flash('success', 'You Liked the content.');
		return redirect()->back();
	}

	public function unlike($id)
	{
		$like = Like::where('content_id', $id)
				->where('goriller_id', Auth::id())->first();
		$like->delete();

		session()->flash('success', 'You Unliked the content.');
		return redirect()->back();
	}
}
