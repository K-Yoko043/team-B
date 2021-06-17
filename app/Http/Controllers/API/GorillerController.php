<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\GorillerForList as GorillerForListResource;
use App\Http\Resources\GorillerForShow as GorillerForShowResource;
use App\Http\Resources\GorillerSelector as GorillerSelectorResource;
use App\Http\Resources\GorillerForSelector as GorillerForSelectorResource;
use App\Goriller;
use App\User;

class GorillerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gorillers = Goriller::all();
        return GorillerForListResource::collection($gorillers);
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
        $count = User::where([
            ['name', $request->goriller['user_name']]
        ])->count();

        if ($count) {
            return response()->json([
                'result' => false,
                'erroeMessage' => 'このユーザ名は既に登録されています。'
            ]);
        }

        DB::transaction(function () use ($request) {
            $position = Goriller::max('position');
            $position++;

            $goriller = new Goriller;
            $goriller->first_name = $request->goriller['first_name'];
            $goriller->last_name = $request->goriller['last_name'];
            $goriller->position = $position;

            $user = new User;
            $user->name = $request->goriller['user_name'];
            $user->password = bcrypt($request->goriller['password']);
            $user->is_admin = $request->goriller['is_admin'];
            $user->save();

            $goriller->user_id = $user->id;
            $goriller->save();
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
    public function show(Goriller $goriller)
    {
        return new GorillerForShowResource($goriller);
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
    public function update(Request $request, Goriller $goriller)
    {
        $count = User::where([
            ['id', '<>', $goriller->user_id],
            ['name', $request->goriller['user_name']]
        ])->count();

        if ($count) {
            return response()->json([
                'result' => false,
                'errorMessage' => 'このユーザ名は既に登録されています。'
            ]);
        }

        DB::transaction(function () use ($request, $goriller) {
            $user = $goriller->user;
            if (!isset($user)) $user = new User;
            $user->name = $request->goriller['user_name'];
            if (array_key_exists('password', $request->goriller) && $request->goriller['password']) {
                $user->password = bcrypt($request->goriller['password']);
            }
            $user->is_admin = $request->goriller['is_admin'];
            $user->save();
            $goriller->user_id = $user->id;

            $goriller->first_name = $request->goriller['first_name'];
            $goriller->last_name = $request->goriller['last_name'];
            $goriller->save();
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
    public function destroy(Goriller $goriller)
    {
        DB::transaction(function () use ($goriller) {
            $goriller->delete();
            $goriller->user->delete();

            DB::table('goriller')
            ->where('position', '>', $goriller->position)
            ->decrement('position');
        });

        return response()->json([
            'result' => true,
        ]);
    }

    public function fullname()
    {
        $gorillers = Goriller::all();
        return GorillerForSelectorResource::collection($gorillers);
    }
}
