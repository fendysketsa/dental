<?php

namespace App\Http\Controllers\Web\Information;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MembersHistory extends Controller
{

    protected $table = 'member';
    protected $table_transaksi = 'transaksi';
    protected $table_produk = 'produk';
    protected $table_layanan = 'layanan';
    protected $table_transaksi_detail = 'transaksi_detail';

    public function index()
    {
        return view('information.his_member.index', [
            'js' => [
                's-home/information/his_member/js/his_member.js',
            ],
            'attribute' => [
                'm_info' => 'true',
                'menu_his_member' => 'active menu-open',
                'title_bc' => 'Informasi - History Rekam Medik',
                'desc_bc' => 'Digunakan untuk media menampilkan informasi history rekam medik member',
            ]
        ]);
    }

    public function _data()
    {
        return view('information.his_member.content.data.table');
    }

    public function _data_history(Request $request)
    {
        return view('information.his_member.content.data.table_' . $request->table);
    }

    public function _data_detail_history(Request $request)
    {
        $load = $request->get('load');
        $id = $request->get('id');

        if (!empty($load)) {
            if ($load == 'rekam') {
                $data['rekam'] = DB::table('transaksi_rekam')
                    ->leftJoin('rekam_medik', 'rekam_medik.id', '=', 'transaksi_rekam.position')
                    ->select('rekam_medik.nama', 'transaksi_rekam.name', 'transaksi_rekam.more_keterangan')->where('transaksi_id', $id)->get();
            }

            if ($load == 'catatan') {
                $data['catatan'] = DB::table('transaksi_rekam_gigi')
                    ->select('gigi', 'ringkasan', 'foto')->where('transaksi_id', $id)->get();
            }

            echo json_encode($data, true);
        } else {
            return view('information.his_member.content.data.load_detail');
        }
    }

    public function _json()
    {
        if (request()->ajax()) {
            $dJson = DB::table($this->table)
                ->select(
                    DB::raw('CONCAT(tempat_lahir, ", ",DATE_FORMAT(tgl_lahir, "%e %b %Y")) as kelahiran'),
                    $this->table . '.id',
                    DB::raw('(SELECT MAX(id) FROM transaksi WHERE member_id = member.user_id) as id_trans'),
                    $this->table . '.no_member',
                    $this->table . '.foto',
                    $this->table . '.nama',
                    $this->table . '.jenis_kelamin',
                    $this->table . '.email',
                    $this->table . '.telepon',
                    'member.user_id as muser_id',
                    DB::raw('IF(' . $this->table . '.saldo, ' . $this->table . '.saldo, 0) as saldo'),
                    DB::raw('CONCAT((SELECT COUNT(id) FROM transaksi WHERE member_id = member.user_id AND status_pembayaran = "terbayar"), " kali") as count_trans')
                );
            $dJson->orderBy($this->table . '.id', 'DESC')
                ->get();

            return datatables()->of($dJson)
                ->addColumn('action', 'information.his_member.content.data.action_button')
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
                    ->leftJoin('pegawai', 'pegawai.id', '=', $this->table_transaksi . '.dokter_id')
                    ->leftJoin('room', 'room.id', '=', $this->table_transaksi . '.room_id')
                    ->select(
                        $this->table_transaksi . '.created_at as tanggal',
                        $this->table_transaksi . '.id',
                        'room.name as ruangan',
                        'pegawai.nama as dokter',
                        $this->table_transaksi . '.no_transaksi as no_trans',
                    )
                    ->where($this->table_transaksi . '.member_id', $request->member)
                    ->where($this->table_transaksi . '.status_pembayaran', 'terbayar')
                    ->where('pegawai.role', 3)
                    ->orderBy(DB::raw('DATE(transaksi.created_at)'), 'DESC')
                    ->get())
                    ->addColumn('action', 'information.his_member.content.data.action_history_button')
                    ->rawColumns(['action'])
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
                    ->addColumn('status', 'information.his_member.content.data.status_info')
                    ->rawColumns(['status'])
                    ->addIndexColumn()
                    ->make(true);
            }
        }
    }

    public function show($id)
    {
        return view('information.his_member.content.detail.modal.detail', [
            'data' => $id
        ]);
    }
}
