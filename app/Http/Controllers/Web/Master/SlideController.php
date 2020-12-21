<?php

namespace App\Http\Controllers\Web\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_encode;

class SlideController extends Controller
{

    protected $table = 'slider';
    private $dir = 'app/public/master-data/slide/uploads/';

    private $validate_message = [
        'nama' => 'required',
        'gambar' => 'image|mimes:jpeg,png,jpg|max:2048',
    ];

    public function fields($request, $gambar)
    {
        $data_add = !empty($gambar) ? ['gambar' => $gambar] : ['gambar' => null];
        $data = [
            'nama' => $request->nama,
            'keterangan' => $request->keterangan ? $request->keterangan : '-',
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
            $mess['msg'] = 'Ada beberapa masalah dengan inputan Anda!' . $d_error;
            $mess['cd'] = 500;
            echo json_encode($mess);
            exit;
        }
    }

    public function index()
    {
        return view('master-data.slide.index', [
            'js' => ['s-home/master-data/slide/js/slide.js'],
            'attribute' => [
                'm_data' => 'true',
                'menu_slide' => 'active menu-open',
                'title_bc' => 'Master Data - Slide',
                'desc_bc' => 'Digunakan untuk media menampilkan, menambah, mengubah dan menghapus slide',
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

    public function create()
    {
        return view('master-data.slide.content.form.form', [
            'action' => route('slides.store'),
            'js' => array('js' => 's-home/master-data/slide/js/slide.js'),
        ]);
    }

    public function update($id = false)
    {
        if (!empty($id)) {
            $dataE = DB::table($this->table)->where('id', $id)->get()->last();
            if (!$dataE) {
                return abort(404);
            }

            return view('master-data.slide.content.form.form', [
                'action' => route('slides.store'),
                'js' => array('js' => 's-home/master-data/slide/js/slide.js'),
                'dataE' => $dataE,
            ]);
        }
    }

    public function _data()
    {
        return view('master-data.slide.content.data.table');
    }

    public function _json()
    {
        if (request()->ajax()) {
            return datatables()->of(DB::table($this->table)
                ->select(
                    'id',
                    'gambar',
                    'nama',
                    'keterangan'
                )->get())
                ->addColumn('action', 'master-data.slide.content.data.action_button')
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
