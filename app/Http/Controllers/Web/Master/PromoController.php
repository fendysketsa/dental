<?php

namespace App\Http\Controllers\Web\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_encode;

class PromoController extends Controller
{
    protected $table = 'promo';
    protected $table_cabang = 'cabang';

    private $dir = 'app/public/master-data/promo/uploads/';
    private $validate_message = [
        'berlaku_dari' => 'required',
        'berlaku_sampai' => 'required|after:berlaku_dari',
    ];

    public function fields($request, $gambar)
    {
        $data_add = !empty($gambar) ? ['gambar' => $gambar] : ['gambar' => null];
        $data = [
            'berlaku_dari' => $request->berlaku_dari ? date("Y-m-d", strtotime($request->berlaku_dari)) : null,
            'berlaku_sampai' => $request->berlaku_sampai ? date("Y-m-d", strtotime($request->berlaku_sampai)) : null,
            'cabang_id' => $request->cabang ? $request->cabang : null,
            'deskripsi' => $request->deskripsi ? $request->deskripsi : '-',
        ];
        return array_merge($data_add, $data);
    }

    public function validated($mess, $request)
    {
        $file = !empty($request->id) ?
            $this->gambarPromo($request->id) : '';

        $image_ = empty($request->id) ? [
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ] : ((empty($file) and $request->hasFile('gambar') != 1) ? [
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ] : [
            'gambar' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $validator = \Validator::make($request->all(), array_merge($image_, $this->validate_message));
        if ($validator->fails()) {
            $d_error = '<ul>';
            foreach ($validator->errors()->all() as $row) {
                $d_error .= '<li>' . $row . '</li>';
            }
            $d_error .= '</ul>';
            $mess['msg'] = $request->has('gambar') . 'Ada beberapa masalah dengan inputan Anda!' . $d_error;
            $mess['cd'] = 500;
            echo json_encode($mess);
            exit;
        }
    }

    public function gambarPromo($id)
    {
        return DB::table($this->table)->where('id', $id)->select('gambar')->first()->gambar;
    }

    public function index()
    {
        return view('master-data.promo.index', [
            'js' => [
                's-home/master-data/promo/js/promo.js',
                's-home/bower_components/ckeditor/ckeditor.js'
            ],
            'attribute' => [
                'm_data' => 'true',
                'menu_promo' => 'active menu-open',
                'title_bc' => 'Master Data - Promo',
                'desc_bc' => 'Digunakan untuk media menampilkan, menambah, mengubah dan menghapus promo',
            ]
        ]);
    }

    public function _addCustomeValidate($request)
    {
        $custVal = DB::select("SELECT * FROM diskon
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
            $tambah = DB::table($this->table)->insert($this->fields($request, $filename));
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
                $affected = DB::table($this->table)->where('id', $request->id)->update($this->fields($request, $filename));
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
        $data = DB::table($this->table_cabang)->get();
        echo json_encode($data);
    }

    public function create()
    {
        return view('master-data.promo.content.form.form', [
            'action' => route('promos.store'),
            'js' => array('js' => 's-home/master-data/promo/js/promo.js'),
        ]);
    }

    public function update($id = false)
    {
        if (!empty($id)) {
            $dataE = DB::table($this->table)->where('id', $id)->get()->last();
            if (!$dataE) {
                return abort(404);
            }

            return view('master-data.promo.content.form.form', [
                'action' => route('promos.store'),
                'js' => array('js' => 's-home/master-data/promo/js/promo.js'),
                'dataE' => $dataE,
            ]);
        }
    }

    public function _data()
    {
        return view('master-data.promo.content.data.table');
    }

    public function _json()
    {
        if (request()->ajax()) {
            return datatables()->of(DB::table($this->table)
                ->leftJoin(
                    $this->table_cabang,
                    $this->table_cabang . '.id',
                    '=',
                    $this->table . '.cabang_id'
                )
                ->select(
                    $this->table . '.id',
                    $this->table . '.gambar',
                    $this->table_cabang . '.nama as cabang',
                    $this->table . '.berlaku_dari as tanggal_dari',
                    $this->table . '.berlaku_sampai as tanggal_sampai'
                )->get())
                ->addColumn('action', 'master-data.promo.content.data.action_button')
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