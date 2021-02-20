<?php

namespace App\Http\Controllers\Reservation;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_encode;
use Illuminate\Support\Facades\Input;
use App\Models\Transaction\TransaksiModel as Transaksi;
use App\Models\MemberModel;
use Symfony\Component\CssSelector\Node\SelectorNode;
use Validator as Validasi;

class ReservationController extends Controller
{
    protected $table = 'transaksi';
    protected $table_detail = 'transaksi_detail';

    protected $table_member = 'member';
    protected $table_layanan = 'layanan';
    protected $table_lokasi = 'lokasi';
    protected $table_paket = 'paket';
    protected $table_pegawai = 'pegawai';
    protected $table_user = 'users';

    private $validate_messageMember = [
        'nama' => 'required',
        // 'email' => 'required',
        'telepon' => 'required',
    ];

    public function fields($request, $last_id)
    {
        $wak_res = (!empty($request->tgl_reservasi) && !empty($request->jam_reservasi)) ?
            date('Y-m-d H:i:s', strtotime($request->tgl_reservasi . ' ' . $request->jam_reservasi)) : date(NOW());

        return [
            'member_id' => $last_id,
            'no_transaksi' => Transaksi::getAutoNoTransaksi(),
            'uniq_transaksi' => Transaksi::getCodeUniqTransaksi(15),
            'jumlah_orang' => $request->jumlah_orang,
            'lokasi_id' => !empty(session('cabang_id')) ? base64_decode(session('cabang_id')) : (!empty(session('cabang_session')) ? session('cabang_session') : $request->lokasi_reservasi),
            'waktu_reservasi' => $wak_res,
            'dp' => 0, //$request->dp,
            //'paket_id' => $request->paket,
            'agent' => 'Web Based',
            'room_id' => $request->room,
            'status_pembayaran' => 'pendaftaran',
            'status' => 2,
        ];
    }

    public function validated($mess, $request)
    {
        $attributeNames = array(
            'sno_member' => 'no member',
            'ino_member' => 'no member',
            'lokasi_reservasi' => 'lokasi reservasi',
        );

        $message = array(
            'sno_member' => 'required|not_in:0',
            'jumlah_orang' => 'required',
            'room' => 'required|not_in:0',
        );

        $message_inp = array(
            'ino_member' => 'required|unique:member,no_member',
            'jumlah_orang' => 'required',
            'room' => 'required|not_in:0',
        );

        $message_paket = array(
            'paket' => 'not_in:0',
        );

        $message_layanan = array(
            'layanan' => 'not_in:0',
        );

        $message_terapis = array(
            'ruangan' => 'not_in:0',
        );

        $message_reserv = array(
            'tgl_reservasi' => 'required',
            'jam_reservasi' => 'required',
            'lokasi_reservasi' => 'required|not_in:0',
        );

        $customMessages = [
            'sno_member.required' => 'Bidang pilihan :attribute wajib dipilih',
            'lokasi_reservasi.required' => 'Bidang pilihan :attribute wajib dipilih',
            'paket.required' => 'Bidang pilihan :attribute wajib dipilih',
            'layanan.required' => 'Bidang pilihan :attribute wajib dipilih',
            'ruangan.required' => 'Bidang pilihan :attribute wajib dipilih',
            'room.required' => 'Bidang pilihan ruangan wajib dipilih',
        ];

        $fail_form1 = $request->has('paket') ? array_merge($message_paket, $message_terapis) : array_merge($message_layanan, $message_terapis);
        $fail_form2 = $request->has('sno_member') ? array_merge($message, $fail_form1) : array_merge($message_inp, $this->validate_messageMember, $fail_form1);
        $fail_form3 = $request->has('tgl_reservasi') ? array_merge($message_reserv, $fail_form2) : $fail_form2;

        $validator = \Validator::make(
            $request->all(),
            $fail_form3,
            $customMessages
        );

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
        getBranch();
        return view('trans.registrasi.index', [
            'css' => [
                's-home/trans/registrasi/css/registrasi.css'
            ],
            'js' => [
                's-home/dist/js/sprintf.js',
                's-home/trans/registrasi/js/registrasi.js',
                // 'https://cdn.jsdelivr.net/npm/recta/dist/recta.js'
                's-home/dist/js/recta.js'
            ],
            'attribute' => [
                'm_registrasi' => 'true',
                'menu_registrasi' => 'active menu-open',
                'title_bc' => 'Pendaftaran',
                'desc_bc' => 'Digunakan untuk media mendaftarkan transaksi member, layanan dan paket yang ditentukan'
            ]
        ]);
    }

