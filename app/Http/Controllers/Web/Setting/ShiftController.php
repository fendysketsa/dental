<?php

namespace App\Http\Controllers\Web\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_encode;

class ShiftController extends Controller
{
    protected $table = 'shift';
    private $validate_message = [
        'nama' => 'required',
        'jam_awal' => 'required',
    ];

    public function fields($request)
    {
        return [
            'nama' => $request->nama,
            'jam_awal' => $request->jam_awal,
            'jam_akhir' => $request->jam_akhir,
            'label' => $request->label,
        ];
    }

    public function _addCustomeValidate($req)
    {
        $data_query = "SELECT * FROM " . $this->table;
        if (empty($req->id)) {
            $data_query .= " WHERE nama = '" . $req->nama . "'";
            $data_query .= " AND ";
            $data_query .= " (jam_awal >= '" . $req->jam_awal . "'";
            $data_query .= " OR ";
            $data_query .= " jam_akhir >= '" . $req->jam_akhir . "')";
            $data_query .= " AND ";
            $data_query .= " (jam_awal <= '" . $req->jam_awal . "'";
            $data_query .= " OR ";
            $data_query .= " jam_akhir <= '" . $req->jam_akhir . "')";
        }
        if (!empty($req->id)) {
            $data_query .= " WHERE nama = '" . $req->nama . "'";
            $data_query .= " AND ";
            $data_query .= " (jam_awal >= '" . $req->jam_akhir . "'";
            $data_query .= " OR ";
            $data_query .= " jam_akhir >= '" . $req->jam_awal . "')";
            $data_query .= " AND ";
            $data_query .= " (jam_awal <= '" . $req->jam_akhir . "'";
            $data_query .= " OR ";
            $data_query .= " jam_akhir <= '" . $req->jam_akhir . "')";
            $data_query .= " AND id != '" . $req->id . "'";
        }

        $custVal = DB::select($data_query);
        if (!empty($custVal)) {
            $mess['msg'] = 'Data gagal disimpan, rentang waktu telah terpakai!';
            $mess['cd'] = 500;
            echo json_encode($mess);
            exit;
        }
    }

    public function validated($mess, $request)
    {
        $attributeNames = array(
            'jam_akhir' => 'jam akhir'
        );

        $message = array(
            'jam_akhir' => 'required|after:jam_awal'
        );

        $customMessages = [
            'jam_akhir.after' => 'Isian :attribute harus setelah waktu jam awal'
        ];

        $validator = \Validator::make($request->all(), array_merge($this->validate_message, $message),  $customMessages);
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
        return view('setting.shift.index', [
            'js' => ['s-home/master-data/shift/js/shift.js'],
            'attribute' => [
                'm_other' => 'true',
                'menu_shift' => 'active menu-open',
                'title_bc' => 'Setting - Shift',
                'desc_bc' => 'Digunakan untuk media menampilkan, menambah, mengubah dan menghapus shift',
            ]
        ]);
    }

    public function store(Request $request)
    {
        $mess = null;
        $this->validated($mess, $request);
        $this->_addCustomeValidate($request);

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
        return view('setting.shift.content.form.form', [
            'js' => array('js' => 's-home/master-data/shift/js/shift.js'),
            'action' => route('shifts.store'),
        ]);
    }

    public function update($id)
    {
        if (!empty($id)) {
            $dataE = DB::table($this->table)->where('id', $id)->get()->last();
            if (!$dataE) {
                return abort(404);
            }

            return view('setting.shift.content.form.form', [
                'action' => route('shifts.store'),
                'js' => array('js' => 's-home/master-data/shift/js/shift.js'),
                'dataE' => $dataE,
            ]);
        }
    }

    public function _data()
    {
        return view('setting.shift.content.data.table');
    }

    public function _json()
    {
        if (request()->ajax()) {
            return datatables()->of(DB::table($this->table)
                ->select(
                    'id',
                    'nama',
                    DB::raw("CONCAT(jam_awal,' - ',jam_akhir) AS waktu"),
                    DB::raw("(SELECT count(*) from kalendar_shift where shift_id is not null AND shift_id = " . $this->table . ".id) as count_kalshift")
                )
                ->get())
                ->addColumn('action', 'setting.shift.content.data/action_button')
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
