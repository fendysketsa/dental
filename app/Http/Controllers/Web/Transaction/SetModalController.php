<?php

namespace App\Http\Controllers\Web\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_encode;
use Auth;

class SetModalController extends Controller
{
    protected $table = 'set_modal';
    protected $table_shift = 'shift';
    protected $table_users = 'users';

    private $validate_message = [
        'nominal' => 'required|numeric'
    ];

    public function fields($request, $method = false)
    {
        $addData = $method == 'add' ? [
            'lokasi_id' => !empty(session('cabang_session')) ? session('cabang_session') : (!empty(session('cabang_id')) ? base64_decode(session('cabang_id')) : null),
            'shift_id' => $request->shift,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ] : [
            'updated_at' => date("Y-m-d H:i:s")
        ];

        $data = [
            'nominal' => unRupiahFormat($request->nominal),
            'pegawai_id' => Auth::user()->id,
        ];

        return array_merge($data, $addData);
    }

    public function dateAccept($shift)
    {
        $date = DB::table($this->table)
            ->where(
                DB::raw('DATE(created_at)'),
                '=',
                date('Y-m-d')
            )->where(
                'shift_id',
                '=',
                $shift
            );

        if (!empty(session('cabang_session'))) {
            $date->where($this->table . '.lokasi_id', '=', session('cabang_session'));
        }

        if (!empty(session('cabang_id'))) {
            $date->where($this->table . '.lokasi_id', base64_decode(session('cabang_id')));
        }

        $date_ = $date->get()->count();

        if ($date_ > 0) {
            $mess['msg'] = 'Oops, untuk shift hari ini sudah set modal!';
            $mess['cd'] = 500;
            echo json_encode($mess);
            exit;
        }
    }

    public function validated($mess, $request)
    {
        $mess = empty($request->id) ?
            array_merge([
                'shift' => 'required|not_in:0'
            ], $this->validate_message)
            : $this->validate_message;

        $validator = \Validator::make($request->all(), $mess);
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
        return view('setting.set.modal.index', [
            'js' => [
                's-home/setting/setModal/js/setModal.js'
            ],
            'attribute' => [
                'm_transaction' => 'true',
                'menu_setModal' => 'active menu-open',
                'title_bc' => 'Transaksi - Set Modal Per Shift',
                'desc_bc' => 'Digunakan untuk media menampilkan, menambah dan mengubah shift',
            ]
        ]);
    }

    public function store(Request $request)
    {
        $mess = null;
        $this->validated($mess, $request);
        if (empty($request->id)) {
            $this->dateAccept($request->shift);
            $tambah = DB::table($this->table)->insert($this->fields($request, 'add'));
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
                $affected = DB::table($this->table)->where('id', $request->id)->update($this->fields($request, 'edit'));
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
        return view('setting.set.modal.content.form.form', [
            'action' => route('set.modals.store'),
            'js' => [
                's-home/setting/setModal/js/setModal.js'
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
            return view('setting.set.modal.content.form.form', [
                'action' => route('set.modals.store'),
                'js' => [
                    's-home/setting/setModal/js/setModal.js'
                ],
                'dataE' => $dataE,
            ]);
        }
    }

    public function _data()
    {
        return view('setting.set.modal.content.data.table');
    }

    public function _json()
    {
        if (request()->ajax()) {

            $data_ = DB::table($this->table)
                ->leftJoin(
                    $this->table_shift,
                    $this->table . '.shift_id',
                    '=',
                    $this->table_shift . '.id'
                )
                ->leftJoin(
                    $this->table_users,
                    $this->table . '.pegawai_id',
                    '=',
                    $this->table_users . '.id'
                )
                ->select(
                    $this->table . '.id',
                    $this->table . '.created_at as tanggal',
                    $this->table_shift . '.jam_akhir as jam_shift',
                    $this->table . '.nominal',
                    $this->table_shift . '.nama as shift',
                    $this->table_users . '.name as operator'
                );

            if (!empty(session('cabang_session'))) {
                $data_->where($this->table . '.lokasi_id', '=', session('cabang_session'));
            }

            if (!empty(session('cabang_id'))) {
                $data_->where($this->table . '.lokasi_id', base64_decode(session('cabang_id')));
            }

            $data_
                ->orderByDesc($this->table . '.id')
                ->get();

            return datatables()->of($data_)
                ->addColumn('action', 'setting.set.modal.content.data.action_button')
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
