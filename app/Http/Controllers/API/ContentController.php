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
use App\Bookmark;
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

		// $tag = Content::groupby('tag')->pluck('tag');
		$contents = Content::where('user_name', 'like', '%'.$keyword.'%')->get();
		// $contents = Content::all();
		\Log::info($contents);

		return response()->json([
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
			$content->user_name = Auth::user()->name;
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

	public function addbook($id){
        $bookmark = new Bookmark;
        $bookmark->content_id = $id;
        $bookmark->user_id = Auth::user()->id;
        $bookmark->save();
        return response()->json([
            'result' => true,
        ]);
    }
    public function deletebook($id){
        $bookmark=Bookmark::where('content_id',$id)->where('user_id',Auth::user()->id);
        $bookmark->delete();
        return response()->json([
            'result' => true,
        ]);
    }
	public function showTag(Request $request)
	{
		$tag = $request->tag;

		if ($tag != "") {
			$contents = Content::where('tag', 'like', '%'.$tag.'%')->get();
		} else {
			$contents = Content::all();
		}

		return response()->json([
			'contents' => ContentForListResource::collection($contents),
		]);
	}

}
