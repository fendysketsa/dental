<?php

namespace App\Http\Controllers\Web\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Items;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\json_encode;

class ServicesController extends Controller
{
    protected $table = 'layanan';
    protected $table_detail = 'layanan_detail';
    protected $table_detail_cabang = 'layanan_detail_cabang';
    protected $table_branch = 'cabang';
    protected $table_category = 'kategori';
    protected $table_brand = 'brand';

    private $dir = 'app/public/master-data/service/uploads/';
    private $validate_message = [
        'nama' => 'required',
        //'komisi' => 'required|integer',
        'harga' => 'required|between:0,99.99',
    ];

    public function fields($request, $gambar = false)
    {
        return [
            'kategori_id' => $request->kategori,
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            //'komisi' => $request->komisi,
            'harga' => unRupiahFormat($request->harga),
            'waktu_pengerjaan' => $request->waktu_pengerjaan,
            'garansi' => $request->garansi,
            'waktu_garansi' => $request->waktu_garansi,
            'gambar' => $gambar ? $gambar : null,
            'retouch_waktu' => $request->retouch_waktu,
            'retouch_detail' => $request->retouch_detail,
        ];
    }

    public function validated($mess, $request)
    {
        $attributeNames = array(
            'kategori' => 'kategori',
            'cabang' => 'cabang',
        );

        $message = array(
            'kategori' => 'required|not_in:0',
            'cabang' => 'required|not_in:0',
        );

        $customMessages = [
            'kategori.required' => 'Bidang pilihan :attribute wajib dipilih',
            'cabang.required' => 'Bidang pilihan :attribute wajib dipilih',
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

    public function index()
    {
        return view('master-data.services.index', [
            'action' => route('services.store'),
            'js' => [
                's-home/bower_components/ckeditor/ckeditor.js',
                's-home/master-data/services/js/layanan.js',
            ],
            'attribute' => [
                'm_data' => 'true',
                'menu_services' => 'active menu-open',
                'title_bc' => 'Master Data - Treatment',
                'desc_bc' => 'Digunakan untuk media menampilkan, menambah, mengubah dan menghapus keluhan',
            ]
        ]);
    }

    public function store(Request $request)
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
            DB::transaction(function () use ($mess, $filename, $request) {

                $tambahId = DB::table($this->table)->insertGetId($this->fields($request, $filename));
                // //set to db akunting
                // $dataItems = new Items;
                // $dataItems->setConnection('mysql_acc');
                // $dataItems->forceFill([
                //     'id' => $tambahId,
                //     'company_id' => '1', //cabang gulawaxing
                //     'name' => $request->nama,
                //     'description' => $request->deskripsi,
                //     'sale_price' => unRupiahFormat($request->harga),
                //     'purchase_price' => unRupiahFormat($request->harga),
                //     'quantity' => 1,
                //     'enabled' => 1
                // ]);
                // $dataItems->save();
                // //selesai set db akunting

                if (!empty($request->brand)) {
                    $dataDetailIns = array();
                    foreach ($request->brand as $num => $brd) {
                        if (!empty($brd)) {
                            $dataDetailIns[] = array(
                                'layanan_id' => $tambahId,
                                'brand_id' => $brd,
                                'created_at' => date("Y-m-d H:i:s"),
                            );
                            DB::table($this->table_detail)->insert($dataDetailIns);
                        }
                    }
                }

                if (!empty($request->cabang)) {
                    $dataDetailCabangIns = array();
                    foreach ($request->cabang as $num => $cbg) {
                        $dataDetailCabangIns[] = array(
                            'layanan_id' => $tambahId,
                            'cabang_id' => $cbg,
                            'created_at' => date("Y-m-d H:i:s"),
                        );
                    }
                    DB::table($this->table_detail_cabang)->insert($dataDetailCabangIns);
                }

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
                DB::transaction(function () use ($mess, $filename, $request) {
                    $elmTable = DB::table($this->table)->where('id', $request->id);

                    if (empty($request->brand)) {
                        DB::table($this->table_detail)->where('layanan_id', $request->id)->delete();
                    }

                    if (!empty($request->brand)) {
                        DB::table($this->table_detail)->where('layanan_id', $request->id)->delete();
                        $dataDetailIns = array();
                        foreach ($request->brand as $num => $brd) {
                            if (!empty($brd)) {
                                $dataDetailIns[] = array(
                                    'layanan_id' => $request->id,
                                    'brand_id' => $brd,
                                    'created_at' => date("Y-m-d H:i:s"),
                                );
                                DB::table($this->table_detail)->insert($dataDetailIns);
                            }
                        }
                    }

                    if (!empty($request->cabang)) {
                        DB::table($this->table_detail_cabang)->where('layanan_id', $request->id)->delete();
                        $dataDetailCabangIns = array();
                        foreach ($request->cabang as $num => $cbg) {
                            $dataDetailCabangIns[] = array(
                                'layanan_id' => $request->id,
                                'cabang_id' => $cbg,
                                'created_at' => date("Y-m-d H:i:s"),
                            );
                        }
                        DB::table($this->table_detail_cabang)->insert($dataDetailCabangIns);
                    }

                    $affected = $elmTable->update($this->fields($request, $filename));

                    // //set to db akunting
                    // $dataItems = Items::find($request->id);
                    // $dataItems->setConnection('mysql_acc');
                    // $dataItems->company_id = 1;
                    // $dataItems->name = $request->nama;
                    // $dataItems->description = $request->deskripsi;
                    // $dataItems->sale_price = unRupiahFormat($request->harga);
                    // $dataItems->purchase_price = unRupiahFormat($request->harga);
                    // $dataItems->save();
                    // //selesai set db akunting

                    $mess['msg'] = 'Data sukses disimpan' . ($affected == 0 ? ", namun tidak ada perubahan" : " dan diubah");
                    $mess['cd'] = 200;

                    echo json_encode($mess);
                });
            } catch (Exception $ex) {
                $mess['msg'] = 'Data gagal disimpan' . $ex;
                $mess['cd'] = 500;

                echo json_encode($mess);
            }
        }
    }

    public function update($id)
    {
        if (!empty($id)) {
            $data1 = DB::table($this->table)->where($this->table . '.id', $id)->first();

            $data2 = DB::table($this->table_detail)
                ->select(
                    DB::raw('GROUP_CONCAT(' . $this->table_detail . '.brand_id' . ') as brand')
                )
                ->where($this->table_detail . '.layanan_id', $id)
                ->first();

            $data3 = DB::table($this->table_detail_cabang)
                ->select(
                    DB::raw('GROUP_CONCAT(' . $this->table_detail_cabang . '.cabang_id' . ') as cabang')
                )
                ->where($this->table_detail_cabang . '.layanan_id', $id)
                ->first();

            $dataE = [
                'id' => $data1->id,
                'kategori_id' => $data1->kategori_id,
                'cabang' => $data3->cabang,
                'nama' => $data1->nama,
                'deskripsi' => $data1->deskripsi,
                'komisi' => $data1->komisi,
                'gambar' => $data1->gambar,
                'harga' => $data1->harga,
                'waktu_pengerjaan' => $data1->waktu_pengerjaan,
                'garansi' => $data1->garansi,
                'waktu_garansi' => $data1->waktu_garansi,
                'retouch_waktu' => $data1->retouch_waktu,
                'retouch_detail' => $data1->retouch_detail,
                'brand' => $data2->brand,
            ];

            if (!$dataE) {
                return abort(404);
            }
            $data = [
                'action' => route('services.store'),
                'dataE' => $dataE,
            ];
            return view('master-data.services.content.form.modal.form', $data);
        }
    }

    public function _option(Request $request)
    {
        if (empty($request)) {
            return abort(404);
        }
        if (!in_array($request->table, array($this->table_branch, $this->table_category, $this->table_brand))) {
            return abort(404);
        }

        $table_ = DB::table($request->table);
        if ($request->table == 'kategori') {
            $data = $table_->where('jenis', 1)->get();
        } else {
            $data = $table_->get();
        }
        echo json_encode($data);
    }

    public function _data()
    {
        return view('master-data.services.content.data.table');
    }

    public function _json()
    {
        if (request()->ajax()) {
            return datatables()->of(DB::table($this->table)
                ->leftJoin(
                    $this->table_category,
                    $this->table_category . '.id',
                    '=',
                    $this->table . '.kategori_id'
                )
                ->select(
                    DB::raw("(SELECT count(*) from kualifikasi_terapis where layanan_id = " . $this->table . ".id) as count_kual"),
                    DB::raw("(SELECT count(*) from paket_detail where layanan_id = " . $this->table . ".id) as count_paket"),
                    DB::raw("(SELECT GROUP_CONCAT(c.nama) "
                        . "FROM layanan_detail_cabang ldc "
                        . "LEFT JOIN cabang c ON c.id = ldc.cabang_id "
                        . "WHERE ldc.layanan_id = " . $this->table . ".id) as cabang"),
                    $this->table . '.id',
                    $this->table . '.gambar',
                    $this->table . '.nama',
                    $this->table . '.harga',
                    $this->table_category . '.nama as kategori'
                )
                ->orderBy($this->table . '.id', 'DESC')
                ->get())
                ->addColumn('action', 'master-data.services.content.data.action_button')
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
