<?php

namespace App\Http\Controllers\Api;

use App\Models\CategoryModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryCollection;

class CategoryService extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new CategoryCollection(CategoryModel::where('jenis', 1)->get());
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
     * @param  \App\CategoryServicesModel  $categoryServicesModel
     * @return \Illuminate\Http\Response
     */
    public function show(CategoryServicesModel $categoryServicesModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CategoryServicesModel  $categoryServicesModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CategoryServicesModel $categoryServicesModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CategoryServicesModel  $categoryServicesModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(CategoryServicesModel $categoryServicesModel)
    {
        //
    }
}
