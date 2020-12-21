<?php

namespace App\Http\Controllers\Web\Transaction;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_encode;
use App\Models\Transaction\BuyModel;

class BuyController extends Controller
{
    protected $table = 'pembelian';
    protected $table_detail = 'pembelian_detail';
    protected $table_supplier = 'supplier';
    protected $table_pegawai = 'users';
    protected $table_produk = 'produk';

    private $validate_message = [
        'no_pembelian' => 'required',
        'total_pembelian' => 'required|numeric',
        'tanggal' => 'required'
    ];

    private $validate_message_supp = [
        'telepon' => 'required',
        'alamat' => 'required',
    ];

    public function fields($request, $method = false, $supplier = false)
    {
        $data_add = $method == 'add' ? [
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s", strtotime($request->tanggal)),
            'status' => 1
        ] : [
            'updated_at' => date("Y-m-d H:i:s", strtotime($request->tanggal)),
        ];

        $data =  [
            'pegawai_id' => Auth::user()->id,
            'supplier_id' => $supplier ? $supplier : $request->ssupplier,
            'no_pembelian' => $request->no_pembelian,
            'total_pembelian' => $request->total_pembelian,
            'keterangan' => $request->keterangan
        ];

        return array_merge($data_add, $data);
    }

    public function fields_supp($request)
    {
        return [
            'cabang_id' => 0,
            'nama' => $request->isupplier,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
        ];
    }