    public function create()
    {
        $form = Input::get('form');
        $load = Input::get('load');

        if (empty($form) && empty($load)) {
            return abort(403);
        }

        if (!empty($form)) {
            if (in_array($form, array('left_periksa', 'left_periksa_gigi'))) {
                return view('trans.registrasi.content.form.form_' . $form, [
                    'autoNom' => MemberModel::getAutoNoMember(),
                    'action' => route('registrations.store')
                ]);
            } else {
                return abort(403);
            }
        }

        if (!empty($load)) {
            if (in_array($load, array('gigi_permanen', 'gigi_susu'))) {
                return view('trans.registrasi.content.load.load_' . $load);
            } else {
                return abort(403);
            }
        }
    }

    public function _explore_rekam()
    {
        $data = DB::table('rekam_medik')->where('status', 1)->get();

        echo json_encode($data, true);
    }

    public function _opt(Request $request)
    {
        if (empty($request->table)) {
            return abort(403);
        }

        if (in_array($request->table, array('layanan', 'ruangan', 'paket'))) {
            $data = array();

            if ($request->table == 'layanan') {
                $datas = DB::table('kategori')
                    ->select('kategori.id', 'kategori.nama')
                    ->where('kategori.jenis', 1)
                    ->orderBy('kategori.nama', 'ASC')
                    ->get();

                foreach ($datas as $num => $layanan) {
                    $data[$num] = [
                        'id' => $layanan->id,
                        'nama' => $layanan->nama,
                        'data' => DB::table($this->table_layanan)
                            ->where('kategori_id', $layanan->id)
                            ->orderBy('nama', 'ASC')
                            ->get()
                    ];
                }
            }
            if ($request->table == 'ruangan') {
                // $t_cabg_kasir = !empty(session('cabang_id')) ? base64_decode(session('cabang_id')) : 2;
                // $tanggal = empty($request->get('tgl_res')) ? date('Y-m-d') : DATE('Y-m-d', strtotime($request->get('tgl_res')));
                // $jam = empty($request->get('jam_res')) ? date('H:i') : $request->get('jam_res');
                // $cabang = empty($request->get('loc_res')) ? $t_cabg_kasir : $request->get('loc_res');

                // $layanan = $request->get('layanan');

                // $data_ = DB::table('kualifikasi_terapis')
                //     ->leftJoin('layanan', 'layanan.id', '=', 'kualifikasi_terapis.layanan_id')
                //     ->leftJoin('pegawai', 'kualifikasi_terapis.pegawai_id', '=', 'pegawai.id')
                //     ->leftJoin(
                //         'kalendar_shift',
                //         'kalendar_shift.pegawai_id',
                //         '=',
                //         'pegawai.id'
                //     )
                //     ->leftJoin('shift', 'shift.id', '=', 'kalendar_shift.shift_id')
                //     ->where('kualifikasi_terapis.layanan_id', '=', $layanan)
                //     ->where('pegawai.role', '=', 3)
                //     ->where('kalendar_shift.ijin', '=', 0);

                // if (empty($request->get('loaded'))) {
                //     $data_->where('kalendar_shift.cabang_id', '=', $cabang)
                //         ->where('kalendar_shift.tanggal', '=', $tanggal)
                //         ->WhereTime('shift.jam_akhir', '>', $jam);
                // }

                // $data_->select(
                //     DB::raw("IF((SELECT COUNT(dtp.pegawai_id) from transaksi_detail dtp left join transaksi t ON t.id = dtp.transaksi_id where t.status_pembayaran != 'terbayar' AND t.status != 4 AND t.status != 1 AND dtp.pegawai_id = pegawai.id AND DATE(t.waktu_reservasi) = '" . $tanggal . "' AND TIME(t.waktu_reservasi) <= '" . $jam . "') > 0, 'true', 'false') as on_work"),
                //     DB::raw("IF(shift.jam_awal <= '$jam' AND shift.jam_akhir > '$jam', 'true', 'false') as available"),
                //     'pegawai.*'
                // )->groupBy('kalendar_shift.pegawai_id');

                // $data = $data_->get();

                $data = DB::table('room')->select('room.price as harga', 'room.name as nama', 'room.*')->get();
            }
            if ($request->table == 'paket') {
                $data = DB::table($this->table_paket)->get();
            }
            echo json_encode($data);
        }
    }

