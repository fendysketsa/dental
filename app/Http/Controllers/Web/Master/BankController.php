<?php

namespace App\Http\Controllers\Web\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_encode;

class BankController extends Controller
{
    protected $table = 'bank';
    private $validate_message = [
        'nama' => 'required',
        'kode' => 'required',
        'pemilik' => 'required',
    ];

    public function fields($request)
    {
        return [
            'nama' => $request->nama,
            'pemilik' => $request->pemilik,
            'kode' => $request->kode,
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
        return view('master-data.bank.index', [
            'js' => ['s-home/master-data/bank/js/bank.js'],
            'attribute' => [
                'm_data' => 'true',
                'menu_bank' => 'active menu-open',
                'title_bc' => 'Master Data - Bank',
                'desc_bc' => 'Digunakan untuk media menampilkan, menambah, mengubah dan menghapus bank',
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
            'js' => array('js' => 's-home/master-data/bank/js/bank.js'),
            'action' => route('banks.store'),
        ];
        return view('master-data.bank.content.form.form', $data);
    }

    public function update($id)
    {
        if (!empty($id)) {
            $dataE = DB::table($this->table)->where('id', $id)->get()->last();
            if (!$dataE) {
                return abort(404);
            }
            $data = [
                'action' => route('banks.store'),
                'js' => array('js' => 's-home/master-data/bank/js/bank.js'),
                'dataE' => $dataE,
            ];
            return view('master-data.bank.content.form.form', $data);
        }
    }

    public function _data()
    {
        return view('master-data.bank.content.data.table');
    }

    public function _json()
    {
        if (request()->ajax()) {
            return datatables()->of(DB::table($this->table)
                ->select(
                    'id',
                    'kode',
                    'nama',
                    'pemilik'
                )
                ->get())
                ->addColumn('action', 'master-data.bank.content.data.action_button')
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
