<?php

namespace App\Http\Controllers\Web\Transaction;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_encode;
use App\Models\Transaction\SpendModel;

class SpendController extends Controller
{
    protected $table = 'pengeluaran';
    protected $table_detail = 'pengeluaran_detail';
    protected $table_pegawai = 'users';

    private $validate_message = [
        'no_pengeluaran' => 'required',
        'total_pengeluaran' => 'required|numeric',
        'tanggal' => 'required'
    ];

    public function fields($request, $method = false)
    {
        $data_add = $method == 'add' ? [
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s", strtotime($request->tanggal)),
        ] : [
            'updated_at' => date("Y-m-d H:i:s", strtotime($request->tanggal)),
        ];

        $data =  [
            'pegawai_id' => Auth::user()->id,
            'no_pengeluaran' => $request->no_pengeluaran,
            'total_pengeluaran' => $request->total_pengeluaran,
        ];

        return array_merge($data_add, $data);
    }

    public function validated($mess, $request)
    {
        $validator = \Validator::make($request->all(), $this->validate_message);
        if ($validator->fails()) {
            $d_error = '<ul>';
            foreach ($validator->errors()->all() as $row) {
                $d_error .= '<li>' . $row . '</li>';
            }
            $d_error .= '</ul>';
            $mess['msg'] = 'Form bertanda bintang wajib diisi!' . $d_error;
            $mess['cd'] = 500;
            echo json_encode($mess);
            exit;
        }
    }

    public function validated_detail_pengeluaran($mess, $request)
    {
        $attributeNames = array(
            'keterangan' => 'keterangan',
            'harga' => 'harga',
            'jumlah' => 'jumlah',
        );

        $message = array(
            'keterangan' => 'required|',
            'harga' => 'required|numeric',
            'jumlah' => 'required|numeric'
        );

        $customMessages = [
            'keterangan.required' => 'Bidang area :attribute wajib diisi',
            'harga.required' => 'Bidang isian :attribute wajib diisi',
            'jumlah.required' => 'Bidang isian :attribute wajib diisi'
        ];

        $validator = \Validator::make($request->all(), $message, $customMessages);
        $validator->setAttributeNames($attributeNames);

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
        return view('trans.spend.sell.index', [
            'action' => route('trans.spends.store'),
            'autoNoPengeluaran' => SpendModel::getAutoNoPengeluaran(),
            'js' => [
                's-home/trans/spend/sell/js/sell.js',
            ],
            'attribute' => [
                'm_transaction' => 'true',
                'menu_sell' => 'active menu-open',
                'title_bc' => 'Transaksi - Pengeluaran',
                'desc_bc' => 'Digunakan untuk media menampilkan, menambah, mengubah dan menghapus pengeluaran',
            ]
        ]);
    }

    public function create()
    {
        return view('trans.spend.sell.content.form.modal.form', [
            'action' => route('trans.spends.store'),
            'autoNoPengeluaran' => SpendModel::getAutoNoPengeluaran(),
        ]);
    }

    public function update($id = false)
    {
        if (!empty($id)) {
            $dataE = DB::table($this->table)->where($this->table . '.id', $id)->first();

            if (!$dataE) {
                return abort(404);
            }
            return view('trans.spend.sell.content.form.modal.form', [
                'action' => route('trans.spends.store'),
                'autoNoPengeluaran' => SpendModel::getAutoNoPengeluaran(),
                'dataE' => $dataE,
            ]);
        }
    }

