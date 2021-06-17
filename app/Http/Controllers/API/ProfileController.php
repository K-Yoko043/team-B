<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileForList as ProfileForListResource;
use App\Http\Resources\ProfileForShow as ProfileForShowResource;

use App\Profile;
use App\Goriller;


class ProfileController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $profiles = Profile::all();
    return ProfileForListResource::collection($profiles);
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
  $this->validate($request, [
    'file' => 'required | image',
    ], [
    'title.required' => 'タイトルを入力してください。',
    'file.required' => '画像が選択されていません。',
    'file.image' => '画像ファイルではありません。',
  ]);

  if (request()->file) {
    $file_name = time() . '.' . request()->file->getClientOriginalName();
    request()->file->storeAs('public', $file_name);
  }
  $image = new Profile();
  $image->path = 'storage/' . $file_name;
  $image->save();

  // DB::transaction(function () use ($request) {

  //   $goriller = new Goriller;
  //   $goriller->first_name = $request->goriller['first_name'];
  //   $goriller->last_name = $request->goriller['last_name'];

  //   $image->goriller_id = $goriller->id;
  //   $image->save();
  // });
  
  return response()->json([
    'success' => '保存しました',
    'result' => true,
  ]);
}

/**
 * Display the specified resource.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function show(Profile $profile)
{
  return new ProfileForShowResource($profile);
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
//
}
}
