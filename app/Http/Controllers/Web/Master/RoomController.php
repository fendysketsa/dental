<?php

namespace App\Http\Controllers\Web\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_encode;
use Illuminate\Support\Facades\File;

class RoomController extends Controller
{
    protected $table = 'room';
    protected $table_branch = 'cabang';

    private $dir = 'app/public/master-data/room/uploads/';
    private $dirLoad = 'storage/master-data/room/uploads/';

    private $validate_message = [
        'cabang' => 'required|not_in:0',
        'nama' => 'required',
        // 'harga' => 'required',
    ];

    public function fields($request, $images)
    {
        $data_add = !empty($images) ? ['images' => $images] : ['images' => null];
        $data = [
            'branch_id' => $request->cabang,
            'name' => $request->nama,
            'price' => unRupiahFormat($request->harga),
            'description' => $request->keterangan ? $request->keterangan : '',
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
        return view('master-data.room.index', [
            'js' => ['s-home/master-data/room/js/room.js'],
            'attribute' => [
                'm_data' => 'true',
                'menu_room' => 'active menu-open',
                'title_bc' => 'Master Data - Ruangan',
                'desc_bc' => 'Digunakan untuk media menampilkan, menambah, mengubah dan menghapus ruangan',
            ]
        ]);
    }

    public function store(Request $request)
    {
        $mess = null;
        $images = null;

        $save_image = null;

        $this->validated($mess, $request);
        if (empty($request->id)) {

            if ($request->hasFile('images')) {
                $images = array();

                foreach ($request->file('images') as $gambar) {
                    $name = $gambar->getClientOriginalName();

                    $filename = uniqid() . '_' . time() . '.' . $name;
                    $gambar->move(storage_path($this->dir), $filename);

                    array_push($images, $filename);
                }
            }

            $save_image = !empty($images) ? json_encode($images, true) : $images;

            $tambah = DB::table($this->table)->insert($this->fields($request, $save_image));

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
                $elmTable = DB::table($this->table)->where('id', $request->id);

                $images = array();

                if ($request->has('old_images')) {
                    $old = array();
                    foreach ($request->old_images as $gmb) {
                        array_push($old, $gmb);
                    }

                    if ($elmTable->count() > 0) {
                        if (!empty($elmTable->first()->images)) {

                            foreach (json_decode($elmTable->first()->images, true) as $idx => $row) {
                                if (!in_array($idx, $old)) {
                                    File::delete(storage_path($this->dir) . $row);
                                }
                                if (in_array($idx, $old)) {
                                    array_push($images, $row);
                                }
                            }
                        }
                    }
                }

                if (!$request->has('old_images')) {
                    if ($elmTable->count() > 0) {
                        if (!empty($elmTable->first()->images)) {
                            foreach (json_decode($elmTable->first()->images, true) as $idx => $row) {
                                File::delete(storage_path($this->dir) . $row);
                            }
                        }
                    }
                }

                if ($request->hasFile('images')) {

                    foreach ($request->file('images') as $gambar) {
                        $name = $gambar->getClientOriginalName();

                        $filename = uniqid() . '_' . time() . '.' . $name;
                        $gambar->move(storage_path($this->dir), $filename);

                        array_push($images, $filename);
                    }
                }

                $save_image = !empty($images) ? json_encode($images, true) : $images;

                $affected = $elmTable->update($this->fields($request, $save_image));

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

        $data = DB::table($request->table)->get();
        echo json_encode($data);
    }

    public function create()
    {
        $data = [
            'js' => array('js' => 's-home/master-data/room/js/room.js'),
            'action' => route('rooms.store'),
            'dataE' => null,
        ];
        return view('master-data.room.content.form.form', $data);
    }

    public function update($id)
    {
        if (!empty($id)) {
            $dataE = DB::table($this->table)->where('id', $id)->get()->last();
            if (!$dataE) {
                return abort(404);
            }

            $image = array();
            if (!empty($dataE->images)) {
                foreach (json_decode($dataE->images, true) as $idx => $img) {
                    array_push($image, array(
                        'id' => (int) $idx,
                        'src' => asset($this->dirLoad . $img),
                    ));
                }
            }

            $data = [
                'action' => route('rooms.store'),
                'js' => array('js' => 's-home/master-data/room/js/room.js'),
                'dataE' => $dataE,
                'dataEImage' => json_encode($image, JSON_UNESCAPED_SLASHES),
            ];
            return view('master-data.room.content.form.form', $data);
        }
    }

    public function _data()
    {
        return view('master-data.room.content.data.table');
    }

    public function _json()
    {
        if (request()->ajax()) {
            return datatables()->of(DB::table($this->table)
                ->leftJoin(
                    $this->table_branch,
                    $this->table . '.branch_id',
                    '=',
                    $this->table_branch . '.id'
                )
                ->select(
                    $this->table . '.id',
                    $this->table . '.name',
                    $this->table . '.price',
                    $this->table_branch . '.nama as cabang'
                )
                ->get())
                ->addColumn('action', 'master-data.room.content.data.action_button')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function destroy($id)
    {
        $mess = null;
        $data = DB::table($this->table)->where('id', $id);

        $image = $data->first();

        if (!empty($image->images)) {
            foreach (json_decode($image->images, true) as $img) {
                File::delete(storage_path($this->dir) . $img);
            }
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
