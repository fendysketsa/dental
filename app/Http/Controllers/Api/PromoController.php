<?php

namespace App\Http\Controllers\Api;

use App\Models\PromoModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PromoCollection;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $table_cabang = 'cabang';

    public function index()
    {
        return new PromoCollection(PromoModel::leftJoin(
            $this->table_cabang,
            $this->table_cabang . '.id',
            '=',
            'promo.cabang_id'
        )->select(
            'promo.id',
            'promo.gambar',
            'promo.berlaku_dari',
            'promo.berlaku_sampai',
            'promo.deskripsi',
            $this->table_cabang . '.nama as cabang'
        )->orderBy('id', 'DESC')->get());
    }

    public function show($id)
    {
        return new PromoCollection(PromoModel::where('id', $id)
            ->get());
    }
}