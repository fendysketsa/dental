<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MemberModel as Member;
use App\Models\CabangModel as Cabang;
use App\Models\PenjualanModel as Transaksi;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('home', [
            'js' => [
                's-home/bower_components/raphael/raphael.min.js',
                's-home/bower_components/morris.js/morris.min.js',
                's-home/dist/js/pages/dashboard.js',
                's-home/dist/js/charts/Chart.min.js',
                's-home/dist/js/charts/utils.js',
            ],
            'css' => [
                's-home/bower_components/morris.js/morris.css',
            ],
            'attribute' => [
                'dashboard' => 'true menu-open',
                'title_bc' => 'Dashboard',
                'desc_bc' => 'Control Panel',
            ]
        ]);
    }

    public function data(Request $request)
    {
        $year = empty($request->tahun) ? date('Y') : $request->tahun;

        $visitPie = '';

        $servicesLabel = '';
        $servicesBar = Transaksi::leftJoin(
            'transaksi_detail',
            'transaksi_detail.transaksi_id',
            '=',
            'transaksi.id'
        )->leftJoin(
            'layanan',
            'layanan.id',
            '=',
            'transaksi_detail.layanan_id'
        )->select(
            DB::RAW('layanan.nama'),
            DB::RAW('transaksi_detail.layanan_id'),
            DB::raw('count(transaksi_detail.layanan_id) as jum')
        )->whereNotNull('transaksi_detail.layanan_id')
            ->groupBy('transaksi_detail.layanan_id')
            ->orderBy(DB::raw('count(transaksi_detail.layanan_id)'), 'DESC')
            ->take(10)
            ->get();

        foreach ($servicesBar as $numS => $r) {
            $koma = (count($servicesBar) - 1) == $numS ? '' : ',';
            $servicesLabel .= '"' . $r->nama . '"' . $koma;
        }

        foreach (Cabang::all() as $count => $lok_) {
            $koma = (Cabang::count() - 1) == $count ? '' : ',';

            $visitPie .= Transaksi::where('lokasi_id', $lok_->id)
                ->where(DB::raw('YEAR(transaksi.created_at)'), $year)
                ->select(DB::raw('count(*) as count'))
                ->take(1)->first()->count . $koma;

            $lok = ' and m.lokasi_id = ' . $lok_->id;
            //     $data_performa[] = Transaksi::select(
            //         DB::raw(
            //             '(SELECT count(DISTINCT(m.member_id)) from transaksi m ' .
            //                 'WHERE YEAR(DATE(created_at)) = "' . $year . '" and MONTH(m.created_at) = "1" ' . $lok . ') as jan, ",",
            //             (SELECT count(DISTINCT(m.member_id)) from transaksi m ' .
            //                 'WHERE YEAR(DATE(created_at)) = "' . $year . '" and MONTH(m.created_at) = "2" ' . $lok . ') as feb, ",",
            //             (SELECT count(DISTINCT(m.member_id)) from transaksi m ' .
            //                 'WHERE YEAR(DATE(created_at)) = "' . $year . '" and MONTH(m.created_at) = "3" ' . $lok . ') as mar, ",",
            //             (SELECT count(DISTINCT(m.member_id)) from transaksi m ' .
            //                 'WHERE YEAR(DATE(created_at)) = "' . $year . '" and MONTH(m.created_at) = "4" ' . $lok . ') as apr, ",",
            //             (SELECT count(DISTINCT(m.member_id)) from transaksi m ' .
            //                 'WHERE YEAR(DATE(created_at)) = "' . $year . '" and MONTH(m.created_at) = "5" ' . $lok . ') as mei, ",",
            //             (SELECT count(DISTINCT(m.member_id)) from transaksi m ' .
            //                 'WHERE YEAR(DATE(created_at)) = "' . $year . '" and MONTH(m.created_at) = "6" ' . $lok . ') as jun, ",",
            //             (SELECT count(DISTINCT(m.member_id)) from transaksi m ' .
            //                 'WHERE YEAR(DATE(created_at)) = "' . $year . '" and MONTH(m.created_at) = "7" ' . $lok . ') as jul, ",",
            //             (SELECT count(DISTINCT(m.member_id)) from transaksi m ' .
            //                 'WHERE YEAR(DATE(created_at)) = "' . $year . '" and MONTH(m.created_at) = "8" ' . $lok . ') as agu, ",",
            //             (SELECT count(DISTINCT(m.member_id)) from transaksi m ' .
            //                 'WHERE YEAR(DATE(created_at)) = "' . $year . '" and MONTH(m.created_at) = "9" ' . $lok . ') as sep, ",",
            //             (SELECT count(DISTINCT(m.member_id)) from transaksi m ' .
            //                 'WHERE YEAR(DATE(created_at)) = "' . $year . '" and MONTH(m.created_at) = "10" ' . $lok . ') as okt, ",",
            //             (SELECT count(DISTINCT(m.member_id)) from transaksi m ' .
            //                 'WHERE YEAR(DATE(created_at)) = "' . $year . '" and MONTH(m.created_at) = "11" ' . $lok . ') as nov, ",",
            //             (SELECT count(DISTINCT(m.member_id)) from transaksi m ' .
            //                 'WHERE YEAR(DATE(created_at)) = "' . $year . '" and MONTH(m.created_at) = "12" ' . $lok . ') as des'
            //         )
            //     )->first();

            //     $data_servicesSetData[$count] = Transaksi::select(
            //         DB::raw(
            //             '(SELECT count(m.id) from transaksi_detail m LEFT JOIN transaksi t ON m.transaksi_id = t.id ' .
            //                 'WHERE YEAR(DATE(t.created_at)) = ' . $year .
            //                 ' and m.layanan_id = ' . $servicesBar[0]->layanan_id . ' and t.lokasi_id = ' . Cabang::all()[$count]->id . ') as fav_0,
            //             (SELECT count(m.id) from transaksi_detail m LEFT JOIN transaksi t ON m.transaksi_id = t.id ' .
            //                 'WHERE YEAR(DATE(t.created_at)) = ' . $year .
            //                 ' and m.layanan_id = ' . $servicesBar[1]->layanan_id . ' and t.lokasi_id = ' . Cabang::all()[$count]->id . ') as fav_1,
            //             (SELECT count(m.id) from transaksi_detail m LEFT JOIN transaksi t ON m.transaksi_id = t.id ' .
            //                 'WHERE YEAR(DATE(t.created_at)) = ' . $year .
            //                 ' and m.layanan_id = ' . $servicesBar[2]->layanan_id . ' and t.lokasi_id = ' . Cabang::all()[$count]->id . ') as fav_2,
            //             (SELECT count(m.id) from transaksi_detail m LEFT JOIN transaksi t ON m.transaksi_id = t.id ' .
            //                 'WHERE YEAR(DATE(t.created_at)) = ' . $year .
            //                 ' and m.layanan_id = ' . $servicesBar[3]->layanan_id . ' and t.lokasi_id = ' . Cabang::all()[$count]->id . ') as fav_3,
            //             (SELECT count(m.id) from transaksi_detail m LEFT JOIN transaksi t ON m.transaksi_id = t.id ' .
            //                 'WHERE YEAR(DATE(t.created_at)) = ' . $year .
            //                 ' and m.layanan_id = ' . $servicesBar[4]->layanan_id . ' and t.lokasi_id = ' . Cabang::all()[$count]->id . ') as fav_4,
            //             (SELECT count(m.id) from transaksi_detail m LEFT JOIN transaksi t ON m.transaksi_id = t.id ' .
            //                 'WHERE YEAR(DATE(t.created_at)) = ' . $year .
            //                 ' and m.layanan_id = ' . $servicesBar[5]->layanan_id . ' and t.lokasi_id = ' . Cabang::all()[$count]->id . ') as fav_5,
            //             (SELECT count(m.id) from transaksi_detail m LEFT JOIN transaksi t ON m.transaksi_id = t.id ' .
            //                 'WHERE YEAR(DATE(t.created_at)) = ' . $year .
            //                 ' and m.layanan_id = ' . $servicesBar[6]->layanan_id . ' and t.lokasi_id = ' . Cabang::all()[$count]->id . ') as fav_6,
            //             (SELECT count(m.id) from transaksi_detail m LEFT JOIN transaksi t ON m.transaksi_id = t.id ' .
            //                 'WHERE YEAR(DATE(t.created_at)) = ' . $year .
            //                 ' and m.layanan_id = ' . $servicesBar[7]->layanan_id . ' and t.lokasi_id = ' . Cabang::all()[$count]->id . ') as fav_7,
            //             (SELECT count(m.id) from transaksi_detail m LEFT JOIN transaksi t ON m.transaksi_id = t.id ' .
            //                 'WHERE YEAR(DATE(t.created_at)) = ' . $year .
            //                 ' and m.layanan_id = ' . $servicesBar[8]->layanan_id . ' and t.lokasi_id = ' . Cabang::all()[$count]->id . ') as fav_8,
            //             (SELECT count(m.id) from transaksi_detail m LEFT JOIN transaksi t ON m.transaksi_id = t.id ' .
            //                 'WHERE YEAR(DATE(t.created_at)) = ' . $year .
            //                 ' and m.layanan_id = ' . $servicesBar[9]->layanan_id . ' and t.lokasi_id = ' . Cabang::all()[$count]->id . ') as fav_9'
            //         )
            //     )->take(1)->first();
        }

        // $performaLine = [];
        // $servicesSet = [];
        // $performaLineQuery = Cabang::all();
        // foreach ($performaLineQuery as $countp => $perf) {
        //     $performaLine[] = [
        //         'label' => $perf->nama,
        //         'fill' => false,
        //         'backgroundColor' => Color($countp),
        //         'borderColor' => Color($countp),
        //         'data' => [
        //             $data_performa[$countp]->jan,
        //             $data_performa[$countp]->feb,
        //             $data_performa[$countp]->mar,
        //             $data_performa[$countp]->apr,
        //             $data_performa[$countp]->mei,
        //             $data_performa[$countp]->jun,
        //             $data_performa[$countp]->jul,
        //             $data_performa[$countp]->agu,
        //             $data_performa[$countp]->sep,
        //             $data_performa[$countp]->okt,
        //             $data_performa[$countp]->nov,
        //             $data_performa[$countp]->des
        //         ]
        //     ];

        //     $servicesSet[] = [
        //         'label' => $perf->nama,
        //         'backgroundColor' => Color($countp),
        //         'borderColor' => Color($countp),
        //         'borderWidth' => 1,
        //         'data' => [
        //             $data_servicesSetData[$countp]->fav_0,
        //             $data_servicesSetData[$countp]->fav_1,
        //             $data_servicesSetData[$countp]->fav_2,
        //             $data_servicesSetData[$countp]->fav_3,
        //             $data_servicesSetData[$countp]->fav_4,
        //             $data_servicesSetData[$countp]->fav_5,
        //             $data_servicesSetData[$countp]->fav_6,
        //             $data_servicesSetData[$countp]->fav_7,
        //             $data_servicesSetData[$countp]->fav_8,
        //             $data_servicesSetData[$countp]->fav_9
        //         ]
        //     ];
        // }

        $pie = Member::select(
            DB::raw("CONCAT( "
                . "(SELECT COUNT(*) FROM member where YEAR(member.created_at) = '$year' AND jenis_kelamin = 'Laki-laki'), ',', "
                . "(SELECT COUNT(*) FROM member where YEAR(member.created_at) = '$year' AND jenis_kelamin = 'Perempuan'), ',', "
                . "(SELECT COUNT(*) FROM member where YEAR(member.created_at) = '$year' AND jenis_kelamin is null) "
                . ") AS memberPie")
        )->take(1)->first();

        return view('content_home', [
            'data' => [
                'member' => Member::where(DB::raw('YEAR(member.created_at)'), $year)->get()->count(),
                'memberPie' => empty($pie) ? 0 : $pie->memberPie,
                'cabang' => Cabang::select(
                    DB::raw("GROUP_CONCAT('?',nama,'?') AS cabangList")
                )->take(1)->first()->cabangList,
                'visit' => $visitPie,
                'servicesLabel' => $servicesLabel,
                // 'servicesSet' => json_encode($servicesSet),
                // 'performa' => json_encode($performaLine),
            ],
        ]);
    }
}