    public function _opts(Request $request)
    {
        if (empty($request->table)) {
            return abort(403);
        }
        $data = null;
        if (in_array($request->table, array('layanan'))) {
            if ($request->table == 'layanan') {
                $paket = $request->get('paket');
                $data = DB::table($this->table_paket)
                    ->leftJoin('paket_detail', 'paket_detail.paket_id', '=', 'paket.id')
                    ->leftJoin('layanan', 'layanan.id', '=', 'paket_detail.layanan_id')
                    ->where('paket.id', $paket)
                    ->select('layanan.*')
                    ->get();
            }
            echo json_encode($data);
        }
    }

    public function _optss(Request $request)
    {
        if (empty($request->table)) {
            return abort(403);
        }
        $data = null;
        if (in_array($request->table, array('pegawai'))) {
            if ($request->table == 'pegawai') {
                $t_cabg_kasir = !empty(session('cabang_id')) ? base64_decode(session('cabang_id')) : 2;
                $tanggal = empty($request->get('tgl_res')) ? date('Y-m-d') : DATE('Y-m-d', strtotime($request->get('tgl_res')));
                $jam = empty($request->get('jam_res')) ? date('H:i') : $request->get('jam_res');
                $cabang = empty($request->get('loc_res')) ? $t_cabg_kasir : $request->get('loc_res');

                $layanan = $request->get('layanan');
                $data = DB::table('kualifikasi_terapis')
                    ->leftJoin('layanan', 'layanan.id', '=', 'kualifikasi_terapis.layanan_id')
                    ->leftJoin('pegawai', 'kualifikasi_terapis.pegawai_id', '=', 'pegawai.id')
                    ->leftJoin(
                        'kalendar_shift',
                        'kalendar_shift.pegawai_id',
                        '=',
                        'pegawai.id'
                    )
                    ->leftJoin('shift', 'shift.id', '=', 'kalendar_shift.shift_id')
                    ->where('kualifikasi_terapis.layanan_id', $layanan)
                    ->where('pegawai.role', 3)
                    ->where('kalendar_shift.ijin', '=', 0)
                    ->where('kalendar_shift.cabang_id', $cabang)
                    ->where('kalendar_shift.tanggal', $tanggal)
                    ->WhereTime('shift.jam_akhir', '>', $jam)
                    ->select(
                        DB::raw("IF((SELECT COUNT(dtp.pegawai_id) from transaksi_detail dtp left join transaksi t ON t.id = dtp.transaksi_id where t.status_pembayaran != 'terbayar' AND t.status != 4 AND t.status != 1 AND dtp.pegawai_id = pegawai.id AND DATE(t.waktu_reservasi) = '" . $tanggal . "' AND TIME(t.waktu_reservasi) <= '" . $jam . "') > 0, 'true', 'false') as on_work"),
                        DB::raw("IF(shift.jam_awal <= '$jam' AND shift.jam_akhir > '$jam', 'true', 'false') as available"),
                        'pegawai.*'
                    )
                    ->groupBy('kalendar_shift.pegawai_id')
                    ->get();
            }
            echo json_encode($data);
        }
    }

