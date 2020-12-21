<?php

namespace App\Http\Controllers\Web\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_encode;

class PackageController extends Controller
{

    protected $table = 'paket';
    protected $table_detail = 'paket_detail';
    protected $table_branch = 'cabang';
    protected $table_services = 'layanan';

    private $dir = 'app/public/master-data/package/uploads/';
    private $validate_message = [
        'nama' => 'required',
        'harga' => 'required|between:0,99.99',
    ];

    public function fields($request, $gambar)
    {
        $data_add = !empty($gambar) ? ['gambar' => $gambar] : ['gambar' => null];
        $data = [
            'cabang_id' => $request->cabang,
            'nama' => $request->nama,
            'harga' => unRupiahFormat($request->harga),
            'keterangan' => $request->keterangan ? $request->keterangan : '-',
            'status' => 1,
        ];

        return array_merge($data_add, $data);
    }

    public function validated($mess, $request)
    {
        $attributeNames = array(
            'cabang' => 'cabang',
            'layanan' => 'layanan',
        );

        $message = array(
            'cabang' => 'required|not_in:0',
            'layanan' => 'required|not_in:0'
        );

        $customMessages = [
            'cabang.required' => 'Bidang pilihan :attribute wajib dipilih',
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

    public function index()
    {
        return view('master-data.package.index', [
            'js' => ['s-home/master-data/package/js/package.js'],
            'attribute' => [
                'm_data' => 'true',
                'menu_package' => 'active menu-open',
                'title_bc' => 'Master Data - Paket',
                'desc_bc' => 'Digunakan untuk media menampilkan, menambah, mengubah dan menghapus paket',
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
            $tambahId = DB::table($this->table)->insertGetId($this->fields($request, $filename));
            if (!empty($request->layanan)) {
                $dataDetailIns = array();
                foreach ($request->layanan as $num => $srv) {
                    $dataDetailIns[] = array(
                        'paket_id' => $tambahId,
                        'layanan_id' => $srv,
                        'created_at' => date("Y-m-d H:i:s"),
                    );
                }
                DB::table($this->table_detail)->insert($dataDetailIns);
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
                $affected = 0;
                $data = DB::table($this->table)->where('id', $request->id);
                if (!empty($request->layanan)) {
                    DB::table($this->table_detail)->where('paket_id', $request->id)->delete();
                    $dataDetailIns = array();
                    foreach ($request->layanan as $num => $srv) {
                        $dataDetailIns[] = array(
                            'paket_id' => $request->id,
                            'layanan_id' => $srv,
                            'created_at' => date("Y-m-d H:i:s"),
                        );
                    }
                    DB::table($this->table_detail)->insert($dataDetailIns);
                }
                $affected = $data->update($this->fields($request, $filename));

                $mess['msg'] = 'Data sukses disimpan' . ($affected == 0 ? ", namun tidak ada perubahan" : " dan diubah");
                $mess['cd'] = 200;
            } catch (Exception $ex) {
                $mess['msg'] = 'Data gagal disimpan' . $ex;
                $mess['cd'] = 500;
            }
        }
        echo json_encode($mess);
    }

    public function _option(Request $request)
    {
        if (empty($request)) {
            return abort(404);
        }
        if (!in_array($request->table, array($this->table_branch, $this->table_services))) {
            return abort(404);
        }

        $table_ = DB::table($request->table);
        $data = $table_->get();

        echo json_encode($data);
    }

    public function create()
    {
        return view('master-data.package.content.form.form', [
            'action' => route('packages.store'),
            'js' => array('js' => 's-home/master-data/package/js/package.js'),
        ]);
    }

    public function update($id = false)
    {
        if (!empty($id)) {
            $data1 = DB::table($this->table)->where($this->table . '.id', $id)->first();
            $data2 = DB::table($this->table_detail)
                ->select(
                    DB::raw('GROUP_CONCAT(' . $this->table_detail . '.layanan_id' . ') as layanan')
                )
                ->where($this->table_detail . '.paket_id', $id)
                ->first();

            $dataE = [
                'id' => $data1->id,
                'gambar' => $data1->gambar,
                'cabang_id' => $data1->cabang_id,
                'nama' => $data1->nama,
                'harga' => $data1->harga,
                'keterangan' => $data1->keterangan,
                'layanan' => $data2->layanan
            ];

            if (!$dataE) {
                return abort(404);
            }

            return view('master-data.package.content.form.form', [
                'action' => route('packages.store'),
                'js' => array('js' => 's-home/master-data/package/js/package.js'),
                'dataE' => $dataE,
            ]);
        }
    }

    public function _data()
    {
        return view('master-data.package.content.data.table');
    }

    public function _json()
    {
        if (request()->ajax()) {
            return datatables()->of(DB::table($this->table)
                ->leftJoin(
                    $this->table_branch,
                    $this->table . '.cabang_id',
                    '=',
                    $this->table_branch . '.id'
                )
                ->select(
                    DB::raw("(SELECT count(*) from transaksi_detail where paket_id is not null AND paket_id = " . $this->table . ".id) as count_paket"),
                    $this->table . '.id',
                    $this->table . '.gambar',
                    $this->table . '.nama',
                    $this->table . '.harga',
                    $this->table . '.keterangan',
                    $this->table_branch . '.nama as cabang'
                )
                ->orderBy($this->table . '.id', 'DESC')
                ->get())
                ->addColumn('action', 'master-data.package.content.data.action_button')
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