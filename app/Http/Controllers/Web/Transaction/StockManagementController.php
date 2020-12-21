<?php

namespace App\Http\Controllers\Web\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\StockManagementModel;
use Illuminate\Support\Facades\DB;
use App\Models\LogStokProdukModel;

class StockManagementController extends Controller
{
    protected $validate_message = [
        'stok' => 'required|numeric'
    ];

    protected $validate_message_keterangan = [
        'keterangan' => 'required'
    ];

    public function validated($mess, $request)
    {
        $keterangan = $request->has('keterangan') ?
            array_merge($this->validate_message, $this->validate_message_keterangan) : $this->validate_message;

        $validator = \Validator::make($request->all(), $keterangan);
        if ($validator->fails()) {
            $d_error = '<ul>';
            foreach ($validator->errors()->all() as $row) {
                $d_error .= '<li>' . $row . '</li>';
            }
            $d_error .= '</ul>';
            $mess['msg'] = 'Ada beberapa masalah dengan inputan Anda!' . $d_error;
            $mess['cd'] = 500;
            echo json_encode($mess);
            exit;
        }
    }

    public function index()
    {
        return view('trans.stockManagement.index', [
            'action' => '',
            'js' => [
                's-home/trans/stock/js/stock.js'
            ],
            'attribute' => [
                'm_transaction' => 'true',
                'menu_stock' => 'active menu-open',
                'title_bc' => 'Transaksi - Stock',
                'desc_bc' => 'Digunakan untuk media menampilkan produk, stock opname',
            ]
        ]);
    }

    public function update($id, Request $request, StockManagementModel $stockManagementModel, LogStokProdukModel $LogStokProdukModel)
    {
        $mess = null;
        $this->validated($mess, $request);

        DB::transaction(function () use ($id, $request, $stockManagementModel, $LogStokProdukModel) {

            $update = $stockManagementModel->find($id);

            if ($update->stok > $request->stok) {
                $data['produk_id'] = $id;
                $data['tanggal'] = date('Y-m-d H:i:s');
                $data['masuk'] = 0;
                $data['keluar'] = $update->stok - $request->stok;
                $data['sisa'] = $request->stok;
                $data['keterangan'] = "<strong>STOK OPNAME</strong> " . $request->keterangan;

                $LogStokProdukModel->forceFill($data);
                $LogStokProdukModel->save();
            }

            if ($update->stok < $request->stok) {
                $data['produk_id'] = $id;
                $data['tanggal'] = date('Y-m-d H:i:s');
                $data['masuk'] = $request->stok - $update->stok;
                $data['keluar'] = 0;
                $data['sisa'] = $request->stok;
                $data['keterangan'] = "<strong>STOK OPNAME</strong>";

                $LogStokProdukModel->forceFill($data);
                $LogStokProdukModel->save();
            }

            $update->update([
                'stok' => $request->stok
            ]);

            echo json_encode([
                'cd' => 200,
                'msg' => 'Stok sukses diupdate!',
                'up' => $update
            ]);
        });
    }

    public function detail($id)
    {
        if (!empty($id)) {

            $dataE = StockManagementModel::leftJoin('kategori', 'kategori.id', '=', 'produk.kategori_id')
                ->where('kategori.jenis', 2)
                ->where('produk.id', $id)
                ->select('produk.*', 'kategori.nama as kategori')
                ->get()
                ->first();
            if (!$dataE) {
                return abort(404);
            }

            return view('trans.stockManagement.content.form.modal.detail', [
                'js' =>  [
                    's-home/trans/stock/js/stock.js'
                ],
                'dataE' => $dataE,
            ]);
        }
    }

    public function show($id)
    {
        if (!empty($id)) {

            $dataE = StockManagementModel::leftJoin('kategori', 'kategori.id', '=', 'produk.kategori_id')
                ->where('kategori.jenis', 2)
                ->where('produk.id', $id)
                ->select('produk.*', 'kategori.nama as kategori')
                ->get()
                ->first();
            if (!$dataE) {
                return abort(404);
            }

            return view('trans.stockManagement.content.form.modal.form', [
                'action' => route('stocks.update', $id),
                'js' =>  [
                    's-home/trans/stock/js/stock.js'
                ],
                'dataE' => $dataE,
            ]);
        }
    }

    public function _data()
    {
        return view('trans.stockManagement.content.data.table');
    }

    public function _data_history()
    {
        return view('trans.stockManagement.content.data.table_history');
    }

    public function _json()
    {
        if (request()->ajax()) {
            return datatables()->of(StockManagementModel::leftJoin('kategori', 'kategori.id', '=', 'produk.kategori_id')
                ->where('kategori.jenis', 2)
                ->select(
                    'produk.id',
                    'produk.gambar',
                    'produk.nama',
                    'produk.updated_at',
                    'produk.stok',
                    'kategori.nama as kategori'
                )
                ->get())
                ->addColumn('action', 'trans/stockManagement/content/data/action_button')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function _json_history(Request $request)
    {
        if (request()->ajax()) {
            return datatables()->of(LogStokProdukModel::where('produk_id', $request->id_produk)
                ->orderBy('tanggal', 'DESC')
                ->get())
                ->addIndexColumn()
                ->make(true);
        }
    }
}
