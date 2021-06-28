<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\NoticeForList as NoticeForListResource;
use App\Goriller;
use App\Content;
use App\User;
use App\Respond;
use App\Notice;
class NoticeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notices = Notice::all();
        return NoticeForListResource::collection($notices);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$contentid,$contentuserid)
    {
        $notice= new Notice;
        $notice->content_id = $contentid;
        $notice->content_userid = $contentuserid;
        $notice->user_id = Auth::id();
        $notice->save();
        //session()->flash('success', 'You Liked the Reply.');
        //return redirect()->back();
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
    public function show($id)
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notice = Notice::where('id', $id)->first();
        $notice->delete();
   
       //session()->flash('success', 'You Unliked the Reply.');
   
       //return redirect()->back();
        return response()->json([
            'result' => true,
        ]);
    }
    public function destroycomment($userid,$contentid)
    {
        $notice = Notice::where('user_id', $userid)->where('content_id', $contentid)->first();
        $notice->delete();
   
       //session()->flash('success', 'You Unliked the Reply.');
   
       //return redirect()->back();
        return response()->json([
            'result' => true,
        ]);
    }
    public function userid(){
        return Auth::id();
    }
    public function username(){
        return Auth::user()->name;
    }
}
