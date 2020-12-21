<?php

namespace App\Http\Controllers\Web\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_encode;

class VoucherController extends Controller
{
    protected $table = 'voucher';
    protected $table_services = 'layanan';
    protected $table_detail_layanan = 'voucher_detail_services';

    private $dir = 'app/public/master-data/voucher/uploads/';
    private $validate_message = [
        'nama' => 'required',
        'diskon' => 'required|numeric',
        'berlaku_dari' => 'required',
        'berlaku_sampai' => 'required|after:berlaku_dari',
        'gambar' => 'image|mimes:jpeg,png,jpg|max:2048',
    ];

    public function fields($request, $gambar = false)
    {
        $data_add = !empty($gambar) ? ['gambar' => $gambar] : ['gambar' => null];
        $data = [
            'nama' => $request->nama,
            'diskon' => $request->diskon,
            'berlaku_dari' => $request->berlaku_dari ? date("Y-m-d", strtotime($request->berlaku_dari)) : null,
            'berlaku_sampai' => $request->berlaku_sampai ? date("Y-m-d", strtotime($request->berlaku_sampai)) : null,
            'keterangan' => $request->keterangan,
        ];
        return array_merge($data_add, $data);
    }

    public function validated($mess, $request)
    {

        $attributeNames = array(
            'layanan' => 'layanan',
        );

        $message = array(
            'layanan' => 'required|not_in:0'
        );

        $customMessages = [
            'layanan.required' => 'Bidang pilihan :attribute wajib dipilih'
        ];
        $validator = \Validator::make(
            $request->all(),
            array_merge(
                $message,
                $this->validate_message
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

    public function _addCustomeValidate($request)
    {
        $custVal = DB::select("SELECT * FROM voucher
                WHERE (berlaku_dari >= '" . date("Y-m-d", strtotime($request->berlaku_dari)) . "' OR berlaku_sampai >= '" . date("Y-m-d", strtotime($request->berlaku_dari)) . "')
                AND ( berlaku_dari <= '" . date("Y-m-d", strtotime($request->berlaku_sampai)) . "' OR berlaku_sampai <= '" . date("Y-m-d", strtotime($request->berlaku_sampai)) . "')
                AND id != '" . $request->id . "'");
        if (!empty($custVal)) {
            $mess['msg'] = 'Data gagal disimpan, rentang waktu telah terpakai!';
            $mess['cd'] = 500;
            echo json_encode($mess);
            exit;
        }
    }

    public function index()
    {
        return view('master-data.voucher.index', [
            'action' => route('vouchers.store'),
            'js' => ['s-home/master-data/voucher/js/voucher.js'],
            'attribute' => [
                'm_data' => 'true',
                'menu_voucher' => 'active menu-open',
                'title_bc' => 'Master Data - Voucher',
                'desc_bc' => 'Digunakan untuk media menampilkan, menambah, mengubah dan menghapus voucher',
            ]
        ]);
    }

    public function store(Request $request)
    {
        $mess = null;
        $filename = null;

        $this->validated($mess, $request);
        $this->_addCustomeValidate($request);

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
            $tambahId = DB::table($this->table)->insert($this->fields($request, $filename));
            if (!empty($request->layanan)) {
                $dataDetailIns = array();
                foreach ($request->layanan as $num => $srv) {
                    $dataDetailIns[] = array(
                        'voucher_id' => $tambahId,
                        'layanan_id' => $srv,
                        'created_at' => date("Y-m-d H:i:s"),
                    );
                }
                DB::table($this->table_detail_layanan)->insert($dataDetailIns);
            }
            if ($tambahId) {
                $mess['msg'] = 'Data sukses ditambahkan';
                $mess['cd'] = 200;
            } else {
                $mess['msg'] = 'Data gagal ditambahkan';
                $mess['cd'] = 500;
            }
        }

        if (!empty($request->id)) {
            try {
                $elmTable = DB::table($this->table)->where('id', $request->id);

                if (!empty($request->layanan)) {
                    DB::table($this->table_detail_layanan)->where('voucher_id', $request->id)->delete();
                    $dataDetailIns = array();
                    foreach ($request->layanan as $num => $srv) {
                        $dataDetailIns[] = array(
                            'voucher_id' => $request->id,
                            'layanan_id' => $srv,
                            'created_at' => date("Y-m-d H:i:s"),
                        );
                    }
                    DB::table($this->table_detail_layanan)->insert($dataDetailIns);
                }

                $affected = $elmTable->update($this->fields($request, $filename));

                $mess['msg'] = 'Data sukses disimpan' . ($affected == 0 ? ", namun tidak ada perubahan" : " dan diubah");
                $mess['cd'] = 200;
            } catch (Exception $ex) {
                $mess['msg'] = 'Data gagal disimpan' . $ex;
                $mess['cd'] = 500;
            }
        }
        echo json_encode($mess);
    }

    public function update($id)
    {
        if (!empty($id)) {
            $data1 = DB::table($this->table)->where($this->table . '.id', $id)->first();
            $data2 = DB::table($this->table_detail_layanan)
                ->select(
                    DB::raw('GROUP_CONCAT(' . $this->table_detail_layanan . '.layanan_id' . ') as layanan')
                )
                ->where($this->table_detail_layanan . '.voucher_id', $id)
                ->first();

            $dataE = [
                'id' => $data1->id,
                'nama' => $data1->nama,
                'diskon' => $data1->diskon,
                'berlaku_dari' => $data1->berlaku_dari,
                'berlaku_sampai' => $data1->berlaku_sampai,
                'keterangan' => $data1->keterangan,
                'gambar' => $data1->gambar,
                'layanan' => $data2->layanan
            ];

            if (!$dataE) {
                return abort(404);
            }
            $data = [
                'action' => route('vouchers.store'),
                'dataE' => $dataE,
            ];
            return view('master-data.voucher.content.form.modal.form', $data);
        }
    }

    public function _option(Request $request)
    {
        if (empty($request)) {
            return abort(404);
        }
        if (!in_array($request->table, array($this->table_services))) {
            return abort(404);
        }

        $table_ = DB::table($request->table);
        $data = $table_->get();

        echo json_encode($data);
    }

    public function _data()
    {
        return view('master-data.voucher.content.data.table');
    }

    public function _json()
    {
        if (request()->ajax()) {
            return datatables()->of(DB::table($this->table)
                ->select(
                    $this->table . '.id',
                    $this->table . '.nama',
                    $this->table . '.diskon',
                    $this->table . '.berlaku_dari',
                    $this->table . '.berlaku_sampai',
                    $this->table . '.keterangan',
                    $this->table . '.gambar'
                )
                ->orderByDesc('id')
                ->get())
                ->addColumn('action', 'master-data.voucher.content.data.action_button')
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
