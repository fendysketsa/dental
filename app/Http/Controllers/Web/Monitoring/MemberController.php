<?php

namespace App\Http\Controllers\Web\Monitoring;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MemberModel;
use App\Models\KunjunganModel as VisitModel;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    public function index()
    {
        return view('monitoring.member.index', [
            'js' => [
                's-home/dist/js/charts/Chart.min.js',
                's-home/monitoring/member/js/member.js'
            ],
            'attribute' => [
                'm_mntrg' => 'true',
                'menu_mntrg_member' => 'active menu-open',
                'title_bc' => 'Monitoring - Member',
                'desc_bc' => 'Digunakan untuk media menampilkan member',
            ],
        ]);
    }

    public function show($id, Request $request)
    {
        if ($request->det == 'layanan') {

            $data = DB::table('transaksi_detail')
                ->leftJoin(
                    'transaksi',
                    'transaksi.id',
                    '=',
                    'transaksi_detail.transaksi_id'
                )
                ->leftJoin(
                    'layanan',
                    'layanan.id',
                    '=',
                    'transaksi_detail.layanan_id'
                )
                ->leftJoin(
                    'paket',
                    'paket.id',
                    '=',
                    'transaksi_detail.paket_id'
                )
                ->leftJoin(
                    'member',
                    'member.id',
                    '=',
                    'transaksi.member_id'
                )
                ->where('transaksi.member_id', $id)
                ->whereNull('transaksi_detail.produk_id')
                ->select(
                    DB::raw('IF((SELECT MIN(td.id)
                    FROM transaksi_detail td
                    LEFT JOIN transaksi t ON t.id = td.transaksi_id
                    WHERE td.transaksi_id = transaksi_detail.id
                    AND t.member_id = ' . $id . ' limit 1) = transaksi_detail.id, transaksi.no_transaksi ,"") as an_transaksi'),
                    'transaksi.created_at as tanggal',
                    'transaksi.no_transaksi',
                    DB::raw('CONCAT("'
                        . asset("storage/master-data/service/") . '", "/", layanan.gambar) as gambar'),
                    'layanan.nama',
                    'transaksi_detail.harga as harga'
                )
                ->orderBy('transaksi.id', 'DESC')
                ->get();

            if (request()->ajax()) {
                return datatables()->of($data)
                    ->addIndexColumn()
                    ->make(true);
            }
        }

        if ($request->det == 'produk') {

            $data = DB::table('transaksi_detail')
                ->leftJoin(
                    'transaksi',
                    'transaksi.id',
                    '=',
                    'transaksi_detail.transaksi_id'
                )
                ->leftJoin(
                    'produk',
                    'produk.id',
                    '=',
                    'transaksi_detail.produk_id'
                )
                ->leftJoin(
                    'member',
                    'member.id',
                    '=',
                    'transaksi.member_id'
                )
                ->where('transaksi.member_id', $id)
                ->whereNull('transaksi_detail.layanan_id')
                ->select(
                    DB::raw('IF((SELECT MIN(td.id)
                    FROM transaksi_detail td
                    LEFT JOIN transaksi t ON t.id = td.transaksi_id
                    WHERE td.transaksi_id = transaksi_detail.id
                    AND t.member_id = ' . $id . ' limit 1) = transaksi_detail.id, transaksi.no_transaksi ,"") as an_transaksi'),
                    'transaksi.created_at as tanggal',
                    'transaksi.no_transaksi',
                    DB::raw('CONCAT("'
                        . asset("storage/master-data/product/") . '", "/", produk.gambar) as gambar'),
                    'produk.nama as nama',
                    'transaksi_detail.harga as harga'
                )
                ->orderBy('transaksi.id', 'DESC')
                ->get();

            if (request()->ajax()) {
                return datatables()->of($data)
                    ->addIndexColumn()
                    ->make(true);
            }
        }
    }

    public function _data()
    {
        $getData = empty(Input::get('data')) ? 'index' : (Input::get('data') == 'table' ? 'table' : 'chart');

        $dataMembers = VisitModel::leftJoin('member', 'member.id', '=', 'transaksi.member_id')
            ->select(
                DB::raw('COUNT(transaksi.id) AS member_rank'),
                'member.nama'
            )
            ->groupBy('member_id')
            ->take(15)
            ->get();

        $data_ch = '';
        $data_nm = '';

        foreach ($dataMembers as $num => $mbr) {
            $koma = count($dataMembers) - 1 == $num ? '' : ',';
            $data_ch .= $mbr->member_rank . $koma;
            $data_nm .=  '"' . $mbr->nama . '"' . $koma;
        }

        return view('monitoring.member.content.data.' . $getData, [
            'data' => $data_ch,
            'dataNm' => $data_nm
        ]);
    }

    public function _json()
    {
        if (request()->ajax()) {
            return datatables()
                ->of(MemberModel::select(
                    'id',
                    'no_member',
                    'foto',
                    'nama',
                    'jenis_kelamin as gender',
                    'email',
                    'telepon',
                    DB::raw('IF(saldo, saldo, 0) as saldo'),
                    DB::raw("(SELECT COUNT(*) FROM transaksi WHERE member_id = member.id) as in_member_use")
                )
                    ->orderBy('id', 'DESC')
                    ->get())
                ->addColumn('action', 'monitoring.member.content.data.action_button')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
    }
}