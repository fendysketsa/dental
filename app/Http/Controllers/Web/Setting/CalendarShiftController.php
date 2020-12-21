<?php

namespace App\Http\Controllers\Web\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CalendarShiftController extends Controller
{
    protected $table = 'kalendar_shift';
    protected $table_shift = 'shift';
    protected $table_pegawai = 'pegawai';
    protected $table_cabang = 'cabang';
    protected $table_pegawai_cabang = 'pegawai_cabang_detail';

    private $validate_message = [
        'tanggal' => 'required|date',
        'shift' => 'required',
        'pegawai' => 'required',
    ];

    public function fields($request)
    {
        $addOn = $request->has('pegawai') ? [
            'pegawai_id' => $request->pegawai,
        ] : [];

        $dataMain = [
            'tanggal' => date('Y-m-d', strtotime($request->tanggal)),
            'shift_id' => $request->shift,
            'cabang_id' => $request->cabang,
            'ijin' => $request->has('keterangan') ? 1 : 0,
            'keterangan' => $request->has('keterangan') ? $request->keterangan : '',
        ];

        return array_merge($addOn, $dataMain);
    }

    public function fields_date($request)
    {
        return [
            'tanggal' => date('Y-m-d', strtotime($request->date)),
        ];
    }

    public function validated($mess, $request)
    {
        $keterangan = $request->has('keterangan') ? [
            'keterangan' => 'required|string|min:30'
        ] : [];

        $validator = \Validator::make($request->all(), array_merge($this->validate_message, $keterangan));
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
        return view('setting.calshift.index', [
            'action' => route('calendars.store'),
            'css' => [
                's-home/bower_components/fullcalendar/dist/fullcalendar.min.css',
            ],
            'js' => [
                's-home/bower_components/moment/moment.js',
                's-home/bower_components/fullcalendar/dist/fullcalendar.min.js',
                's-home/bower_components/fullcalendar/dist/locale-all.js',
                's-home/master-data/calshift/js/calshift.js'
            ],
            'attribute' => [
                'm_other' => 'true',
                'menu_calshift' => 'active menu-open',
                'title_bc' => 'Setting - Kalendar Shift',
                'desc_bc' => 'Digunakan untuk media menampilkan, menambah, mengubah dan menghapus shift pegawai',
            ]
        ]);
    }

    public function _option(Request $request)
    {
        if (empty($request)) {
            return abort(404);
        }
        if (!in_array($request->table, array($this->table_shift, $this->table_pegawai, $this->table_cabang))) {
            return abort(404);
        }

        $table_ = DB::table($request->table);
        if ($request->table == 'pegawai') {
            $data = $table_
                ->where('status', 1)
                ->where('role', 3)
                ->leftJoin(
                    'pegawai_cabang_detail',
                    'pegawai_cabang_detail.pegawai_id',
                    '=',
                    'pegawai.id'
                );
            if (!empty($request->cabang_id)) {
                $table_->where('pegawai.cabang_id', $request->cabang_id);
                $table_->orWhere('pegawai_cabang_detail.cabang_id', $request->cabang_id);
            }
            $table_->select('pegawai.*');
            $data = $table_->groupBy('pegawai.id')->orderBy('pegawai.nama', "ASC")->get();
        } else {
            $data = $table_->get();
        }
        echo json_encode($data);
    }

    public function cekShift($request)
    {
        $cek = DB::table($this->table)
            ->where('tanggal', date('Y-m-d', strtotime($request->tanggal)))
            ->where('shift_id', $request->shift)
            ->where('pegawai_id', $request->pegawai);

        if (!empty($request->idKal)) {
            $cek->whereNotIn('id', [$request->idKal]);
        }

        $data = $cek->get();

        return count($data);
    }

