<?php

namespace App\Http\Controllers\Api;

use App\Models\SliderModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SliderCollection;

class SlideController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new SliderCollection(SliderModel::all());
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
     * @param  \App\Models\SlideModel  $slideModel
     * @return \Illuminate\Http\Response
     */
    public function show(SlideModel $slideModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SlideModel  $slideModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SlideModel $slideModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SlideModel  $slideModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(SlideModel $slideModel)
    {
        //
    }
}
