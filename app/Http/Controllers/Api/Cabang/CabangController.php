<?php

namespace App\Http\Controllers\Api\Cabang;

use App\Models\CabangModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CabangCollection;

class CabangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new CabangCollection(CabangModel::all());
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
     * @param  \App\LokasiModel  $lokasiModel
     * @return \Illuminate\Http\Response
     */
    public function show(LokasiModel $lokasiModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\LokasiModel  $lokasiModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LokasiModel $lokasiModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LokasiModel  $lokasiModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(LokasiModel $lokasiModel)
    {
        //
    }
}
