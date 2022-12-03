<?php

namespace App\Http\Controllers;

use App\Models\theloaigame;
use App\Http\Requests\StoretheloaigameRequest;
use App\Http\Requests\UpdatetheloaigameRequest;
use App\Http\Resources\TheLoaiGameResource;
use Illuminate\Support\Facades\DB;

class TheLoaiGameController extends Controller
{
    public function getTheloaiPageSort($sort,$num)
    {
        return TheLoaiGameResource::collection(theloaigame::orderBy($sort)->paginate($num));
    }

    public function getNumTheloai()
    {
        $count = theloaigame::select(DB::raw('count(*) AS num_theloai'))->get();
        return response(['num_theloai' => $count]);
    }

    public function getPopularTheloai()
    {
        // $data = DB::table('theloaigames AS tl', 'games AS g')->select('tl.tentheloai', DB::raw('sum(g.soluotchoi) AS slc'))->where('tl.id', '=', 'g.id_theloai')
        // ->groupBy('g.id_theloai')->get();
        $data = DB::table('theloaigames AS tl')->join('games','tl.id', '=','games.id_theloai')->select('tl.tentheloai', DB::raw('sum(games.soluotchoi) AS slc'))
        ->groupBy('tl.tentheloai')->orderBy('slc','desc')->limit(5)->get();
        return response([
            'data' => $data
        ]); 
    }

    public function getTheloaiByBlug($slug)
    {
        return TheLoaiGameResource::collection(theloaigame::where('slug', $slug)->get());
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TheLoaiGameResource::collection(theloaigame::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoretheloaigameRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoretheloaigameRequest $request)
    {
        $data = $request->validated();
        $theloai = theloaigame::create($data);
        return new TheLoaiGameResource($theloai);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\theloaigame  $theloaigame
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return TheLoaiGameResource::collection(theloaigame::where('id', $id)->get());  
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatetheloaigameRequest  $request
     * @param  \App\Models\theloaigame  $theloaigame
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatetheloaigameRequest $request, theloaigame $theloaigame)
    {
        $data = $request->validated();
        theloaigame::where('id', $request->id)->update($data);
        return response('thanh cong', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\theloaigame  $theloaigame
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        theloaigame::where('id', $id)->delete();
        return response('xoa thanh cong', 204);
    }
}
