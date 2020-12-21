<?php

namespace App\Http\Controllers\Web\Information;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MembersInformation extends Controller
{

    protected $table = 'member';
    protected $table_transaksi = 'transaksi';
    protected $table_produk = 'produk';
    protected $table_layanan = 'layanan';
    protected $table_transaksi_detail = 'transaksi_detail';

    public function index()
    {
        return view('information.member.index', [
            'js' => [
                's-home/information/member/js/member.js',
            ],
            'attribute' => [
                'm_info' => 'true',
                'menu_inf_member' => 'active menu-open',
                'title_bc' => 'Informasi - Pelanggan',
                'desc_bc' => 'Digunakan untuk media menampilkan informasi , history transaksi dan layanan',
            ]
        ]);
    }

    public function _data()
    {
        return view('information.member.content.data.table');
    }

    public function _data_history(Request $request)
    {
        return view('information.member.content.data.table_' . $request->table);
    }

    public function _json()
    {
        if (request()->ajax()) {
            $dJson = DB::table($this->table)
                ->select(
                    DB::raw('CONCAT(tempat_lahir, ", ",DATE_FORMAT(tgl_lahir, "%e %b %Y")) as kelahiran'),
                    $this->table . '.id',
                    DB::raw('(SELECT MAX(id) FROM transaksi WHERE member_id = member.id) as id_trans'),
                    $this->table . '.no_member',
                    $this->table . '.foto',
                    $this->table . '.nama',
                    $this->table . '.jenis_kelamin',
                    $this->table . '.email',
                    $this->table . '.telepon',
                    DB::raw('IF(' . $this->table . '.saldo, ' . $this->table . '.saldo, 0) as saldo'),
                    DB::raw('CONCAT((SELECT COUNT(id) FROM transaksi WHERE member_id = member.id AND status_pembayaran = "terbayar"), " kali") as count_trans')
                );
            $dJson->orderBy($this->table . '.id', 'DESC')
                ->get();

            return datatables()->of($dJson)
                ->addColumn('action', 'information.member.content.data.action_button')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function _json_history(Request $request)
    {
        if (request()->ajax()) {
            if ($request->table == 'layanan') {
                return datatables()->of(DB::table($this->table_transaksi)
                    ->leftJoin($this->table_transaksi_detail, $this->table_transaksi . '.id', '=', $this->table_transaksi_detail . '.transaksi_id')
                    ->leftJoin($this->table_layanan, $this->table_layanan . '.id', '=', $this->table_transaksi_detail . '.layanan_id')
                    ->select(
                        $this->table_transaksi . '.status',
                        $this->table_transaksi . '.created_at as waktu',
                        $this->table_layanan . '.nama',
                        $this->table_transaksi . '.id',
                        $this->table_transaksi_detail . '.harga as harga'
                    )
                    ->where($this->table_transaksi . '.member_id', $request->member)
                    ->where($this->table_transaksi . '.status_pembayaran', 'terbayar')
                    ->whereNull($this->table_transaksi_detail . '.produk_id')
                    ->orderBy(DB::raw('DATE(transaksi.created_at)'), 'DESC')
                    ->get())
                    ->addColumn('status', 'information.member.content.data.status_info')
                    ->rawColumns(['status'])
                    ->addIndexColumn()
                    ->make(true);
            }

            if ($request->table == 'produk') {
                return datatables()->of(DB::table($this->table_transaksi_detail)
                    ->leftJoin($this->table_transaksi, $this->table_transaksi . '.id', '=', $this->table_transaksi_detail . '.transaksi_id')
                    ->leftJoin($this->table_produk, $this->table_produk . '.id', '=', $this->table_transaksi_detail . '.produk_id')
                    ->select(
                        $this->table_transaksi . '.status',
                        $this->table_transaksi . '.created_at as waktu',
                        $this->table_produk . '.nama',
                        $this->table_transaksi . '.id',
                        $this->table_transaksi_detail . '.harga as harga',
                        $this->table_transaksi_detail . '.kuantitas as jumlah'
                    )
                    ->where($this->table_transaksi . '.member_id', $request->member)
                    ->where($this->table_transaksi . '.status_pembayaran', 'terbayar')
                    ->whereNotNull($this->table_transaksi_detail . '.produk_id')
                    ->orderBy(DB::raw('DATE(transaksi.created_at)'), 'DESC')
                    ->get())
                    ->addColumn('status', 'information.member.content.data.status_info')
                    ->rawColumns(['status'])
                    ->addIndexColumn()
                    ->make(true);
            }
        }
    }

    public function show($id)
    {
        return view('information.member.content.detail.modal.detail', [
            'data' => $id
        ]);
    }
}