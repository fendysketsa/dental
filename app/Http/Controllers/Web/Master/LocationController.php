<?php

namespace App\Http\Controllers\Web\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_encode;

class LocationController extends Controller
{
    protected $table = 'lokasi';
    protected $table_branch = 'cabang';

    private $validate_message = [
        'cabang' => 'required|not_in:0',
        'nama' => 'required',
    ];

    public function fields($request)
    {
        return [
            'cabang_id' => $request->cabang,
            'nama' => $request->nama,
        ];
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
        return view('master-data.location.index', [
            'js' => ['s-home/master-data/location/js/location.js'],
            'attribute' => [
                'm_data' => 'true',
                'menu_location' => 'active menu-open',
                'title_bc' => 'Master Data - Lokasi',
                'desc_bc' => 'Digunakan untuk media menampilkan, menambah, mengubah dan menghapus lokasi',
            ]
        ]);
    }

    public function store(Request $request)
    {
        $mess = null;
        $this->validated($mess, $request);
        if (empty($request->id)) {
            $tambah = DB::table($this->table)->insert($this->fields($request));
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
                $affected = DB::table($this->table)->where('id', $request->id)->update($this->fields($request));
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
        if(empty($request)) {
            return abort(404);
        }

        $data = DB::table($request->table)->get();
        echo json_encode($data);
    }

    public function create()
    {
        $data = [
            'js' => array('js' => 's-home/master-data/location/js/location.js'),
            'action' => route('locations.store'),
            'dataE' => null,
        ];
        return view('master-data.location.content.form.form', $data);
    }

    public function update($id)
    {
        if (!empty($id)) {
            $dataE = DB::table($this->table)->where('id', $id)->get()->last();
            if (!$dataE) {
                return abort(404);
            }
            $data = [
                'action' => route('locations.store'),
                'js' => array('js' => 's-home/master-data/location/js/location.js'),
                'dataE' => $dataE,
            ];
            return view('master-data.location.content.form.form', $data);
        }
    }

    public function _data()
    {
        return view('master-data.location.content.data.table');
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
                    $this->table . '.id',
                    $this->table . '.nama',
                    $this->table_branch . '.nama as cabang'
                )
                ->get())
                ->addColumn('action', 'master-data.location.content.data.action_button')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function destroy($id)
    {
        $mess = null;
        $hapus = DB::table($this->table)->where('id', $id)->delete();
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
