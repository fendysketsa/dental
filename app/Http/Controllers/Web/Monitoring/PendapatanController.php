<?php

namespace App\Http\Controllers\Web\Monitoring;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\IncomesModel;
use Illuminate\Support\Facades\DB;

class PendapatanController extends Controller
{
    protected $table = 'transaksi';
    protected $table_pengeluaran = 'pengeluaran';
    protected $table_pembelian = 'pembelian';
    protected $table_modal = 'set_modal';

    private $total_pemasukan = 0;
    private $total_pengeluaran = 0;
    private $total_modale = 0;

    public function index()
    {
        return view('monitoring.pendapatan.index', [
            'js' => [
                's-home/monitoring/pendapatan/js/pendapatan.js'
            ],
            'attribute' => [
                'm_mntrg' => 'true',
                'menu_mntrg_income' => 'active menu-open',
                'title_bc' => 'Monitoring - Pendapatan',
                'desc_bc' => 'Digunakan untuk media menampilkan pendapatan',
            ],
        ]);
    }

    public function _data(Request $request)
    {
        return view('monitoring.pendapatan.content.data.table_' . $request->load);
    }

    public function _json(Request $request)
    {
        if ($request->load == 'pemasukan') {
            if (request()->ajax()) {
                $data = DB::table($this->table)
                    ->select(
                        $this->table . '.id',
                        DB::raw('DATE(' . $this->table . '.created_at) as tanggal'),
                        $this->table . '.no_transaksi',
                        $this->table . '.total_biaya'
                    );

                $data->where($this->table . '.status', 3)
                    ->where($this->table . '.status_pembayaran', 'terbayar');

                if (!empty(session('cabang_session'))) {
                    $data->where($this->table . '.lokasi_id', session('cabang_session'));
                }

                if (!empty($request->starts) && !empty($request->ends)) {
                    $data->whereBetween(
                        DB::raw('DATE(' . $this->table . '.created_at)'),
                        [$request->starts, $request->ends]
                    );
                }

                $data_pemasukan = $data->orderBy(DB::raw('DATE(' . $this->table . '.created_at)'), 'DESC')
                    ->orderBy($this->table . '.no_transaksi', 'DESC');

                foreach ($data_pemasukan->get() as $row) {
                    $this->total_pemasukan += $row->total_biaya;
                }

                return datatables()->of($data_pemasukan->get())
                    ->addIndexColumn()
                    ->with('total_pemasukan', $this->total_pemasukan)
                    ->make(true);
            }
        }

        if ($request->load == 'pengeluaran') {
            if ($request->data == 'pengeluaran') {
                if (request()->ajax()) {
                    $data = DB::table($this->table_pengeluaran)
                        ->leftJoin('pegawai', 'pegawai.id', '=', 'pengeluaran.pegawai_id');

                    if (!empty($request->starts) && !empty($request->ends)) {
                        $data->whereBetween(
                            DB::raw('DATE(pengeluaran.created_at)'),
                            [$request->starts, $request->ends]
                        );
                    }

                    $data->select(
                        $this->table_pengeluaran . '.id',
                        DB::raw('DATE(' . $this->table_pengeluaran . '.created_at) as tanggal'),
                        $this->table_pengeluaran . '.no_pengeluaran as no_transaksi',
                        'pegawai.nama as operator',
                        $this->table_pengeluaran . '.total_pengeluaran as total_biaya'
                    )
                        ->orderBy(DB::raw('DATE(' . $this->table_pengeluaran . '.created_at)'), 'DESC')
                        ->orderBy($this->table_pengeluaran . '.no_pengeluaran', 'DESC');

                    $dataJson = $data->get();

                    foreach ($dataJson as $row) {
                        $this->total_pengeluaran += $row->total_biaya;
                    }

                    return datatables()->of($dataJson)
                        ->addIndexColumn()
                        ->with('total_pengeluaran', $this->total_pengeluaran)
                        ->make(true);
                }
            }

            if ($request->data == 'pembelian') {
                if (request()->ajax()) {
                    $data = DB::table($this->table_pembelian)
                        ->leftJoin('pegawai', 'pegawai.id', '=', 'pembelian.pegawai_id')
                        ->leftJoin('supplier', 'supplier.id', '=', 'pembelian.supplier_id');

                    if (!empty($request->starts) && !empty($request->ends)) {
                        $data->whereBetween(
                            DB::raw('DATE(pembelian.created_at)'),
                            [$request->starts, $request->ends]
                        );
                    }

                    $data->select(
                        $this->table_pembelian . '.id',
                        DB::raw('DATE(' . $this->table_pembelian . '.created_at) as tanggal'),
                        $this->table_pembelian . '.no_pembelian as no_transaksi',
                        'pegawai.nama as operator',
                        'supplier.nama as supplier',
                        $this->table_pembelian . '.total_pembelian as total_biaya'
                    )
                        ->orderBy(DB::raw('DATE(' . $this->table_pembelian . '.created_at)'), 'DESC')
                        ->orderBy($this->table_pembelian . '.no_pembelian', 'DESC');

                    $dataJson = $data->get();
                    foreach ($dataJson as $row) {
                        $this->total_pengeluaran += $row->total_biaya;
                    }

                    return datatables()->of($dataJson)
                        ->addIndexColumn()
                        ->with('total_pembelian', $this->total_pengeluaran)
                        ->make(true);
                }
            }
        }

        if ($request->load == 'modal') {
            if (request()->ajax()) {
                $data = DB::table($this->table_modal)
                    ->leftJoin('shift', 'shift.id', '=', 'set_modal.shift_id')
                    ->leftJoin('pegawai', 'pegawai.id', '=', 'set_modal.pegawai_id');

                if (!empty($request->starts) && !empty($request->ends)) {
                    $data->whereBetween(
                        DB::raw('DATE(set_modal.created_at)'),
                        [$request->starts, $request->ends]
                    );
                }

                $data->select(
                    $this->table_modal . '.id',
                    DB::raw('DATE(' . $this->table_modal . '.created_at) as tanggal'),
                    $this->table_modal . '.nominal as total_biaya',
                    'shift.nama as shift',
                    'pegawai.nama as operator'
                )
                    ->orderBy(DB::raw('DATE(' . $this->table_modal . '.created_at)'), 'DESC')
                    ->orderBy($this->table_modal . '.id', 'DESC');

                $dataJson = $data->get();
                foreach ($dataJson as $row) {
                    $this->total_modale += $row->total_biaya;
                }

                return datatables()->of($data)
                    ->addIndexColumn()
                    ->with('total_modale', $this->total_modale)
                    ->make(true);
            }
        }
    }
}