    public function _option(Request $request)
    {
        if (empty($request->table)) {
            return abort(403);
        }

        if (in_array($request->table, array('agama', 'room', $this->table_lokasi, $this->table_member, $this->table_layanan, $this->table_paket, $this->table_pegawai))) {
            if ($request->table == 'terapis') {
                $data = DB::table($request->table)->where('role', 3)->get();
            } else {
                if ($request->table == 'layanan' & !empty($request->paket_id)) {
                    $data_paket_layanan = DB::table($request->table)
                        ->leftJoin('paket_detail', 'layanan.id', '=', 'paket_detail.layanan_id')
                        ->where('paket_detail.paket_id', $request->paket_id)
                        ->select('layanan.id')
                        ->get();
                    $data_ = DB::table($request->table);
                    foreach ($data_paket_layanan as $layanan) {
                        $data_->where('id', '!=', $layanan->id);
                    }
                    $data = $data_->get();
                } else if ($request->has('harga') && $request->table == 'layanan') {
                    $data = DB::table($this->table_layanan)
                        ->where('id', $request->harga)
                        ->select('layanan.harga')
                        ->first();
                } else if ($request->table == "agama") {
                    $data = array(
                        ['id' => 1, 'name' => 'Islam'],
                        ['id' => 2, 'name' => 'Kristen'],
                        ['id' => 3, 'name' => 'Katholik'],
                        ['id' => 4, 'name' => 'Hindu'],
                        ['id' => 5, 'name' => 'Budha'],
                        ['id' => 6, 'name' => 'Lainnya'],
                    );
                } else {
                    $data = DB::table($request->table)->get();
                }
            }
            echo json_encode($data);
        }
    }

    public function _detail(Request $request)
    {
        echo json_encode(DB::table($this->table_member)
            ->where('id', $request->id)
            ->get());
    }

    public function _gen(Request $request)
    {
        if (empty($request->id)) {
            return abort(403);
        }
        $characters = '1234567890';
        $pin = mt_rand(1000, 9999)
            . mt_rand(1000, 9999)
            . $characters[rand(0, strlen($characters) - 1)];
        $string = str_shuffle($pin);
        return [
            'code' => 200,
            'auto' => $string
        ];
    }

    public function update($id)
    {
        //
    }

