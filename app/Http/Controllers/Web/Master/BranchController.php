<?php

namespace App\Http\Controllers\Web\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_encode;

class BranchController extends Controller
{

    protected $table = 'cabang';
    private $validate_message = [
        'nama' => 'required',
        'kode' => 'required:max:4',
        'telepon' => 'required',
    ];

    public function fields($request)
    {
        return [
            'nama' => $request->nama,
            'kode' => $request->kode,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat ? $request->alamat : '-',
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
        return view('master-data.branch.index', [
            'js' => ['s-home/master-data/branch/js/branch.js'],
            'attribute' => [
                'm_data' => 'true',
                'menu_branch' => 'active menu-open',
                'title_bc' => 'Master Data - Cabang',
                'desc_bc' => 'Digunakan untuk media menampilkan, menambah, mengubah dan menghapus cabang',
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

    public function create()
    {
        $data = [
            'js' => array('js' => 's-home/master-data/branch/js/branch.js'),
            'action' => route('branchs.store'),
        ];
        return view('master-data.branch.content.form.form', $data);
    }

    public function _session($id, Request $request)
    {
        if (empty($id)) {
            return abort(404);
        }

        session(['cabang_session' => base64_decode($id)]);

        $mess['cd'] = 200;
        $mess['redirect'] = $request->get('load');

        echo json_encode($mess);
    }

    public function update($id)
    {
        if (!empty($id)) {
            $dataE = DB::table($this->table)->where('id', $id)->get()->last();
            if (!$dataE) {
                return abort(404);
            }
            $data = [
                'action' => route('branchs.store'),
                'js' => array('js' => 's-home/master-data/branch/js/branch.js'),
                'dataE' => $dataE,
            ];
            return view('master-data.branch.content.form.form', $data);
        }
    }

    public function _data()
    {
        return view('master-data.branch.content.data.table');
    }

    public function _json()
    {
        if (request()->ajax()) {
            return datatables()->of(DB::table($this->table)
                ->select(
                    DB::raw("(SELECT COUNT(*) FROM kalendar_shift WHERE cabang_id = " . $this->table . ".id) as in_kal_use"),
                    DB::raw("(SELECT COUNT(*) FROM layanan WHERE cabang_id = " . $this->table . ".id) as in_lay_use"),
                    DB::raw("(SELECT COUNT(*) FROM layanan_detail_cabang WHERE cabang_id = " . $this->table . ".id) as in_lay_det_use"),
                    'id',
                    'kode',
                    'nama',
                    'telepon',
                    'alamat'
                )
                ->get())
                ->addColumn('action', 'master-data.branch.content.data.action_button')
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