    public function store(Request $request)
    {
        $mess = null;
        $this->validated($mess, $request);

        if ($this->cekShift($request) > 0) {
            $mess['msg'] = 'Data gagal ditambahkan!<li>terjadi duplikasi shift dan pegawai ditanggal yang sama!</li>';
            $mess['cd'] = 500;
            echo json_encode($mess);
            exit;
        }

        if (empty($request->idKal)) {
            $tambah = DB::table($this->table)->insertGetId($this->fields($request));
            if ($tambah) {
                $mess['msg'] = 'Data sukses ditambahkan';
                $mess['cd'] = 200;
                $mess['id'] = $tambah;
            } else {
                $mess['msg'] = 'Data gagal ditambahkan';
                $mess['cd'] = 500;
            }
        }

        if (!empty($request->idKal)) {
            try {
                $affected = DB::table($this->table)->where('id', $request->idKal)->update($this->fields($request));
                $mess['msg'] = 'Data sukses disimpan' . ($affected == 0 ? ", namun tidak ada perubahan" : " dan diubah");
                $mess['cd'] = 200;
            } catch (Exception $ex) {
                $mess['msg'] = 'Data gagal disimpan' . $ex;
                $mess['cd'] = 500;
            }
        }
        echo json_encode($mess);
    }

    public function update(Request $request)
    {
        $mess = null;
        try {
            $affected = DB::table($this->table)->where('id', $request->id)->update($this->fields_date($request));
            $mess['msg'] = 'Data sukses disimpan' . ($affected == 0 ? ", namun tidak ada perubahan" : " dan diubah");
            $mess['cd'] = 200;
        } catch (Exception $ex) {
            $mess['msg'] = 'Data gagal disimpan' . $ex;
            $mess['cd'] = 500;
        }
        echo json_encode($mess);
    }

    public function _json(Request $request)
    {
        $json = array();
        $data = DB::table($this->table)
            ->leftJoin(
                $this->table_shift,
                $this->table_shift . '.id',
                '=',
                $this->table . '.shift_id'
            )
            ->leftJoin(
                $this->table_pegawai,
                $this->table_pegawai . '.id',
                '=',
                $this->table . '.pegawai_id'
            )
            ->leftJoin(
                $this->table_pegawai_cabang,
                $this->table_pegawai_cabang . '.pegawai_id',
                '=',
                $this->table_pegawai . '.id'
            )
            ->select(
                DB::raw('IF(pegawai.cabang_id = ' . ($request->has('cabang') ? $request->cabang : "0") . ', "main", "") as main'),
                DB::raw('IF(DATE(' . $this->table . '.tanggal) < "' . DATE('Y-m-d') . '", "past", "") as past_day'),
                $this->table . '.*',
                $this->table_shift . '.nama as shift',
                $this->table_shift . '.label as labeling',
                $this->table_pegawai . '.nama as nama_user'
            )
            ->where(
                $this->table . '.cabang_id',
                ($request->has('cabang') ? "=" : "!="),
                ($request->has('cabang') ? $request->cabang : "")
            )
            ->whereBetween($this->table . '.tanggal', array($request->start, $request->end))
            // ->orWhere(
            //     $this->table_pegawai_cabang . '.cabang_id',
            //     ($request->has('cabang') ? "=" : ">"),
            //     ($request->has('cabang') ? $request->cabang : "0")
            // )
            ->groupBy(
                $this->table . '.tanggal',
                $this->table . '.cabang_id',
                $this->table . '.shift_id',
                $this->table . '.pegawai_id'
            )
            ->orderBy($this->table_shift . '.nama', 'ASC')
            ->get();

        foreach ($data as $r) {
            $json[] = array(
                'id' => $r->id,
                'title' => ($request->has('cabang') ? (empty($r->main) ? "+ " : "") : "") . $r->shift . ' - ' .  $r->nama_user . ($r->ijin > 0 ? '' : (!empty($r->past_day) ?
                    '' : ' <em class="fa fa-times text-white pull-right fnt-15"></em>')),
                'shift' => $r->shift_id,
                'pegawai' => $r->pegawai_id,
                'start' => $r->tanggal,
                'color' => $r->ijin > 0 ? '#dddddd' : $r->labeling,
                'noEdit' => ($request->has('cabang') ? (empty($r->main) ? "noedit" : "") : ""),
                'ijin' => $r->ijin,
                'dayPast' => $r->past_day
            );
        }
        return response()->json(
            $json
        );
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
