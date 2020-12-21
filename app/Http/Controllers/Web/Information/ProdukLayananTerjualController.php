<?php

namespace App\Http\Controllers\Web\Information;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

class ProdukLayananTerjualController extends Controller
{
    protected $table_produk = 'produk';
    protected $table_paket = 'paket';
    protected $table_layanan = 'layanan';
    protected $table_kategori_layanan = 'kategori';
    protected $table_detail_transaksi = 'transaksi_detail';
    protected $table_transaksi = 'transaksi';

    private $total_item = 0;
    private $total_pendapatan = 0;

    public function index()
    {
        return view('information.salesPS.index', [
            'js' => [
                's-home/information/salesPS/js/salesPS.js'
            ],
            'attribute' => [
                'm_info' => 'true',
                'menu_salesProdServ' => 'active menu-open',
                'title_bc' => 'Informasi - Produk & Layanan Terjual',
                'desc_bc' => 'Digunakan untuk media menampilkan informasi produk dan layanan terjual',
            ]
        ]);
    }

    public function _data()
    {
        return view('information.salesPS.content.data.table_' . Input::get('table'));
    }

    public function _json(Request $request)
    {
        if (request()->ajax()) {
            if (Input::get('table') == 'produk') {
                $table_json = DB::table($this->table_produk)
                    ->leftJoin(
                        $this->table_detail_transaksi,
                        $this->table_detail_transaksi . '.produk_id',
                        '=',
                        $this->table_produk . '.id'
                    )
                    ->leftJoin(
                        $this->table_transaksi,
                        $this->table_detail_transaksi . '.transaksi_id',
                        '=',
                        $this->table_transaksi . '.id'
                    )
                    ->select(
                        $this->table_produk . '.id',
                        $this->table_produk . '.nama',
                        $this->table_produk . '.stok',
                        $this->table_produk . '.harga_jual_member as harga',
                        DB::raw("COUNT(" . $this->table_detail_transaksi . ".produk_id) as terjual"),
                        DB::raw("SUM(" . $this->table_produk . ".harga_jual_member) as pendapatan")
                    )
                    ->whereNotNull($this->table_detail_transaksi . '.produk_id')
                    ->where($this->table_transaksi . '.status_pembayaran', 'terbayar')
                    ->where($this->table_transaksi . '.status', 3);

                if (!empty(session('cabang_session'))) {
                    $table_json->orWhere($this->table_transaksi . '.lokasi_id', session('cabang_session'));
                }

                if (!empty(session('cabang_id'))) {
                    $table_json->where($this->table_transaksi . '.lokasi_id', base64_decode(session('cabang_id')));
                }

                if ($request->has('start') && $request->has('ends')) {
                    $table_json->whereBetween(DB::RAW('DATE(' . $this->table_transaksi . '.created_at)'), [$request->starts, $request->ends]);
                }

                $table_json->groupBy($this->table_produk . '.id');
                $dataJson = $table_json->get();

                foreach ($dataJson as $row) {
                    $this->total_item += $row->terjual;
                    $this->total_pendapatan += $row->pendapatan;
                }
            } else if (Input::get('table') == 'paket') {
                $table_json = DB::table($this->table_paket)
                    ->leftJoin(
                        $this->table_detail_transaksi,
                        $this->table_detail_transaksi . '.paket_id',
                        '=',
                        $this->table_paket . '.id'
                    )
                    ->leftJoin(
                        $this->table_transaksi,
                        $this->table_detail_transaksi . '.transaksi_id',
                        '=',
                        $this->table_transaksi . '.id'
                    )
                    ->select(
                        $this->table_paket . '.id',
                        $this->table_paket . '.nama',
                        $this->table_paket . '.harga',
                        DB::raw("COUNT(DISTINCT(" . $this->table_transaksi . ".id)) as terjual"),
                        DB::raw("SUM(" . $this->table_detail_transaksi . ".harga) as pendapatan")
                    )
                    ->whereNotNull($this->table_detail_transaksi . '.paket_id')
                    ->where($this->table_transaksi . '.status_pembayaran', 'terbayar')
                    ->where($this->table_transaksi . '.status', 3);

                if (!empty(session('cabang_session'))) {
                    $table_json->orWhere($this->table_transaksi . '.lokasi_id', session('cabang_session'));
                }

                if (!empty(session('cabang_id'))) {
                    $table_json->where($this->table_transaksi . '.lokasi_id', base64_decode(session('cabang_id')));
                }

                if ($request->has('start') && $request->has('ends')) {
                    $table_json->whereBetween(DB::RAW('DATE(' . $this->table_transaksi . '.created_at)'), [$request->starts, $request->ends]);
                }

                $table_json->groupBy($this->table_paket . '.id');
                $dataJson = $table_json->get();

                foreach ($dataJson as $row) {
                    $this->total_item += $row->terjual;
                    $this->total_pendapatan += $row->pendapatan;
                }
            } else {
                $table_json = DB::table($this->table_layanan)
                    ->leftJoin(
                        $this->table_detail_transaksi,
                        $this->table_detail_transaksi . '.layanan_id',
                        '=',
                        $this->table_layanan . '.id'
                    )
                    ->leftJoin(
                        $this->table_kategori_layanan,
                        $this->table_kategori_layanan . '.id',
                        '=',
                        $this->table_layanan . '.kategori_id'
                    )
                    ->leftJoin(
                        $this->table_transaksi,
                        $this->table_detail_transaksi . '.transaksi_id',
                        '=',
                        $this->table_transaksi . '.id'
                    )
                    ->select(
                        $this->table_layanan . '.id',
                        $this->table_kategori_layanan . '.nama as kategori',
                        $this->table_layanan . '.nama',
                        $this->table_layanan . '.harga',
                        DB::raw("COUNT(" . $this->table_detail_transaksi . ".layanan_id) as terjual"),
                        DB::raw("SUM(" . $this->table_layanan . ".harga) as pendapatan")
                    )
                    ->whereNull($this->table_detail_transaksi . '.paket_id')
                    ->where($this->table_transaksi . '.status_pembayaran', 'terbayar')
                    ->where($this->table_transaksi . '.status', 3);

                if (!empty(session('cabang_session'))) {
                    $table_json->where($this->table_transaksi . '.lokasi_id', session('cabang_session'));
                }

                if (!empty(session('cabang_id'))) {
                    $table_json->where($this->table_transaksi . '.lokasi_id', base64_decode(session('cabang_id')));
                }

                if ($request->has('start') && $request->has('ends')) {
                    $table_json->whereBetween(DB::RAW('DATE(' . $this->table_transaksi . '.created_at)'), [$request->starts, $request->ends]);
                }

                $table_json->groupBy($this->table_layanan . '.id');
                $dataJson = $table_json->get();

                foreach ($dataJson as $row) {
                    $this->total_item += $row->terjual;
                    $this->total_pendapatan += $row->pendapatan;
                }
            }

            return datatables()->of($table_json)
                ->addColumn('action', 'information.salesPS.content.data.action_button')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->with('total_item', $this->total_item)
                ->with('total_pendapatan', $this->total_pendapatan)
                ->make(true);
        }
    }
}
