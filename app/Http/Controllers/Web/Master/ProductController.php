<?php

namespace App\Http\Controllers\Web\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LogStokProdukModel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_encode;

class ProductController extends Controller
{
    protected $table = 'produk';
    protected $table_branch = 'cabang';
    protected $table_category = 'kategori';

    private $dir = 'app/public/master-data/product/uploads/';
    private $validate_message_stok = [
        'stok' => 'required|numeric',
    ];
    private $validate_message = [
        'nama' => 'required',
        'harga_beli' => 'required|between:0,99.99',
        'harga_jual' => 'required|between:0,99.99',
        'harga_jual_member' => 'required|between:0,99.99',
        'gambar' => 'image|mimes:jpeg,png,jpg|max:2048',
    ];

    public function fields($request, $gambar = false)
    {
        $stokEdit = empty($request->id) ? [
            'stok' => $request->stok
        ] : [];

        $main = [
            'kategori_id' => $request->kategori,
            'cabang_id' => $request->cabang,
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
            'harga_beli' => unRupiahFormat($request->harga_beli),
            'harga_jual' => unRupiahFormat($request->harga_jual),
            'harga_jual_member' => unRupiahFormat($request->harga_jual_member),
            'gambar' => $gambar ? $gambar : null,
            'status' => 1,
            // 'retouch_waktu' => $request->retouch_waktu,
            // 'retouch_detail' => $request->retouch_detail,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ];

        return array_merge($stokEdit, $main);
    }

    public function validated($mess, $request)
    {
        $attributeNames = array(
            'cabang' => 'cabang',
            'kategori' => 'kategori'
        );

        $message = array(
            'cabang' => 'required|not_in:0',
            'kategori' => 'required|not_in:0'
        );

        $customMessages = [
            'cabang.required' => 'Bidang pilihan :attribute wajib dipilih',
            'kategori.required' => 'Bidang pilihan :attribute wajib dipilih'
        ];

        $mesStokAdd = empty($request->id) ? $this->validate_message_stok : [];

        $validator = \Validator::make(
            $request->all(),
            array_merge(
                $message,
                $this->validate_message,
                $mesStokAdd
            ),
            $customMessages
        );
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
        return view('master-data.product.index', [
            'action' => route('products.store'),
            'js' => [
                's-home/master-data/product/js/product.js',
            ],
            'attribute' => [
                'm_data' => 'true menu-open',
                'menu_product' => 'active menu-open',
                'title_bc' => 'Master Data - Produk',
                'desc_bc' => 'Digunakan untuk media menampilkan, menambah, mengubah dan menghapus produk',
            ]
        ]);
    }

    public function store(Request $request, LogStokProdukModel $logStokProdukModel)
    {
        $mess = null;
        $filename = null;

        $this->validated($mess, $request);

        if ($request->hasFile('gambar') == 1) {
            $extension = $request->file('gambar')->getClientOriginalExtension();
            if (!empty($request->id)) {
                $image = DB::table($this->table)->where('id', $request->id)->first()->gambar;
                File::delete(storage_path($this->dir) . $image);
            }
            $filename = uniqid() . '_' . time() . '.' . $extension;
            $request->file('gambar')->move(storage_path($this->dir), $filename);
        } else {
            if (!empty($request->id)) {
                if (empty($request->old_img) && $request->old_img == '') {
                    $image = DB::table($this->table)->where('id', $request->id)->first()->gambar;
                    File::delete(storage_path($this->dir) . $image);
                } else {
                    $filename = $request->old_img;
                }
            }
        }

        if (empty($request->id)) {
            DB::transaction(function () use ($request, $filename, $logStokProdukModel, $mess) {

                $tambahId = DB::table($this->table)->insertGetId($this->fields($request, $filename));

                $data['produk_id'] = $tambahId;
                $data['tanggal'] = date('Y-m-d H:i:s');
                $data['masuk'] = $request->stok;
                $data['keluar'] = 0;
                $data['sisa'] = $request->stok;
                $data['keterangan'] = "<strong>STOK OPNAME</strong> Awal";

                $logStokProdukModel->forceFill($data);
                $logStokProdukModel->save();

                if ($tambahId) {
                    $mess['msg'] = 'Data sukses ditambahkan';
                    $mess['cd'] = 200;
                } else {
                    $mess['msg'] = 'Data gagal ditambahkan';
                    $mess['cd'] = 500;
                }

                echo json_encode($mess);
            });
        }

        if (!empty($request->id)) {
            try {
                $elmTable = DB::table($this->table)->where('id', $request->id);
                $affected = $elmTable->update($this->fields($request, $filename));

                $mess['msg'] = 'Data sukses disimpan' . ($affected == 0 ? ", namun tidak ada perubahan" : " dan diubah");
                $mess['cd'] = 200;
            } catch (Exception $ex) {
                $mess['msg'] = 'Data gagal disimpan' . $ex;
                $mess['cd'] = 500;
            }
            echo json_encode($mess);
        }
    }

    public function update($id)
    {
        if (!empty($id)) {
            $dataE = DB::table($this->table)->where($this->table . '.id', $id)->get()->last();

            if (!$dataE) {
                return abort(404);
            }
            $data = [
                'action' => route('products.store'),
                'dataE' => $dataE,
            ];
            return view('master-data.product.content.form.modal.form', $data);
        }
    }

    public function _option(Request $request)
    {
        if (empty($request)) {
            return abort(404);
        }
        if (!in_array($request->table, array($this->table_branch, $this->table_category))) {
            return abort(404);
        }

        $table_ = DB::table($request->table);
        if ($request->table == 'kategori') {
            $data = $table_->where('jenis', 2)->get();
        } else {
            $data = $table_->get();
        }
        echo json_encode($data);
    }

    public function _data()
    {
        return view('master-data.product.content.data.table');
    }

    public function _json()
    {
        if (request()->ajax()) {
            return datatables()->of(DB::table($this->table)
                ->leftJoin(
                    $this->table_branch,
                    $this->table_branch . '.id',
                    '=',
                    $this->table . '.cabang_id'
                )
                ->leftJoin(
                    $this->table_category,
                    $this->table_category . '.id',
                    '=',
                    $this->table . '.kategori_id'
                )
                ->select(
                    DB::raw("(SELECT count(*) from discount_detail where product_id = " . $this->table . ".id) as count_disc"),
                    DB::raw("(SELECT count(*) from transaksi_detail where produk_id is not null AND produk_id = " . $this->table . ".id) as count_trans"),
                    DB::raw("(SELECT count(*) from voucher_detail where produk_id is not null AND produk_id = " . $this->table . ".id) as count_vouch"),
                    DB::raw("(SELECT count(*) from pembelian_detail where produk_id is not null AND produk_id = " . $this->table . ".id) as count_pemb"),
                    $this->table . '.id',
                    $this->table . '.gambar',
                    $this->table . '.nama',
                    $this->table . '.stok',
                    $this->table . '.harga_beli',
                    $this->table . '.harga_jual',
                    $this->table . '.harga_jual_member',
                    $this->table_category . '.nama as kategori',
                    $this->table_branch . '.nama as cabang'
                )
                ->orderBy($this->table . '.id', 'DESC')
                ->get())
                ->addColumn('action', 'master-data.product.content.data.action_button')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function destroy($id)
    {
        $mess = null;
        $data = DB::table($this->table)->where('id', $id);

        $image = $data->first()->gambar;
        if (!empty($image)) {
            File::delete(storage_path($this->dir) . $image);
        }

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