<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Api\KeranjangBelanjaModel;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\KeranjangBelanjaCollection;
use App\Models\Api\ReservationModel;
use App\Models\Transaction\TransaksiModel;
use App\Api\ReservationDetailModel;

class KeranjangBelanjaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //82 ini manual
        return new KeranjangBelanjaCollection(KeranjangBelanjaModel::where('member_id', $request->member_id)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|numeric',
            'produk_id' => 'required|numeric'
        ]);

        $transaksi = new ReservationModel;
        DB::transaction(function () use ($request, $transaksi) {

            $keranjang = new KeranjangBelanjaModel;
            $keranjang->fill($request->all());
            $keranjang->save();

            if ($request->belanja == 'ok') {
                $transaksi->forceFill([
                    'no_transaksi' => TransaksiModel::getAutoNoTransaksi(),
                    'status' => 1,
                    'agent' => 'Android'
                ]);
                $transaksi->fill($request->all());
                $transaksi->save();

                $baskets = KeranjangBelanjaModel::where('member_id', $request->member_id)->get();
                foreach ($baskets as $fills) {
                    $transaksiDetail = new TransaksiDetailModel();

                    $transaksiDetail->kuantitas = 1;
                    $transaksiDetail->produk_id = $fills->produk_id;
                    $transaksiDetail->member_id = $fills->member_id;
                    $transaksiDetail->transaksi_id = $transaksi->id;
                    $transaksiDetail->save();

                    $fills->delete();
                }
            }
        });

        if ($request->belanja == 'ok') {
            return response()->json([
                'code' => 200,
                'message' => 'Berhasil berbelanja produk',
                'data' => TransaksiDetailModel::where('member_id', $request->member_id)->first()
            ]);
        } else {
            return response()->json([
                'code' => 200,
                'message' => 'Berhasil membeli produk',
                'data' => KeranjangBelanjaModel::where('member_id', $request->member_id)->first()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
