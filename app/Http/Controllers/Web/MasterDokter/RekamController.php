<?php

namespace App\Http\Controllers\Web\MasterDokter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_encode;

class RekamController extends Controller
{
    protected $table = 'rekam_medik';

    private $validate_message = [
        'nama' => 'required',
        'pilihan' => 'required',
    ];

    private $validate_message_tambahan = [
        'placeholder' => 'required',
    ];

    public function fields($request)
    {
        return [
            'nama' => $request->nama,
            'option' => $request->pilihan,
            'more_input' => $request->tambahan_input ? $request->tambahan_input : NULL,
            'more_input_placeholder' => $request->placeholder ? $request->placeholder : NULL,
            'more_input_label' => $request->label ? $request->label : NULL,
            'set_input' => $request->set_input,
            'status' => $request->status,
        ];
    }

    public function validated($mess, $request)
    {
        $message = empty($request->tambahan_input) ? $this->validate_message : array_merge($this->validate_message, $this->validate_message_tambahan);

        $validator = \Validator::make($request->all(), $message);
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
        return view('master-dokter.rekam-medik.index', [
            'js' => ['s-home/master-dokter/rekam-medik/js/rekam-medik.js'],
            'attribute' => [
                'm_dokter_data' => 'true',
                'menu_rekammedik' => 'active menu-open',
                'title_bc' => 'Master Dokter - Rekam Medik',
                'desc_bc' => 'Digunakan untuk media menampilkan, menambah, mengubah dan menghapus data master rekam medik',
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
                $elmTable = DB::table($this->table)->where('id', $request->id);
                $affected = $elmTable->update($this->fields($request));

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
            'js' => array('js' => 's-home/master-dokter/rekam-medik/js/rekam-medik.js'),
            'action' => route('rekams.store'),
            'dataE' => null,
        ];
        return view('master-dokter.rekam-medik.content.form.form', $data);
    }

    public function update($id)
    {
        if (!empty($id)) {
            $dataE = DB::table($this->table)->where('id', $id)->get()->last();
            if (!$dataE) {
                return abort(404);
            }

            $data = [
                'action' => route('rekams.store'),
                'js' => array('js' => 's-home/master-dokter/rekam-medik/js/rekam-medik.js'),
                'dataE' => $dataE,
            ];
            return view('master-dokter.rekam-medik.content.form.form', $data);
        }
    }

    public function _data()
    {
        return view('master-dokter.rekam-medik.content.data.table');
    }

    public function _json()
    {
        if (request()->ajax()) {
            return datatables()->of(DB::table($this->table)
                ->select(
                    $this->table . '.id',
                    $this->table . '.nama',
                    $this->table . '.option',
                    $this->table . '.set_input',
                    $this->table . '.status'
                )
                ->get())
                ->addColumn('action', 'master-dokter.rekam-medik.content.data.action_button')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function destroy($id)
    {
        $mess = null;
        $data = DB::table($this->table)->where('id', $id);

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