    public function validated($mess, $request)
    {
        $attributeNames = array(
            'isupplier' => 'supplier',
            'ssupplier' => 'supplier',
        );

        $message = $request->has('isupplier') ?
            array_merge(
                $this->validate_message,
                array_merge(array(
                    'isupplier' => 'required'
                ), $this->validate_message_supp)
            )
            : array_merge(
                $this->validate_message,
                array(
                    'ssupplier' => 'required|not_in:0'
                )
            );

        $customMessages = [
            'ssupplier.required' => 'Bidang pilihan :attribute wajib dipilih'
        ];

        $validator = \Validator::make($request->all(), $message, $customMessages);
        $validator->setAttributeNames($attributeNames);

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

    public function validated_detail_pembelian($mess, $request)
    {
        $attributeNames = array(
            'produk' => 'produk',
            'eharga' => 'harga',
            'harga' => 'harga',
        );

        $message = array(
            'eharga.*' => 'required|string|distinct|numeric',
            'harga.*' => 'required|string|distinct|numeric',
        );

        $customMessages = [
            'produk.required' => 'Bidang pilihan :attribute wajib dipilih',
            'eharga.*.numeric' => 'Bidang isian harga harus berupa Angka',
            'harga.*.numeric' => 'Bidang isian harga harus berupa Angka',
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
        return view('trans.purchase.buy.index', [
            'action' => route('trans.purchases.store'),
            'actionCK' => route('trans.purchases.checklist.store_'),
            'autoNoPembelian' => BuyModel::getAutoNoPembelian(),
            'js' => [
                's-home/trans/purchase/buy/js/buy.js',
            ],
            'attribute' => [
                'm_transaction' => 'true',
                'menu_buy' => 'active menu-open',
                'title_bc' => 'Transaksi - Pembelian',
                'desc_bc' => 'Digunakan untuk media menampilkan, menambah, mengubah dan menghapus pembelian',
            ]
        ]);
    }

    public function create()
    {
        return view('trans.purchase.buy.content.form.modal.form', [
            'action' => route('trans.purchases.store'),
            'autoNoPembelian' => BuyModel::getAutoNoPembelian(),
        ]);
    }

    public function update($id = false)
    {
        if (!empty($id)) {
            $dataE = DB::table($this->table)->where($this->table . '.id', $id)->first();

            if (!$dataE) {
                return abort(404);
            }

            return view('trans.purchase.buy.content.form.modal.form', [
                'action' => route('trans.purchases.store'),
                'autoNoPembelian' => BuyModel::getAutoNoPembelian(),
                'dataE' => $dataE,
            ]);
        }
    }

    public function update_($id = false)
    {
        if (!empty($id)) {
            $dataE = DB::table($this->table)
                ->leftJoin(
                    $this->table_supplier,
                    $this->table_supplier . '.id',
                    '=',
                    $this->table . '.supplier_id'
                )
                ->select(
                    $this->table . '.id',
                    $this->table . '.no_pembelian',
                    $this->table . '.total_pembelian',
                    $this->table . '.keterangan',
                    $this->table . '.created_at as tanggal',
                    $this->table_supplier . '.nama',
                    $this->table_supplier . '.telepon',
                    $this->table_supplier . '.alamat'
                )
                ->where($this->table . '.id', $id)->first();

            if (!$dataE) {
                return abort(404);
            }
            return view('trans.purchase.buy.content.form.modal.formChecklist', [
                'actionCK' => route('trans.purchases.checklist.store_'),
                'dataE' => $dataE,
            ]);
        }
    }

    public function store_(Request $request)
    {
        $mess = null;
        try {
            if (!empty($request->id)) {
                if ($request->has('eid')) {
                    $dataDetail = array();
                    $dataWhereId = array();
                    $dataWherePembId = array();
                    $dataUpdate = DB::table($this->table);
                    foreach ($request->eid as $num => $id) {
                        $dataDetailUpdate = DB::table($this->table_detail);

                        $dataWhereId[$num] = array(
                            'id' => $id,
                        );

                        $dataWherePembId[$num] = array(
                            'pembelian_id' => $request->id,
                        );

                        $dataDetail[$num] = array(
                            'status' => 2,
                        );

                        $dataDetailUpdate
                            ->where($dataWhereId[$num])
                            ->where($dataWherePembId[$num])
                            ->update($dataDetail[$num]);
                    }

                    $cek = DB::table($this->table_detail)
                        ->where('pembelian_id', '=', $request->id)
                        ->where('status', '!=', 2)
                        ->count();

                    if ($cek == 0) {
                        $dataUpdate->where(
                            'id',
                            $request->id
                        )->update(
                            [
                                'status' => 2
                            ]
                        );
                        $update_stok = DB::table($this->table_detail)->where('pembelian_id', '=', $request->id)->get();

                        foreach ($update_stok as $ro) {
                            if ($ro->status == 2) {
                                $data = DB::table($this->table_produk)->where('id', $ro->produk_id);
                                $stok = $data->first()->stok + $ro->jumlah;
                                $data->update(['stok' => $stok]);
                            }
                        }
                    } else {
                        $dataUpdate->where(
                            'id',
                            $request->id
                        )->update(
                            [
                                'status' => 3
                            ]
                        );
                    }
                }
            }
            $mess['msg'] = 'Data sukses disimpan';
            $mess['cd'] = 200;
        } catch (Exception $ex) {
            $mess['msg'] = 'Data gagal disimpan' . $ex;
            $mess['cd'] = 500;
        }
        echo json_encode($mess);
    }

    public function store(Request $request)
    {
        $mess = null;
        $this->validated($mess, $request);
        $supplier = null;
        $tambah = null;
        if (empty($request->id)) {
            if (!empty($request->produk)) {
                $this->validated_detail_pembelian($mess, $request);
                if ($request->has('isupplier')) {
                    $supplier = DB::table($this->table_supplier)->insertGetId($this->fields_supp($request));
                }
                $pembelianId = DB::table($this->table)->insertGetId($this->fields($request, 'add', $supplier));

                if ($pembelianId) {
                    if (!empty($request->produk)) {
                        $dataDetail = array();
                        foreach ($request->produk as $num => $prd) {
                            $dataDetail[] = array(
                                'status' => 1,
                                'pembelian_id' => $pembelianId,
                                'produk_id' => $prd,
                                'jumlah' => $request->jumlah[$num],
                                'harga' => $request->harga[$num],
                                'created_at' => date("Y-m-d H:i:s", strtotime($request->tanggal)),
                            );
                        }
                        $tambah = DB::table($this->table_detail)->insert($dataDetail);
                    } elseif (empty($request->produk)) {
                        $this->validated_detail_pembelian($mess, $request);
                    }
                }
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

                $elmTable = DB::table($this->table)->where('id', $request->id);
                if ($request->has('isupplier')) {
                    $supplier = DB::table($this->table_supplier)->insertGetId($this->fields_supp($request));
                }
                $affected = $elmTable->update($this->fields($request, 'edit', $supplier));

                if (!empty($request->produk)) {
                    $this->validated_detail_pembelian($mess, $request);
                    $dataDetailIns = array();
                    foreach ($request->produk as $num => $prd) {
                        $dataDetailIns[] = array(
                            'status' => 1,
                            'pembelian_id' => $request->id,
                            'produk_id' => $prd,
                            'jumlah' => $request->jumlah[$num],
                            'harga' => $request->harga[$num],
                            'created_at' => date("Y-m-d H:i:s", strtotime($request->tanggal)),
                        );
                    }
                    DB::table($this->table_detail)->insert($dataDetailIns);
                }

                if (!empty($request->eproduk)) {
                    $this->validated_detail_pembelian($mess, $request);
                    $dataDetail = array();
                    $dataWhereId = array();
                    $dataWherePembId = array();

                    foreach ($request->eproduk as $num => $prd) {
                        $dataDetailUpdate = DB::table($this->table_detail);

                        $dataWhereId[$num] = array(
                            'id' => $request->idDetail[$num],
                        );

                        $dataWherePembId[$num] = array(
                            'pembelian_id' => $request->id,
                        );

                        $dataDetail[$num] = array(
                            'status' => 1,
                            'produk_id' => $prd,
                            'jumlah' => $request->ejumlah[$num],
                            'harga' => $request->eharga[$num],
                            'updated_at' => date("Y-m-d H:i:s", strtotime($request->tanggal)),
                        );

                        if (!empty($request->delete_pembelian[$num])) {
                            $affected = $dataDetailUpdate
                                ->where($dataWhereId[$num])
                                ->delete();
                        } else {
                            $affected = $dataDetailUpdate
                                ->where($dataWhereId[$num])
                                ->where($dataWherePembId[$num])
                                ->update($dataDetail[$num]);
                        }
                    }
                } elseif (empty($request->eproduk) && empty($request->produk)) {
                    $this->validated_detail_pembelian($mess, $request);
                }

                $mess['msg'] = 'Data sukses disimpan' . ($affected == 0 ? ", namun tidak ada perubahan" : " dan diubah");
                $mess['cd'] = 200;
            } catch (Exception $ex) {
                $mess['msg'] = 'Data gagal disimpan' . $ex;
                $mess['cd'] = 500;
            }
        }
        echo json_encode($mess);
    }

    public function _option($table)
    {
        $data = DB::table($table)->get();
        echo json_encode($data);
    }

    public function _data()
    {
        return view('trans.purchase.buy.content.data.table');
    }

    public function _json()
    {
        if (request()->ajax()) {
            return datatables()->of(DB::table($this->table)
                ->leftJoin(
                    $this->table_supplier,
                    $this->table_supplier . '.id',
                    '=',
                    $this->table . '.supplier_id'
                )
                ->leftJoin(
                    $this->table_pegawai,
                    $this->table_pegawai . '.id',
                    '=',
                    $this->table . '.pegawai_id'
                )
                ->select(
                    DB::raw('DATE(' . $this->table . '.updated_at' . ') as tanggal'),
                    $this->table . '.id',
                    $this->table . '.total_pembelian',
                    $this->table . '.status',
                    $this->table_supplier . '.nama as supplier',
                    $this->table_pegawai . '.name as pegawai'
                )
                ->orderBy($this->table . '.created_at', 'DESC')
                ->get())
                ->addColumn('status', 'trans.purchase.buy.content.data.status')
                ->addColumn('action', 'trans.purchase.buy.content.data.action_button')
                ->rawColumns(['status', 'action'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function destroy($id)
    {
        $mess = null;
        DB::table($this->table_detail)->where('pembelian_id', $id)->delete();
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

    public function _detail(Request $request)
    {
        // post data ke table
        $data_detail = DB::table($this->table_supplier)->where('id', $request->id)->get();
        echo json_encode($data_detail);
    }

    public function _load(Request $request)
    {
        // post data ke table
        $data_detail_pemb = DB::table($this->table_detail)
            ->select(
                $this->table_detail . '.id',
                $this->table_detail . '.harga',
                $this->table_produk . '.harga_jual',
                $this->table_detail . '.produk_id',
                $this->table_detail . '.jumlah',
                $this->table_produk . '.nama',
                $this->table_detail . '.status'
            )
            ->leftJoin(
                $this->table_produk,
                $this->table_produk . '.id',
                '=',
                $this->table_detail . '.produk_id'
            )
            ->where('pembelian_id', $request->id)->get();
        echo json_encode($data_detail_pemb);
    }
}
