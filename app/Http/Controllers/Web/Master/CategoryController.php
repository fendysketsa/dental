<?php

namespace App\Http\Controllers\Web\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use function GuzzleHttp\json_encode;

class CategoryController extends Controller
{
    protected $table = 'kategori';

    private $dir_icon = 'app/public/master-data/category/uploads/icon/';

    private $validate_message = [
        'nama' => 'required',
        'slug' => 'required|max:10',
        'jenis' => 'required|not_in:0',
    ];

    public function fields($request, $icon)
    {
        $data_add = !empty($icon) ? ['icon' => $icon] : [];

        $data = [
            'nama' => $request->nama,
            'slug' => $request->slug,
            'jenis' => $request->jenis,
            'keterangan' => $request->keterangan ? $request->keterangan : '-',
        ];

        return array_merge($data_add, $data);
    }

    public function validated($mess, $request)
    {
        $vIcon = $request->has('id') ? (empty($request->old_img_icon) ? [
            'icon' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ] : [
            'icon' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]) : [
            'icon' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];

        $validator = \Validator::make($request->all(), array_merge($this->validate_message, $vIcon));

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
        return view('master-data.category.index', [
            'js' => ['s-home/master-data/category/js/category.js'],
            'attribute' => [
                'm_data' => 'true',
                'menu_category' => 'active menu-open',
                'title_bc' => 'Master Data - Kategori',
                'desc_bc' => 'Digunakan untuk media menampilkan, menambah, mengubah dan menghapus kategori',
            ]
        ]);
    }

    public function store(Request $request)
    {
        $mess = null;
        $filename_icon = null;

        $this->validated($mess, $request);

        if ($request->hasFile('icons') == 1) {
            $extension = $request->file('icons')->getClientOriginalExtension();
            if (!empty($request->id)) {
                $icon = DB::table($this->table)->where('id', $request->id)->first();
                if (!empty($icon) && !empty($icon->icon)) {
                    File::delete(storage_path($this->dir_icon) . $icon->icon);
                }
            }
            $filename_icon = uniqid() . '_' . time() . '.' . $extension;
            $request->file('icons')->move(storage_path($this->dir_icon), $filename_icon);
        } else {
            if (!empty($request->id)) {
                if (empty($request->old_img_icon) && $request->old_img_icon == '') {
                    $icon = DB::table($this->table)->where('id', $request->id)->first();
                    if (!empty($icon) && !empty($icon->icon)) {
                        File::delete(storage_path($this->dir_icon) . $icon->icon);
                    }
                } else {
                    $filename_icon = $request->old_img_icon;
                }
            }
        }

        if (empty($request->id)) {
            $tambah = DB::table($this->table)->insert($this->fields($request, $filename_icon));
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
                $affected = DB::table($this->table)->where('id', $request->id)->update($this->fields($request, $filename_icon));
                $mess['msg'] = 'Data sukses disimpan' . ($affected == 0 ? ", namun tidak ada perubahan" : " dan diubah");
                $mess['cd'] = 200;
            } catch (Exception $ex) {
                $mess['msg'] = 'Data gagal disimpan' . $ex;
                $mess['cd'] = 500;
            }
        }
        echo json_encode($mess);
    }

    public function create()
    {
        $data = [
            'js' => array('js' => 's-home/master-data/category/js/category.js'),
            'action' => route('categories.store'),
        ];
        return view('master-data.category.content.form.form', $data);
    }

    public function update($id)
    {
        if (!empty($id)) {
            $dataE = DB::table($this->table)->where('id', $id)->get()->last();
            if (!$dataE) {
                return abort(404);
            }
            $data = [
                'action' => route('categories.store'),
                'js' => array('js' => 's-home/master-data/category/js/category.js'),
                'dataE' => $dataE,
            ];
            return view('master-data.category.content.form.form', $data);
        }
    }

    public function _data()
    {
        return view('master-data.category.content.data.table');
    }

    public function _json()
    {
        if (request()->ajax()) {
            return datatables()->of(DB::table($this->table)
                ->select(
                    DB::raw("IF(jenis != '1', 'Produk', 'Layanan')  AS jenis"),
                    DB::raw("(SELECT count(*) from layanan where jenis = 1 AND kategori_id = " . $this->table . ".id)  AS use_serv"),
                    DB::raw("(SELECT count(*) from produk where jenis = 2 AND kategori_id = " . $this->table . ".id)  AS use_prod"),
                    $this->table . '.id',
                    $this->table . '.nama',
                    $this->table . '.keterangan'
                )
                ->orderBy('jenis', 'ASC')
                ->orderBy($this->table . '.id', 'DESC')
                ->get())
                ->addColumn('action', 'master-data.category.content.data.action_button')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function destroy($id)
    {
        $mess = null;
        $data = DB::table($this->table)->where('id', $id);

        $icon = $data->first()->icon;
        if (!empty($icon)) {
            File::delete(storage_path($this->dir_icon) . $icon);
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