    public function store(Request $request)
    {
        $mess = null;
        $this->validated($mess, $request);
        $tambah = null;
        if (empty($request->id)) {
            if (!empty($request->keterangan)) {
                $pengeluaranId = DB::table($this->table)->insertGetId($this->fields($request, 'add'));

                if ($pengeluaranId) {
                    $dataDetail = array();
                    foreach ($request->keterangan as $num => $ktrgn) {
                        $dataDetail[] = array(
                            'pengeluaran_id' => $pengeluaranId,
                            'keterangan' => $ktrgn,
                            'jumlah' => $request->jumlah[$num],
                            'harga' => $request->harga[$num],
                            'created_at' => date("Y-m-d H:i:s", strtotime($request->tanggal)),
                        );
                    }
                    $tambah = DB::table($this->table_detail)->insert($dataDetail);
                }
            } elseif (empty($request->keterangan)) {
                $this->validated_detail_pengeluaran($mess, $request);
            }

            if ($tambah) {
                $mess['msg'] = 'Data sukses ditambahkan';
                $mess['cd'] = 200;
            } else {
                $mess['msg'] = 'Data gagal ditambahkan';
                $mess['cd'] = 500;
            }
        }

        if (!empty($request->id)) {
            try {
                $affected = 0;
                $affected_ = 0;

                $elmTable = DB::table($this->table)->where('id', $request->id);
                $affected = $elmTable->update($this->fields($request, 'edit'));

                if (!empty($request->keterangan)) {
                    $dataDetailIns = array();
                    foreach ($request->keterangan as $num => $ktrgn) {
                        $dataDetailIns[] = array(
                            'pengeluaran_id' => $request->id,
                            'keterangan' => $ktrgn,
                            'jumlah' => $request->jumlah[$num],
                            'harga' => $request->harga[$num],
                            'created_at' => date("Y-m-d H:i:s", strtotime($request->tanggal)),
                        );
                    }
                    DB::table($this->table_detail)->insert($dataDetailIns);
                }

                if (!empty($request->eketerangan)) {
                    $dataDetail = array();
                    $dataWhereId = array();
                    $dataWherePembId = array();

                    foreach ($request->eketerangan as $num => $ktrgn) {
                        $dataDetailUpdate = DB::table($this->table_detail);

                        $dataWhereId[$num] = array(
                            'id' => $request->idDetail[$num],
                        );

                        $dataWherePembId[$num] = array(
                            'pengeluaran_id' => $request->id,
                        );

                        $dataDetail[$num] = array(
                            'keterangan' => $ktrgn,
                            'jumlah' => $request->ejumlah[$num],
                            'harga' => $request->eharga[$num],
                            'updated_at' => date("Y-m-d H:i:s", strtotime($request->tanggal)),
                        );

                        if (!empty($request->delete_pengeluaran[$num])) {
                            $affected_ = $dataDetailUpdate
                                ->where($dataWhereId[$num])
                                ->delete();
                        } else {
                            $affected_ = $dataDetailUpdate
                                ->where($dataWhereId[$num])
                                ->where($dataWherePembId[$num])
                                ->update($dataDetail[$num]);
                        }
                    }
                } elseif (empty($request->eketerangan) && empty($request->keterangan)) {
                    $this->validated_detail_pengeluaran($mess, $request);
                }

                $mess['msg'] = 'Data sukses disimpan' . (($affected == 0 && $affected_ == 0) ? ", namun tidak ada perubahan" : " dan diubah");
                $mess['cd'] = 200;
            } catch (Exception $ex) {
                $mess['msg'] = 'Data gagal disimpan' . $ex;
                $mess['cd'] = 500;
            }
        }
        echo json_encode($mess);
    }

    public function _data()
    {
        return view('trans.spend.sell.content.data.table');
    }

    public function _json()
    {
        if (request()->ajax()) {
            return datatables()->of(DB::table($this->table)
                ->leftJoin(
                    $this->table_pegawai,
                    $this->table_pegawai . '.id',
                    '=',
                    $this->table . '.pegawai_id'
                )
                ->select(
                    DB::raw('DATE(' . $this->table . '.updated_at' . ') as tanggal'),
                    $this->table . '.id',
                    $this->table . '.total_pengeluaran',
                    $this->table_pegawai . '.name as pegawai'
                )
                ->orderBy($this->table . '.created_at', 'DESC')
                ->get())
                ->addColumn('action', 'trans.spend.sell.content.data.action_button')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function _load(Request $request)
    {
        // post data ke table
        $data_detail_peng = DB::table($this->table_detail)
            ->select(
                $this->table_detail . '.id',
                $this->table_detail . '.harga',
                $this->table_detail . '.jumlah',
                $this->table_detail . '.keterangan'
            )
            ->where('pengeluaran_id', $request->id)->get();
        echo json_encode($data_detail_peng);
    }

    public function destroy($id)
    {
        $mess = null;
        DB::table($this->table_detail)->where('pengeluaran_id', $id)->delete();
        $data = DB::table($this->table)->where('id', $id);
        $hapus = $data->delete();

        if ($hapus) {
            $mess['msg'] = 'Data sukses dihapus!';
            $mess['cd'] = 200;
        } else {
            $mess['msg'] = 'Data gagal dihapus!';
            $mess['cd'] = 500;
        }
        echo json_encode($mess);
    }
}