    public function rulesEmail($id = false)
    {
        $validator = Validasi::make(['email' => $id], [
            'email' => (!is_numeric($id) || empty($id)) ?  'string|email|unique:users,email' : 'string|email|unique:users,email,' . $id
        ]);

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

    function valid_email($str)
    {
        return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
    }

    function uniq_referal_code($id)
    {
        $random = mt_rand(100000, 999999);

        $refCode = DB::table($this->table_member)
            ->where('user_id', $id)
            ->first();

        if (empty($refCode)) {
            return $random;
        } else {
            if (!empty($refCode) && !empty($refCode->referal_code)) {
                return $refCode->referal_code;
            } else {
                return $random;
            }
        }
    }

    public function store(Request $request)
    {

        $mess = null;
        $this->validated($mess, $request);
        $total_harga = 0;
        DB::transaction(function () use ($request, $mess, $total_harga) {

            if (!empty($request->ino_member)) {

                if (!empty($request->email)) {
                    $UserMail_ = null;
                    DB::transaction(function () use ($request, &$UserMail_) {

                        $this->rulesEmail($request->email);

                        $UserMail_ = DB::table($this->table_user)->insertGetId([
                            'name' => $request->nama,
                            'email' => $request->email,
                            'password' => bcrypt($request->nama),
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    });

                    $UserMail = $UserMail_;
                } else {
                    $characters = '1234567890';
                    $pin = mt_rand(0, 999999)
                        . mt_rand(0, 999999)
                        . $characters[rand(0, strlen($characters) - 1)];
                    $string = str_shuffle($pin);

                    $UserMail = DB::table($this->table_user)->insertGetId([
                        'name' => 'New Member',
                        'email' => 'membernew' . $string . '@medinadental.clinic',
                        'password' => bcrypt('newmember'),
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }

                $last_id = DB::table($this->table_member)->insertGetId([
                    'no_member' => $request->ino_member,
                    'user_id' => $UserMail,
                    'nama' => $request->nama,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'tgl_lahir' => empty($request->tanggal_lahir) ? null : DATE('Y-m-d', strtotime($request->tanggal_lahir)),
                    'nik' => $request->nik,
                    'agama' => $request->agama,
                    'profesi' => $request->profesi,
                    'instansi' => $request->instansi,
                    'status_member' => $request->status_member,
                    'referal_code' => $this->uniq_referal_code($UserMail),
                    'alamat' => $request->alamat,
                    'email' => empty($request->email) ? '' : $request->email,
                    'telepon' => $request->telepon,
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                $transId = DB::table($this->table)->insertGetId($this->fields($request, $last_id));

                // if ($request->has('paket')) {
                //     foreach ($request->paket as $numP => $pkt) {
                //         if (!empty($pkt)) {
                //             $paket_layanan = DB::table('paket_detail')->where('paket_id', $pkt);
                //             if ($paket_layanan->count() > 0) {
                //                 $dataDetailIns1 = array();
                //                 foreach ($paket_layanan->get() as $num => $pl) {
                //                     $dataDetailIns1[] = array(
                //                         'transaksi_id' => $transId,
                //                         'posisi' => $numP + 1,
                //                         'paket_id' => $pkt,
                //                         'layanan_id' => $pl->layanan_id,
                //                         'pegawai_id' => empty($request->pkt_layanan_terapis[$numP][$num]) ? null : $request->pkt_layanan_terapis[$numP][$num],
                //                         'kuantitas' => null,
                //                         'harga' => DB::table('paket')->where('id', $pkt)->first()->harga / $paket_layanan->count(),
                //                         'created_at' => date("Y-m-d H:i:s"),
                //                     );
                //                 }
                //                 DB::table($this->table_detail)->insert($dataDetailIns1);
                //             }
                //         }
                //     }
                // }

                if ($request->has('layanan')) {
                    foreach ($request->layanan as $num => $lay) {
                        if (!empty($lay)) {
                            $dataDetailIns2 = array();
                            $dataDetailIns2[] = array(
                                'transaksi_id' => $transId,
                                'layanan_id' => $lay,
                                'pegawai_id' => empty($request->terapis[$num]) ? null : $request->terapis[$num],
                                'kuantitas' => null,
                                'harga' => DB::table('layanan')->where('id', $lay)->first()->harga,
                                'created_at' => date("Y-m-d H:i:s"),
                            );
                            DB::table($this->table_detail)->insert($dataDetailIns2);
                        }
                    }

                    //set proses ke
                }


                $harga_transaksi = DB::table($this->table_detail)
                    // ->select(DB::raw('DISTINCT(layanan_id), harga'))
                    ->select(DB::raw('layanan_id, harga'))
                    ->where('transaksi_id', $transId)
                    ->get();

                foreach ($harga_transaksi as $harga) {
                    $total_harga += $harga->harga;
                }

                DB::table($this->table)->where('id', $transId)->update([
                    'total_biaya' => $total_harga,
                    'hutang_biaya' => $total_harga - $request->dp,
                    'created_at' => date("Y-m-d H:i:s"),
                ]);

                if ($transId) {
                    $mess['msg'] = 'Data sukses ditambahkan';
                    $mess['cd'] = 200;
                    $mess['idTrans'] = $transId;
                } else {
                    $mess['msg'] = 'Data gagal ditambahkan';
                    $mess['cd'] = 500;
                }
            } else {

                $cekMailUser = DB::table($this->table_member)
                    ->where('id', $request->sno_member);

                if ($request->has('email')) {

                    if (!$this->valid_email($request->email) && !empty($request->email)) {
                        $mess['msg'] = 'Cek input Data Email!, silakan masukkan Email yang valid!';
                        $mess['cd'] = 500;
                        echo json_encode($mess);
                        die;
                    }

                    if ($cekMailUser->count() > 0 and !empty($cekMailUser->first()->email != $request->email)) {
                        $mess['msg'] = 'Email telah terdaftar sebagai member!.<br>Tidak diperkenankan merubah email!.';
                        $mess['cd'] = 500;
                        echo json_encode($mess);
                        return false;
                    } else {

                        if (!empty($request->email) && !empty($cekMailUser->first()->user_id)) {
                            if (!$this->rulesEmail($cekMailUser->first()->user_id)) {
                                $mess['msg'] = 'Email ini telah terdaftar dimember lain!';
                                $mess['cd'] = 500;
                                echo json_encode($mess);
                                die;
                            }
                        }

                        $member = DB::table($this->table_member)->where('id', $request->sno_member);

                        if ($member->count() > 0) {
                            $member->update([
                                'nama' => $request->nama,
                                'jenis_kelamin' => $request->jenis_kelamin,
                                'tgl_lahir' => empty($request->tanggal_lahir) ? null : DATE('Y-m-d', strtotime($request->tanggal_lahir)),
                                'nik' => $request->nik,
                                'agama' => $request->agama,
                                'profesi' => $request->profesi,
                                'instansi' => $request->instansi,
                                'status_member' => $request->status_member,
                                'referal_code' => $this->uniq_referal_code($member->first()->user_id),
                                'email' => $request->email,
                                'telepon' => $request->telepon,
                                'alamat' => $request->alamat,
                            ]);

                            if (!empty($member->first()->user_id)) {
                                $user = DB::table($this->table_user)->where('id', $member->first()->user_id);
                                if ($user->count() > 0 && !empty($request->email)) {
                                    $user->update([
                                        'name' => $request->nama,
                                        'email' => $request->email,
                                    ]);
                                }
                            }

                            $transId = DB::table($this->table)->insertGetId($this->fields($request, $member->first()->id));

                            // if ($request->has('paket')) {
                            //     foreach ($request->paket as $numP => $pkt) {
                            //         if (!empty($pkt)) {
                            //             $paket_layanan = DB::table('paket_detail')->where('paket_id', $pkt);
                            //             if ($paket_layanan->count() > 0) {
                            //                 $dataDetailIns1 = array();
                            //                 foreach ($paket_layanan->get() as $num => $pl) {
                            //                     $dataDetailIns1[] = array(
                            //                         'transaksi_id' => $transId,
                            //                         'posisi' => $numP + 1,
                            //                         'paket_id' => $pkt,
                            //                         'layanan_id' => $pl->layanan_id,
                            //                         'pegawai_id' => empty($request->pkt_layanan_terapis[$numP][$num]) ? null : $request->pkt_layanan_terapis[$numP][$num],
                            //                         'kuantitas' => null,
                            //                         'harga' => DB::table('paket')->where('id', $pkt)->first()->harga / $paket_layanan->count(),
                            //                         'created_at' => date("Y-m-d H:i:s"),
                            //                     );
                            //                 }
                            //                 DB::table($this->table_detail)->insert($dataDetailIns1);
                            //             }
                            //         }
                            //     }
                            // }

                            if ($request->has('layanan')) {
                                foreach ($request->layanan as $num => $lay) {
                                    if (!empty($lay)) {
                                        $dataDetailIns2 = array();
                                        $dataDetailIns2[] = array(
                                            'transaksi_id' => $transId,
                                            'layanan_id' => $lay,
                                            'pegawai_id' => empty($request->terapis[$num]) ? null : $request->terapis[$num],
                                            'kuantitas' => null,
                                            'harga' => DB::table('layanan')->where('id', $lay)->first()->harga,
                                            'created_at' => date("Y-m-d H:i:s"),
                                        );
                                        DB::table($this->table_detail)->insert($dataDetailIns2);
                                    }
                                }
                            }


                            $harga_transaksi = DB::table($this->table_detail)
                                // ->select(DB::raw('DISTINCT(layanan_id), harga'))
                                ->select(DB::raw('layanan_id, harga'))
                                ->where('transaksi_id', $transId)
                                ->get();

                            foreach ($harga_transaksi as $harga) {
                                $total_harga += $harga->harga;
                            }

                            DB::table($this->table)->where('id', $transId)->update([
                                'total_biaya' => $total_harga,
                                'hutang_biaya' => $total_harga - $request->dp,
                                'created_at' => date("Y-m-d H:i:s"),
                            ]);

                            if ($transId) {
                                $mess['msg'] = 'Data sukses ditambahkan';
                                $mess['cd'] = 200;
                                $mess['idTrans'] = $transId;
                            } else {
                                $mess['msg'] = 'Data gagal ditambahkan';
                                $mess['cd'] = 500;
                            }
                        } else {
                            $mess['msg'] = 'Data gagal ditambahkan';
                            $mess['cd'] = 500;
                        }
                    }
                }
            }
            echo json_encode($mess);
        });
    }
}
