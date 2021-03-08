<?php

namespace App\Http\Controllers\Api;

use App\Models\RoomsModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoomsCollection;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new RoomsCollection(RoomsModel::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RoomsModel  $RoomsModel
     * @return \Illuminate\Http\Response
     */
    public function show(RoomsModel $RoomsModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RoomsModel  $RoomsModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RoomsModel $RoomsModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RoomsModel  $RoomsModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(RoomsModel $RoomsModel)
    {
        //
    }
}
