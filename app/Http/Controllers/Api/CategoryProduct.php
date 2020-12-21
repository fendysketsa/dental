<?php

namespace App\Http\Controllers\Api;

use App\Models\CategoryModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryCollection;

class CategoryProduct extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new CategoryCollection(CategoryModel::where('jenis', 2)->get());
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
     * @param  \App\Models\Api\CategoryProductModel  $categoryProductModel
     * @return \Illuminate\Http\Response
     */
    public function show(CategoryProductModel $categoryProductModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Api\CategoryProductModel  $categoryProductModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CategoryProductModel $categoryProductModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Api\CategoryProductModel  $categoryProductModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(CategoryProductModel $categoryProductModel)
    {
        //
    }
}
