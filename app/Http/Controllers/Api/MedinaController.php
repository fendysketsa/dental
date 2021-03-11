<?php

namespace App\Http\Controllers\Api;

use App\Models\MedinasModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MedinasCollection;

class MedinaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $abMed = new MedinasModel;
        // $dataAbMedina = empty($request->cabang) ? $abMed->all() : $abMed->where('branch_id', $request->cabang)->get();

        return new MedinasCollection($abMed->all());
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
     * @param  \App\Models\MedinasModel  $MedinasModel
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new MedinasCollection(MedinasModel::where('id', $id)->get());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MedinasModel  $MedinasModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MedinasModel $MedinasModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MedinasModel  $MedinasModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(MedinasModel $MedinasModel)
    {
        //
    }
}
