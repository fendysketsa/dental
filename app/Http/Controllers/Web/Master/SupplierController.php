<?php

namespace App\Http\Controllers\Web\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_encode;

class SupplierController extends Controller
{
    protected $table = 'supplier';
    protected $table_branch = 'cabang';

    private $validate_message = [
        'cabang' => 'required|not_in:0',
        'nama' => 'required',
        'alamat' => 'required',
        'telepon' => 'required',
    ];

    public function fields($request)
    {
        return [
            'cabang_id' => $request->cabang,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'email' => $request->email,
            'keterangan' => $request->keterangan,
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
        return view('master-data.supplier.index', [
            'js' => ['s-home/master-data/supplier/js/supplier.js'],
            'attribute' => [
                'm_data' => 'true',
                'menu_supplier' => 'active menu-open',
                'title_bc' => 'Master Data - Supplier',
                'desc_bc' => 'Digunakan untuk media menampilkan, menambah, mengubah dan menghapus supplier',
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
        if (empty($request)) {
            return abort(404);
        }
        $data = DB::table($this->table_branch)->get();
        echo json_encode($data);
    }

    public function create()
    {
        $data = [
            'action' => route('suppliers.store'),
            'js' => array('js' => 's-home/master-data/supplier/js/supplier.js'),
        ];
        return view('master-data.supplier.content.form.form', $data);
    }

    public function update($id = false)
    {
        $dataE = null;
        if (!empty($id)) {
            $dataE = DB::table($this->table)->where('id', $id)->first();
            if (!$dataE) {
                return abort(404);
            }
            $data = [
                'action' => route('suppliers.store'),
                'js' => array('js' => 's-home/master-data/supplier/js/supplier.js'),
                'dataE' => $dataE,
            ];
            return view('master-data.supplier.content.form.form', $data);
        }
    }

    public function _data()
    {
        return view('master-data.supplier.content.data.table');
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
                    $this->table . '.email',
                    $this->table . '.telepon',
                    $this->table_branch . '.nama as cabang'
                )
                ->get())
                ->addColumn('action', 'master-data.supplier.content.data.action_button')
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
