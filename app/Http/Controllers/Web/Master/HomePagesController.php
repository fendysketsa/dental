<?php

namespace App\Http\Controllers\Web\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_encode;

class HomePagesController extends Controller
{
    protected $table = 'home_page';

    private $dir = 'app/public/master-data/home-page/uploads/';
    private $dir_icon = 'app/public/master-data/home-page/uploads/icon/';

    private $validate_message = [
        'judul' => 'required',
        'deskripsi' => 'required',
    ];

    public function fields($request, $gambar, $icon)
    {
        $data_add = !empty($gambar) ? ['gambar' => $gambar] : [];

        $data_add2 = !empty($icon) ? (!empty($data_add) ? array_merge($data_add, ['icon' => $icon]) : ['icon' => $icon]) : [];

        $data = [
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi ? $request->deskripsi : '-',
            'video' => $request->video
        ];
        return array_merge($data_add2, $data);
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
        return view('master-data.homepage.index', [
            'js' => [
                's-home/master-data/homepage/js/homepage.js',
                's-home/bower_components/ckeditor/ckeditor.js'
            ],
            'attribute' => [
                'm_hp_data' => 'true',
                'menu_homepage' => 'active menu-open',
                'title_bc' => 'Master Data - Home Page',
                'desc_bc' => 'Digunakan untuk media menampilkan, menambah, mengubah dan menghapus content home page',
            ]
        ]);
    }

    public function store(Request $request)
    {
        $mess = null;
        $filename = null;
        $filename_icon = null;

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
            $tambah = DB::table($this->table)->insert($this->fields($request, $filename, $filename_icon));
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
                $affected = DB::table($this->table)->where('id', $request->id)->update($this->fields($request, $filename, $filename_icon));
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
        return view('master-data.homepage.content.form.form', [
            'action' => route('homepages.store'),
            'js' => [
                's-home/master-data/homepage/js/homepage.js',
                's-home/bower_components/ckeditor/ckeditor.js'
            ],
        ]);
    }

    public function update($id = false)
    {
        if (!empty($id)) {
            $dataE = DB::table($this->table)->where('id', $id)->get()->last();
            if (!$dataE) {
                return abort(404);
            }

            return view('master-data.homepage.content.form.form', [
                'action' => route('homepages.store'),
                'js' => [
                    's-home/master-data/homepage/js/homepage.js',
                    's-home/bower_components/ckeditor/ckeditor.js'
                ],
                'dataE' => $dataE,
            ]);
        }
    }

    public function _data()
    {
        return view('master-data.homepage.content.data.table');
    }

    public function _json()
    {
        if (request()->ajax()) {
            return datatables()->of(DB::table($this->table)
                ->select(
                    'id',
                    'video',
                    'gambar',
                    'judul'
                )->get())
                ->addColumn('action', 'master-data.homepage.content.data.action_button')
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
