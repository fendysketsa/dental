<?php

namespace App\Http\Controllers\Web\Monitoring;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\KunjunganModel as VisitModel;

class KunjunganController extends Controller
{
    protected $table_member = 'member';

    public function index()
    {
        return view('monitoring.kunjungan.index', [
            'js' => [
                's-home/dist/js/charts/Chart.min.js',
                's-home/monitoring/kunjungan/js/kunjungan.js'
            ],
            'attribute' => [
                'm_mntrg' => 'true',
                'menu_mntrg_visit' => 'active menu-open',
                'title_bc' => 'Monitoring - Kunjungan',
                'desc_bc' => 'Digunakan untuk media menampilkan konsumen yang berkunjung',
            ],
        ]);
    }

    public function _json_data()
    {
        return view('monitoring.kunjungan.content.data.table');
    }

    public function _json(Request $request)
    {
        $data_pengunjung = DB::table('transaksi')
            ->leftJoin(
                'member',
                'member.id',
                '=',
                'transaksi.member_id'
            )
            ->select(
                'member.no_member',
                'member.nama',
                'member.jenis_kelamin',
                'member.email',
                'member.telepon',
                DB::raw('(SELECT count(*)
                    FROM transaksi
                    WHERE YEAR(transaksi.created_at) = "' . $request->y . '"
                    AND MONTH(transaksi.created_at) = "' . $request->m . '"
                    AND member_id = member.id) as kunjungan')
            )
            ->where(DB::raw('YEAR(transaksi.created_at)'), $request->y)
            ->where(DB::raw('MONTH(transaksi.created_at)'), $request->m);

        if (!empty(session('cabang_session'))) {
            $data_pengunjung->orWhere('transaksi.lokasi_id', session('cabang_session'));
        }

        $data_pengunjung->groupBy('transaksi.member_id')
            ->orderBy('kunjungan', 'DESC')
            ->orderBy('member.nama', 'ASC')
            ->limit(10)
            ->get();

        if (request()->ajax()) {
            return datatables()->of($data_pengunjung)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function _data(Request $request)
    {
        $data_ch = '[';
        $year = empty($request->y) ? date('Y') : $request->y;

        $lok = !empty(session('cabang_session')) ? ' and m.lokasi_id = ' . session('cabang_session') : '';

        $dataKunjungan = VisitModel::select(
            DB::raw('(SELECT count(DISTINCT(m.member_id)) from transaksi m ' .
                'WHERE YEAR(DATE(created_at)) = "' . $year . '" and MONTH(m.created_at) = "1" ' . $lok . ') as januari'),
            DB::raw('(SELECT count(DISTINCT(m.member_id)) from transaksi m ' .
                'WHERE YEAR(DATE(created_at)) = "' . $year . '" and MONTH(m.created_at) = "2" ' . $lok . ') as februari'),
            DB::raw('(SELECT count(DISTINCT(m.member_id)) from transaksi m ' .
                'WHERE YEAR(DATE(created_at)) = "' . $year . '" and MONTH(m.created_at) = "3" ' . $lok . ') as maret'),
            DB::raw('(SELECT count(DISTINCT(m.member_id)) from transaksi m ' .
                'WHERE YEAR(DATE(created_at)) = "' . $year . '" and MONTH(m.created_at) = "4" ' . $lok . ') as april'),
            DB::raw('(SELECT count(DISTINCT(m.member_id)) from transaksi m ' .
                'WHERE YEAR(DATE(created_at)) = "' . $year . '" and MONTH(m.created_at) = "5" ' . $lok . ') as mei'),
            DB::raw('(SELECT count(DISTINCT(m.member_id)) from transaksi m ' .
                'WHERE YEAR(DATE(created_at)) = "' . $year . '" and MONTH(m.created_at) = "6" ' . $lok . ') as juni'),
            DB::raw('(SELECT count(DISTINCT(m.member_id)) from transaksi m ' .
                'WHERE YEAR(DATE(created_at)) = "' . $year . '" and MONTH(m.created_at) = "7" ' . $lok . ') as juli'),
            DB::raw('(SELECT count(DISTINCT(m.member_id)) from transaksi m ' .
                'WHERE YEAR(DATE(created_at)) = "' . $year . '" and MONTH(m.created_at) = "8" ' . $lok . ') as agustus'),
            DB::raw('(SELECT count(DISTINCT(m.member_id)) from transaksi m ' .
                'WHERE YEAR(DATE(created_at)) = "' . $year . '" and MONTH(m.created_at) = "9" ' . $lok . ') as september'),
            DB::raw('(SELECT count(DISTINCT(m.member_id)) from transaksi m ' .
                'WHERE YEAR(DATE(created_at)) = "' . $year . '" and MONTH(m.created_at) = "10" ' . $lok . ') as oktober'),
            DB::raw('(SELECT count(DISTINCT(m.member_id)) from transaksi m ' .
                'WHERE YEAR(DATE(created_at)) = "' . $year . '" and MONTH(m.created_at) = "11" ' . $lok . ') as november'),
            DB::raw('(SELECT count(DISTINCT(m.member_id)) from transaksi m ' .
                'WHERE YEAR(DATE(created_at)) = "' . $year . '" and MONTH(m.created_at) = "12" ' . $lok . ') as desember')
        )->where(DB::raw('YEAR(created_at)'), $year)->groupBy(DB::raw('YEAR(created_at)'))->get();

        foreach ($dataKunjungan as $buln) {
            $data_ch .= $buln->januari . ', ';
            $data_ch .= $buln->februari . ', ';
            $data_ch .= $buln->maret . ', ';
            $data_ch .= $buln->april . ', ';
            $data_ch .= $buln->mei . ', ';
            $data_ch .= $buln->juni . ', ';
            $data_ch .= $buln->juli . ', ';
            $data_ch .= $buln->agustus . ', ';
            $data_ch .= $buln->september . ', ';
            $data_ch .= $buln->oktober . ', ';
            $data_ch .= $buln->november . ', ';
            $data_ch .= $buln->desember;
        }
        $data_ch .= ']';

        return view('monitoring.kunjungan.content.data.chart', [
            'data' => $data_ch
        ]);
    }
}
