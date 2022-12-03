<?php

namespace App\Http\Controllers;

use App\Models\reply;
use App\Http\Requests\StorereplyRequest;
use App\Http\Requests\UpdatereplyRequest;

class ReplyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorereplyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorereplyRequest $request)
    {
        $data = $request->validated();
        reply::create($data);
        return response('reply success', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function show(reply $reply)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatereplyRequest  $request
     * @param  \App\Models\reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatereplyRequest $request, reply $reply)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function destroy(reply $reply)
    {
        $reply->delete();
        return response('success', 200);
    }
}
