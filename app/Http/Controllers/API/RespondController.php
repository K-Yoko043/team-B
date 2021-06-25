<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\RespondForList as RespondForListResource;
use App\Goriller;
use App\Content;
use App\User;
use App\Respond;
class RespondController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $responds = Respond::all();
        return RespondForListResource::collection($responds);
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
            $respond = new Respond;
            $respond->text = $request->respond['text'];
            $respond->id = $request->respond['id'];
            $respond->content_id = $request->respond['content_id'];
            $respond->user_id = Auth::id();
            $respond->save();
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
    public function update(Request $request, Respond $respond)
    {
        DB::transaction(function () use ($request, $respond) {
            $respond->text = $request->respond['text'];
            $respond->save();
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
    public function destroy(Respond $respond)
    {
        DB::transaction(function () use ($respond)
        {
            $respond->delete();
        });
        return response()->json([
            'result' => true,
        ]);
    }
}
