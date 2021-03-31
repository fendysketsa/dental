<?php

namespace App\Http\Controllers\Web\Monitoring;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Validator as Validasi;
use App\Models\User;
use App\Models\Transaction\TransaksiModel as TransactionModel;
use App\TransaksiDetailModel as TransactionDetailModel;
use App\TransaksiDetailTambahanModel;
use App\Models\MemberModel;
use App\Models\RoomsModel;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    protected $table = 'transaksi';
    protected $table_produk = 'produk';
    protected $table_detail = 'transaksi_detail';
    protected $table_tambahan = 'transaksi_tambahan';
    protected $table_rekam = 'transaksi_rekam';
    protected $table_rekam_gigi = 'transaksi_rekam_gigi';
    protected $table_member = 'member';
    protected $table_pegawai = 'pegawai';
    protected $table_layanan = 'layanan';
    protected $table_user = 'users';
    protected $table_tindakan_gigi = 'transaksi_tindakan_gigi';
    protected $table_rekam_tindakan_gigi = 'transaksi_rekam_tindakan_gigi';
    protected $table_diagnosis = 'diagnosis';

    private $dir = 'app/public/master-data/upload/gigi/pasien/';
    private $dirTindakan = 'app/public/master-data/upload/gigi/pasien/tindakan/';

    private $validate_messageMember = [
        'nama' => 'required',
        'email' => 'required',
        'telepon' => 'required',
    ];

    private $validateGigi_message = [
        'foto' => 'image|mimes:jpeg,png,jpg|max:2048',
        'gigi' => 'required'
    ];

    public function fieldsGigi($request, $gambar)
    {
        $data_add = !empty($gambar) ? ['foto' => $gambar] : ['foto' => null];
        $data = [
            'gigi' => $request->gigi,
            'transaksi_id' => $request->id,
            'ringkasan' => $request->ringkasan_gigi ? $request->ringkasan_gigi : '-',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ];

        return array_merge($data_add, $data);
    }

    public function fields($request, $last_id)
    {
        $wak_res = (!empty($request->tgl_reservasi) && !empty($request->jam_reservasi)) ?
            date('Y-m-d H:i:s', strtotime($request->tgl_reservasi . ' ' . $request->jam_reservasi)) : null;
        return [
            'member_id' => $last_id,
            'jumlah_orang' => $request->jumlah_orang,
            'lokasi_id' => $request->lokasi_reservasi,
            'waktu_reservasi' => $wak_res,
            'dp' => 0,
            'room_id' => $request->room,
            'dokter_id' => $request->dokter,
            //'paket_id' => $request->paket,
            'tanggal_comeback' => empty($request->tanggal_next) ? null : date('Y-m-d H:i:s', strtotime($request->tanggal_next)),
            'status' => 2,
        ];
    }

    public function fieldsPeriksa($request)
    {
        return [
            'jumlah_orang' => $request->jumlah_orang,
            'dp' => 0,
            'room_id' => $request->room,
            'dokter_id' => $request->dokter,
            'tanggal_comeback' => empty($request->tanggal_next) ? null : date('Y-m-d H:i:s', strtotime($request->tanggal_next)),
        ];
    }

    public function validatedGigi($mess, $request)
    {
        $validator = \Validator::make($request->all(), $this->validateGigi_message);
        if ($validator->fails()) {
            $d_error = '<ul>';
            foreach ($validator->errors()->all() as $row) {
                $d_error .= '<li>' . $row . '</li>';
            }

            if ($this->arrayIsNotEmpty($request->layanan) > 0) {
                $d_error .= '<li>Bidang pilihan layanan wajib dipilih</li>';
            }

            if ($this->arrayIsNotEmpty($request->category) > 0) {
                $d_error .= '<li>Bidang pilihan kategori wajib dipilih</li>';
            }

            $d_error .= '</ul>';
            $mess['msg'] = 'Ada beberapa masalah dengan inputan Anda!' . $d_error;
            $mess['cd'] = 500;
            echo json_encode($mess);
            exit;
        } else {

            $kesalahan = 0;

            $d_error = '<ul>';

            if ($this->arrayIsNotEmpty($request->layanan) > 0) {
                $kesalahan += 1;
                $d_error .= '<li>Bidang pilihan layanan wajib dipilih</li>';
            }

            if ($this->arrayIsNotEmpty($request->category) > 0) {
                $kesalahan += 1;
                $d_error .= '<li>Bidang pilihan kategori wajib dipilih</li>';
            }

            $d_error .= '</ul>';

            if ($kesalahan > 0) {
                $mess['msg'] = 'Ada beberapa masalah dengan inputan Anda!' . $d_error;
                $mess['cd'] = 500;
                echo json_encode($mess);
                exit;
            }
        }
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
            // 'jumlah_orang' => 'required',
            'dokter' => 'required|not_in:0',
            'room' => 'required|not_in:0',
        );

        $message_inp = array(
            'ino_member' => 'required|unique:member,no_member',
            // 'jumlah_orang' => 'required',
            'dokter' => 'required|not_in:0',
            'room' => 'required|not_in:0',
        );

        $message_paket = array(
            'paket' => 'not_in:0',
        );

        $message_layanan = array(
            'layanan' => 'not_in:0',
        );

        $message_terapis = array(
            'terapis' => 'not_in:0',
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
            'terapis.required' => 'Bidang pilihan :attribute wajib dipilih',
            'dokter.required' => 'Bidang pilihan dokter wajib dipilih',
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

            if ($this->arrayIsNotEmpty($request->layanan) > 0) {
                $d_error .= '<li>Bidang pilihan layanan wajib dipilih</li>';
            }

            if ($this->arrayIsNotEmpty($request->category) > 0) {
                $d_error .= '<li>Bidang pilihan kategori wajib dipilih</li>';
            }

            $d_error .= '</ul>';
            $mess['msg'] = 'Ada beberapa masalah dengan inputan Anda!' . $d_error;
            $mess['cd'] = 500;
            echo json_encode($mess);
            exit;
        } else {

            $kesalahan = 0;

            $d_error = '<ul>';

            if ($this->arrayIsNotEmpty($request->layanan) > 0) {
                $kesalahan += 1;
                $d_error .= '<li>Bidang pilihan layanan wajib dipilih</li>';
            }

            if ($this->arrayIsNotEmpty($request->category) > 0) {
                $kesalahan += 1;
                $d_error .= '<li>Bidang pilihan kategori wajib dipilih</li>';
            }

            $d_error .= '</ul>';

            if ($kesalahan > 0) {
                $mess['msg'] = 'Ada beberapa masalah dengan inputan Anda!' . $d_error;
                $mess['cd'] = 500;
                echo json_encode($mess);
                exit;
            }
        }
    }

    private function arrayIsNotEmpty($arr)
    {
        $fls = 0;

        foreach ($arr as $key => $value) {
            if (empty($value)) {
                $fls++;
            }
        }
        if (empty($arr)) {
            $fls;
        }

        return $fls;
    }

    function metode_bayars($met)
    {
        $mett = null;
        if (empty($met)) {
            return $mett;
        }

        switch ($met) {
            case 1:
                $mett .= 'Debit';
                break;
            case 2:
                $mett .= 'Visa';
                break;
            case 3:
                $mett .= 'Master Card';
                break;
            case 4:
                $mett .= 'Transfer';
                break;
        }
        dd($mett);
        exit;

        return $mett;
    }

    public function detTrans($id)
    {
        $dTransaksi = !empty($id) ?
            DB::table($this->table)
            ->leftJoin($this->table_member, $this->table_member . '.id', '=', $this->table . '.member_id')
            ->where($this->table . '.id', $id)
            ->select(
                $this->table_member . '.nama as member',
                $this->table . '.no_transaksi',
                $this->table_member . '.no_member',
                $this->table . '.created_at',
                $this->table . '.total_biaya',
                $this->table . '.hutang_biaya',
                $this->table . '.diskon',
                $this->table . '.cara_bayar_kasir',
                DB::raw("IF("
                    . $this->table . ".metode_bayar = 1, 'Debit', IF("
                    . $this->table . ".metode_bayar = 2, 'Visa', IF("
                    . $this->table . ".metode_bayar = 3, 'Master Card', IF("
                    . $this->table . ".metode_bayar = 4, 'Transfer', '')))) as metode_bayar"),
                DB::raw("IF("
                    . $this->table . ".kd_kartu = 1, 'BCA', IF("
                    . $this->table . ".kd_kartu = 2, 'BNI', IF("
                    . $this->table . ".kd_kartu = 3, 'BRI', IF("
                    . $this->table . ".kd_kartu = 4, 'Mandiri', IF("
                    . $this->table . ".kd_kartu = 5, 'Lainnya', ''))))) as kd_kartu"),
                $this->table . '.nominal_bayar',
                $this->table . '.kembalian'
            )
            ->first() : '';

        $dTLayanan = !empty($id) ?
            DB::table($this->table_detail)
            ->leftJoin($this->table_pegawai, $this->table_pegawai . '.id', '=', $this->table_detail . '.pegawai_id')
            ->leftJoin($this->table_layanan, $this->table_layanan . '.id', '=', $this->table_detail . '.layanan_id')
            ->leftJoin('kategori', 'layanan.kategori_id', '=', 'kategori.id')
            ->where($this->table_detail . '.transaksi_id', $id)
            ->where('kategori.jenis', 1)
            ->whereNull('paket_id')
            ->select(
                // DB::raw('DISTINCT(transaksi_detail.layanan_id), IF(kategori.slug, kategori.slug, kategori.nama) as kategori'),
                DB::raw('IF(kategori.slug, kategori.slug, kategori.nama) as kategori'),
                $this->table_layanan . '.nama as layanan',
                $this->table_pegawai . '.nama as terapis',
                $this->table_layanan . '.harga as harga'
            )
            ->get() : '';

        $dTPaket = !empty($id) ?
            DB::table($this->table_detail)
            ->leftJoin($this->table_pegawai, $this->table_pegawai . '.id', '=', $this->table_detail . '.pegawai_id')
            ->leftJoin($this->table_layanan, $this->table_layanan . '.id', '=', $this->table_detail . '.layanan_id')
            ->leftJoin('paket', $this->table_detail . '.paket_id', '=', 'paket.id')
            ->where($this->table_detail . '.transaksi_id', $id)
            ->whereNotNull('paket_id')
            ->select(
                DB::raw('IF((SELECT id from transaksi_detail where paket_id = paket.id AND paket_id is not null and transaksi_id = "' . $id . '" order by id, posisi ASC limit 1) = transaksi_detail.id, paket.nama,"") as paket'),
                $this->table_layanan . '.nama as layanan',
                $this->table_pegawai . '.nama as terapis',
                'paket.harga as harga_paket'
            )
            ->orderBy('paket.id', 'ASC')
            ->orderBy('layanan.id', 'ASC')
            ->get() : '';

        $dTProduk = !empty($id) ?
            DB::table($this->table_detail)
            ->leftJoin($this->table_produk, $this->table_produk . '.id', '=', $this->table_detail . '.produk_id')
            ->where($this->table_detail . '.transaksi_id', $id)
            ->whereNotNull('produk_id')
            ->select(
                $this->table_produk . '.nama as produk',
                $this->table_detail . '.harga as harga',
                $this->table_detail . '.kuantitas as kuantitas'
            )
            ->get() : '';

        if (!empty($_GET['printact']) && $_GET['printact'] == 'yes') {
            $getPrintAct = DB::table($this->table)->where('id', $id);
            if ($getPrintAct->first()->print_act == 0) {
                $getPrintAct->update([
                    'print_act' => 1
                ]);
            }
        }

        return response()->json([
            'data' => $dTransaksi,
            'layanan' => $dTLayanan,
            'paket' => $dTPaket,
            'produk' => $dTProduk
        ]);
    }

    public function print(Request $request)
    {
        if (empty($request))
            show(404);

        $id = $request->id;

        $data['transaksi'] = TransactionModel::find($id)->toArray();

        $data['ruang'] = RoomsModel::find($data['transaksi']['room_id'])->toArray();

        $data['det_transaksi'] = TransactionDetailModel::leftJoin('transaksi', 'transaksi.id', '=', 'transaksi_detail.transaksi_id')
            ->leftJoin('layanan', 'transaksi_detail.layanan_id', '=', 'layanan.id')
            ->where('transaksi_detail.transaksi_id', $data['transaksi']['id'])
            ->select('transaksi_detail.*', 'transaksi.*', 'layanan.nama as name', 'transaksi_detail.harga_fix as price_fix')
            ->get()
            ->toArray();

        $data['det_transaksi_tambahan'] = TransaksiDetailTambahanModel::leftJoin('transaksi', 'transaksi.id', '=', 'transaksi_tambahan.transaksi_id')
            ->where('transaksi_id', $data['transaksi']['id'])
            ->select('transaksi_tambahan.*')
            ->get()
            ->toArray();

        $data['member'] = MemberModel::where('user_id', $data['transaksi']['member_id'])->first()->toArray();

        $data['noUrut'] = 0;
        $data['subTotal'] = 0;
        $data['Total'] = 0;

        return view('monitoring.order.content.print', $data);
    }

    protected function cekOrder()
    {
        $cekPrint = DB::table('transaksi')
            ->where('status', 2)
            ->where('status_pembayaran', 'pendaftaran')
            ->where(DB::RAW('DATE(waktu_reservasi)'), '=', DATE('Y-m-d'))
            ->where(DB::RAW('date_add(TIME(waktu_reservasi),interval 360 minute)'), '<', DATE('H:i'));

        if (!empty($cekPrint->get())) {
            foreach ($cekPrint->get() as $r) {
                if ($r->print_act == 0 or empty($r->print_act)) {
                    DB::table('transaksi')
                        ->where('status', 2)
                        ->where('status_pembayaran', 'pendaftaran')
                        ->where(DB::RAW('DATE(waktu_reservasi)'), '=', DATE('Y-m-d'))
                        ->where(DB::RAW('date_add(TIME(waktu_reservasi),interval 360 minute)'), '<', DATE('H:i'))->where('id', $r->id)
                        ->update([
                            'status' => 4
                        ]);
                }
            }
        }
    }

    public function _reload()
    {
        $this->cekOrder();
        return response()->json([
            'msg' => 'Pengecekan data terkini selesai!',
        ]);
    }

    public function index(Request $request)
    {
        $this->cekOrder();
        return view('monitoring.order.index', [
            'js' => [
                's-home/dist/js/sprintf.js',
                's-home/monitoring/order/js/order.js',
                's-home/monitoring/order/js/gigi.js',
                // 'https://cdn.jsdelivr.net/npm/recta/dist/recta.js'
                's-home/dist/js/recta.js'
            ],
            'attribute' => [
                'm_mntrgO' => 'true',
                'menu_orderM' => 'active menu-open',
                'title_bc' => 'Monitoring Order',
                'desc_bc' => 'Digunakan untuk media menampilkan order',
            ],
            'data' => null,
            'terapis' => null,
            'layanan' => null,
            'action' => '',
        ]);
    }

    public function sendPembayaran(Request $request)
    {
        if (empty($request->order_id)) {
            return abort(404);
        }

        if ($request->has('order_id')) {
            $mess = null;
            DB::transaction(function () use ($mess, $request) {
                $dataOrder = DB::table($this->table)
                    ->where('id', $request->order_id);

                // if ($dataOrder->first()->print_act == 0) {
                //     $mess['msg'] = 'Data gagal dikirim ke pembayaran, Akses ditolak!';
                //     $mess['cd'] = 500;
                // } else {

                $dataOrder_ = DB::table($this->table)
                    ->where('id', $request->order_id)->update([
                        'status_pembayaran' => 'pembayaran'
                    ]);

                if ($dataOrder) {
                    $mess['msg'] = 'Data sukses disimpan' . ($dataOrder_ == 0 ? ", namun belum ke proses pembayaran" : " dan terkirim ke pembayaran");
                    $mess['cd'] = 200;
                } else {
                    $mess['msg'] = 'Data gagal disimpan dan dikirim';
                    $mess['cd'] = 500;
                }
                // }
                echo json_encode($mess);
            });
        }
    }

    public function voidPembayaran(Request $request)
    {
        if (empty($request->order_id)) {
            return abort(404);
        }

        if ($request->has('order_id')) {
            $mess = null;
            DB::transaction(function () use ($mess, $request) {
                $dataOrder = DB::table($this->table)
                    ->where('id', $request->order_id);

                if (in_array($dataOrder->first()->status, array(1, 3, 4))) {
                    $mess['msg'] = 'Data gagal dikirim ke pembayaran, Akses ditolak!';
                    $mess['cd'] = 500;
                } else {

                    $dataOrder_ = DB::table($this->table)
                        ->where('id', $request->order_id)->update([
                            'status' => 1
                        ]);

                    if ($dataOrder) {
                        $mess['msg'] = 'Data sukses disimpan' . ($dataOrder_ == 0 ? ", namun belum ke proses pembatalan" : " dan dibatalkan");
                        $mess['cd'] = 200;
                    } else {
                        $mess['msg'] = 'Data gagal disimpan dan dibatalkan';
                        $mess['cd'] = 500;
                    }
                }
                echo json_encode($mess);
            });
        }
    }

    public function printOrder(Request $request)
    {
        if (empty($request->order_id)) {
            return abort(404);
        }


        if ($request->has('order_id')) {
            $mess = null;
            DB::transaction(function () use ($mess, $request) {
                $dataOrder = DB::table($this->table)
                    ->where('id', $request->order_id)
                    ->update([
                        'status_pembayaran' => 'pembayaran'
                    ]);

                if ($dataOrder) {
                    $mess['msg'] = 'Data sukses disimpan' . ($dataOrder == 0 ? ", namun belum ke proses pembayaran" : " dan terkirim ke pembayaran");
                    $mess['cd'] = 200;
                } else {
                    $mess['msg'] = 'Data gagal disimpan';
                    $mess['cd'] = 500;
                }

                echo json_encode($mess);
            });
        }
    }

    public function periksas($id)
    {
        if (!empty($id)) :
            $dataTrans = DB::table($this->table)
                ->where('id', $id)->get();

            $dataLayanan = DB::table($this->table_detail)
                ->select(
                    DB::raw('GROUP_CONCAT(IF(layanan_id, layanan_id, 0)) as layanan'),
                    DB::raw('GROUP_CONCAT(IF(category_id, category_id, 0)) as category'),
                    DB::raw('GROUP_CONCAT(IF(harga_fix, harga_fix, 0)) as price_fix'),
                    DB::raw('GROUP_CONCAT(IF(pegawai_id, pegawai_id, 0)) as terapis')
                )
                ->whereNull('paket_id')
                ->where('transaksi_id', $id)->get();

            $dataLayananTambahan = DB::table($this->table_tambahan)
                ->select('name', 'price')
                ->where('transaksi_id', $id)->get();

            $dataGigi = DB::table($this->table_rekam_gigi)
                ->select('gigi', 'ringkasan', 'foto')
                ->where('transaksi_id', $id)->get();

            $dataTindakanGigi = DB::table($this->table_tindakan_gigi)
                ->leftJoin($this->table_diagnosis, $this->table_diagnosis . '.id', '=', $this->table_tindakan_gigi . '.diagnosa_id')
                ->leftJoin($this->table_layanan, $this->table_layanan . '.id', '=', $this->table_tindakan_gigi . '.tindakan_id')
                ->select('diagnosa_id', 'tindakan_id', 'catatan', 'image', $this->table_diagnosis . '.nama as diagnosa_text', $this->table_layanan . '.nama as tindakan_text')
                ->where('transaksi_id', $id)->get();

            $dataRekTindakanGigi = DB::table($this->table_rekam_tindakan_gigi)
                ->select('gigi_no', 'gigi_no_posisi')
                ->where('transaksi_id', $id)->get();

            $dataRekam_ = DB::table($this->table_rekam)
                ->select('id', 'name', 'more_keterangan', 'position')
                ->where('transaksi_id', $id)
                ->orderBy('position', 'ASC')
                ->get();

            $dataRekam = array();
            foreach ($dataRekam_ as $r) {
                $dataRekam[$r->position]['position'] = $r->position;
                $dataRekam[$r->position]['name'] = $r->name;
                $dataRekam[$r->position]['more'] = $r->more_keterangan;
            }

        endif;

        return view('monitoring.order.content.form.modal.index_periksa', [
            'data' => !empty($id) ? $dataTrans : null,
            'services' => !empty($id) ? $dataLayanan : null,
            'services_add' => !empty($id) ? $dataLayananTambahan : null,
            'rekam' => !empty($id) ? json_encode($dataRekam, true) : null,
            'rekam_gigi' => !empty($id) ? json_encode($dataGigi, true) : null,
            'tindakan_gigi' => !empty($id) ? json_encode($dataTindakanGigi, true) : null,
            'rekam_tindakan_gigi' => !empty($id) ? json_encode($dataRekTindakanGigi, true) : null,
            'action' => ""
        ]);
    }

    public function update($id)
    {
        if (!empty($id)) :
            $dataTrans = DB::table($this->table)
                ->where('id', $id)->get();

            $dataLayanan = DB::table($this->table_detail)
                ->select(
                    DB::raw('GROUP_CONCAT(IF(layanan_id, layanan_id, 0)) as layanan'),
                    DB::raw('GROUP_CONCAT(IF(category_id, category_id, 0)) as category'),
                    DB::raw('GROUP_CONCAT(IF(harga_fix, harga_fix, 0)) as price_fix'),
                    DB::raw('GROUP_CONCAT(IF(pegawai_id, pegawai_id, 0)) as terapis')
                )
                ->whereNull('paket_id')
                ->where('transaksi_id', $id)->get();

            $dataLayananTambahan = DB::table($this->table_tambahan)
                ->select('name', 'price')
                ->where('transaksi_id', $id)->get();

            $dataRekam_ = DB::table($this->table_rekam)
                ->select('id', 'name', 'more_keterangan', 'position')
                ->where('transaksi_id', $id)
                ->orderBy('position', 'ASC')
                ->get();

            $dataRekam = array();
            foreach ($dataRekam_ as $r) {
                $dataRekam[$r->position]['position'] = $r->position;
                $dataRekam[$r->position]['name'] = $r->name;
                $dataRekam[$r->position]['more'] = $r->more_keterangan;
            }

            $dataPaketLayanan = DB::table($this->table_detail)
                ->select(
                    DB::raw('GROUP_CONCAT(IF(pegawai_id, pegawai_id, 0)) as terapis')
                )
                ->whereNotNull('paket_id')
                ->where('transaksi_id', $id)
                ->groupBy('posisi')
                ->orderBy('posisi', 'ASC')
                ->get();

            $dataPaketLayananPosisi = DB::table($this->table_detail)
                ->select(
                    DB::raw('DISTINCT(posisi) as posisi')
                )
                ->whereNotNull('paket_id')
                ->where('transaksi_id', $id)->get();

            $dataPaket = DB::table($this->table_detail)
                ->select(
                    DB::raw('GROUP_CONCAT(DISTINCT(paket_id)) as paket')
                )
                ->whereNotNull('paket_id')
                ->where('transaksi_id', $id)->get();

        endif;

        return view('monitoring.order.content.form.modal.index', [
            'data' => !empty($id) ? $dataTrans : null,
            'services' => !empty($id) ? $dataLayanan : null,
            'services_add' => !empty($id) ? $dataLayananTambahan : null,
            'rekam' => !empty($id) ? json_encode($dataRekam, true) : null,
            'posisi' => !empty($id) ? $dataPaketLayananPosisi : null,
            'pktservices' => !empty($id) ? $dataPaketLayanan : null,
            'paket' => !empty($id) ? $dataPaket : null,
            'action' => route('registrations.update', $id)
        ]);
    }

    function valid_email($str)
    {
        return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
    }

    public function rulesEmail($id = false)
    {
        $validator = Validasi::make([(!is_numeric($id) || empty($id)) ? 'email' : 'id' => $id], [
            'email' => (!is_numeric($id) || empty($id)) ?  'unique:users,email' : 'unique:users,email,' . $id
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

    public function createImageTindakan($id, $num, $img)
    {
        $image = $img;

        $image = str_replace('data:image/jpeg;base64,', '', $image);
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace('data:image/jpg;base64,', '', $image);

        $image = str_replace(' ', '+', $image);
        $imageName = $id . "-" . $num . '.png';

        File::delete(storage_path($this->dirTindakan) . $imageName);

        Storage::disk('tindakan')->put($imageName, base64_decode($image));

        return $imageName;
    }

    public function storePeriksa(Request $request)
    {

        $mess = null;
        $this->validatedGigi($mess, $request);

        $total_harga = 0;
        DB::transaction(function () use ($request, $mess, $total_harga) {

            DB::table($this->table)
                ->where('id', $request->id)
                ->update($this->fieldsPeriksa($request));

            if (!empty($request->gigi_no)) {

                DB::table('transaksi_rekam_tindakan_gigi')
                    ->where('transaksi_id', $request->id)
                    ->delete();

                foreach ($request->gigi_no as $num => $rdg) {

                    if (!empty($rdg)) {

                        $dataDetailRekamTindGigi = array();
                        $dataDetailRekamTindGigiPss[$num] = array();

                        if (!empty($request->gigi_no_posisi[$rdg])) {
                            foreach ($request->gigi_no_posisi[$rdg] as $rdg_idx) {
                                if (!empty($rdg_idx)) {
                                    array_push($dataDetailRekamTindGigiPss[$num], (int) $rdg_idx);
                                }
                            }
                        }

                        $dataDetailRekamTindGigi[] = array(
                            'transaksi_id' => $request->id,
                            'gigi_no' => $rdg,
                            'gigi_no_posisi' => json_encode($dataDetailRekamTindGigiPss[$num], true),
                            'created_at' => date("Y-m-d H:i:s"),
                        );

                        DB::table($this->table_rekam_tindakan_gigi)->insert($dataDetailRekamTindGigi);
                    }
                }
            }

            if (!empty($request->diagnosa_id) && !empty($request->tindakan_id)) {

                DB::table('transaksi_tindakan_gigi')
                    ->where('transaksi_id', $request->id)
                    ->delete();

                foreach ($request->diagnosa_id as $num => $dg) {
                    if (!empty($dg) && !empty($request->tindakan_id[$num])) {
                        $dataDetailTindGigi = array();

                        $dataDetailTindGigi[] = array(
                            'transaksi_id' => $request->id,
                            'tindakan_id' => empty($request->tindakan_id[$num]) ? null : $request->tindakan_id[$num],
                            'diagnosa_id' => $dg,
                            'catatan' =>  empty($request->catatan_tindakan[$num]) ? null : $request->catatan_tindakan[$num],
                            'image' => empty($request->tindakan_image[$num]) ? null : $this->createImageTindakan($request->id, $num, $request->tindakan_image[$num]),
                            'created_at' => date("Y-m-d H:i:s"),
                        );
                        DB::table($this->table_tindakan_gigi)->insert($dataDetailTindGigi);
                    }
                }
            }

            if ($request->has('layanan') && $request->has('category')) {

                if (!empty($request->layanan)) {
                    DB::table('transaksi_detail')
                        ->where('transaksi_id', $request->id)
                        ->whereNull('paket_id')
                        ->delete();

                    foreach ($request->layanan as $num => $lay) {
                        if (!empty($lay)) {
                            $dataDetailIns2 = array();
                            $dataDetailIns2[] = array(
                                'transaksi_id' => $request->id,
                                'category_id' => empty($request->category[$num]) ? null : $request->category[$num],
                                'layanan_id' => $lay,
                                'pegawai_id' => empty($request->terapis[$num]) ? null : $request->terapis[$num],
                                'kuantitas' => null,
                                'harga' => DB::table('layanan')->where('id', $lay)->first()->harga,
                                'harga_fix' => empty($request->harga_custom[$num]) ? null : unRupiahFormat($request->harga_custom[$num]),
                                'created_at' => date("Y-m-d H:i:s"),
                            );
                            DB::table($this->table_detail)->insert($dataDetailIns2);
                        }
                    }
                }
            }

            if (!empty($request->layanan_tambahan)) {
                DB::table('transaksi_tambahan')
                    ->where('transaksi_id', $request->id)
                    ->delete();

                foreach ($request->layanan_tambahan as $num => $layTam) {
                    if (!empty($layTam)) {
                        $dataTambahan = array();
                        $dataTambahan[] = array(
                            'transaksi_id' => $request->id,
                            'name' => $layTam,
                            'price' => empty($request->harga_tambahan[$num]) ? null : unRupiahFormat($request->harga_tambahan[$num]),
                            'created_at' => date("Y-m-d H:i:s"),
                            'updated_at' => date("Y-m-d H:i:s"),
                        );
                        DB::table($this->table_tambahan)->insert($dataTambahan);
                    }
                }
            }

            $harga_transaksi = DB::table($this->table_detail)
                // ->select(DB::raw('DISTINCT(layanan_id), harga'))
                ->select(DB::raw('layanan_id, harga_fix'))
                ->where('transaksi_id', $request->id)
                ->get();

            foreach ($harga_transaksi as $harga) {
                $total_harga += $harga->harga_fix;
            }

            $harga_transaksi_tambahan = DB::table($this->table_tambahan)
                // ->select(DB::raw('DISTINCT(layanan_id), harga'))
                ->select(DB::raw('price'))
                ->where('transaksi_id', $request->id)
                ->get();

            foreach ($harga_transaksi_tambahan as $price) {
                $total_harga += $price->price;
            }

            $transId = DB::table($this->table)->where('id', $request->id)->update([
                'total_biaya' => $total_harga,
                'hutang_biaya' => $total_harga - $request->dp,
                'created_at' => date("Y-m-d H:i:s"),
            ]);

            $dataRekam = array();
            if (!empty($_POST['rekam'])) {
                $cekRekam = DB::table($this->table_rekam)->where('transaksi_id', $request->id)->count();

                $dRekam = DB::table('rekam_medik')->where('status', 1)->get();

                foreach ($dRekam as $p) {
                    $Name[$p->id] = null;

                    if (!empty($_POST['rekam'][$p->id])) {
                        if (is_array($_POST['rekam'][$p->id]) && count($_POST['rekam'][$p->id]) > 0) {
                            foreach ($_POST['rekam'][$p->id] as $rekam) {
                                $Name[$p->id] .= $rekam;
                            }
                            $Name[$p->id] = $Name[$p->id];
                        } else {
                            $Name[$p->id] = $_POST['rekam'][$p->id];
                        }
                    }

                    if ($cekRekam > 0) {

                        $cekRekam_[$p->id] = DB::table($this->table_rekam)
                            ->where('transaksi_id', $request->id)
                            ->where('position', $p->id);

                        if ($cekRekam_[$p->id]->count() > 0) {
                            $cekRekam_[$p->id]->update([
                                'name' => $Name[$p->id],
                                'more_keterangan' => empty($request->rekam_more[$p->id]) ? null : $request->rekam_more[$p->id],
                                'updated_at' => date("Y-m-d H:i:s"),
                            ]);
                        } else {
                            $dataRekamm[$p->id] = [
                                'transaksi_id' => $request->id,
                                'position' => $p->id,
                                'name' => $Name[$p->id],
                                'more_keterangan' => empty($request->rekam_more[$p->id]) ? null : $request->rekam_more[$p->id],
                                'created_at' => date("Y-m-d H:i:s"),
                                'position' => $p->id,
                            ];

                            DB::table($this->table_rekam)->insert($dataRekamm[$p->id]);
                        }
                    }

                    array_push($dataRekam, array(
                        'transaksi_id' => $request->id,
                        'name' => $Name[$p->id],
                        'more_keterangan' => empty($request->rekam_more[$p->id]) ? null : $request->rekam_more[$p->id],
                        'created_at' => date("Y-m-d H:i:s"),
                        'position' => $p->id,
                    ));
                }

                if ($cekRekam == 0) {
                    DB::table($this->table_rekam)->insert($dataRekam);
                }
            }

            $filename = null;
            if ($request->hasFile('gambar_gigi') == 1) {
                $extension = $request->file('gambar_gigi')->getClientOriginalExtension();
                if (!empty($request->id)) {
                    $image = DB::table($this->table_rekam_gigi)->where('transaksi_id', $request->id);
                    if ($image->count() > 0 && !empty($image->first()->foto)) {
                        File::delete(storage_path($this->dir) . $image->first()->foto);
                    }
                }
                $filename = uniqid() . '_' . time() . '.' . $extension;
                $request->file('gambar_gigi')->move(storage_path($this->dir), $filename);
            } else {
                if (!empty($request->id)) {
                    if (empty($request->old_img_gigi) && $request->old_img_gigi == '') {
                        $image = DB::table($this->table_rekam_gigi)->where('transaksi_id', $request->id);
                        if ($image->count() > 0 && !empty($image->first()->foto)) {
                            File::delete(storage_path($this->dir) . $image->first()->foto);
                        }
                    } else {
                        $filename = $request->old_img_gigi;
                    }
                }
            }

            $GigiRekam = DB::table($this->table_rekam_gigi)->where('transaksi_id', $request->id);

            if ($GigiRekam->count() > 0) {
                $GigiRekam->update($this->fieldsGigi($request, $filename));
            } else {
                DB::table($this->table_rekam_gigi)->insert($this->fieldsGigi($request, $filename));
            }

            if ($transId) {
                $mess['msg'] = 'Data sukses disimpan' . ($transId == 0 ? ", namun tidak ada perubahan" : " dan diubah");
                $mess['cd'] = 200;
            } else {
                $mess['msg'] = 'Data gagal disimpan';
                $mess['cd'] = 500;
            }
            echo json_encode($mess);
        });
    }

    public function store(Request $request)
    {
        $mess = null;
        $this->validated($mess, $request);

        $total_harga = 0;
        DB::transaction(function () use ($request, $mess, $total_harga) {
            if (!empty($request->ino_member)) {

                $UserMail = DB::table($this->table_user)->insertGetId([
                    'name' => $request->nama,
                    'email' => $request->email,
                    'password' => Hash::make($request->nama), //defaukt nama
                    'created_at' => date('Y-m-d H:i:s')
                ]);

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
                    'email' => $request->email,
                    'telepon' => $request->telepon,
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                DB::table($this->table)
                    ->where('id', $request->id)
                    ->update($this->fields($request, $last_id));

                // if ($request->has('paket')) {
                //     DB::table('transaksi_detail')
                //         ->where('transaksi_id', $request->id)
                //         ->whereNotIn('paket_id')
                //         ->delete();
                //     foreach ($request->paket as $numP => $pkt) {
                //         if (!empty($pkt)) {
                //             $paket_layanan = DB::table('paket_detail')->where('paket_id', $pkt);
                //             if ($paket_layanan->count() > 0) {
                //                 $dataDetailIns1 = array();
                //                 foreach ($paket_layanan->get() as $num => $pl) {
                //                     $dataDetailIns1[] = array(
                //                         'transaksi_id' => $request->id,
                //                         'posisi' => $numP + 1,
                //                         'paket_id' => $pkt,
                //                         'layanan_id' => $pl->layanan_id,
                //                         'pegawai_id' => empty($request->pkt_layanan_terapis[$numP + 1][$num]) ? null : $request->pkt_layanan_terapis[$numP + 1][$num],
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

                if ($request->has('layanan') &&  $request->has('category')) {

                    if (!empty($request->layanan)) {
                        DB::table('transaksi_detail')
                            ->where('transaksi_id', $request->id)
                            ->whereNull('paket_id')
                            ->delete();

                        foreach ($request->layanan as $num => $lay) {
                            if (!empty($lay)) {
                                $dataDetailIns2 = array();
                                $dataDetailIns2[] = array(
                                    'transaksi_id' => $request->id,
                                    'category_id' => empty($request->category[$num]) ? null : $request->category[$num],
                                    'layanan_id' => $lay,
                                    'pegawai_id' => empty($request->terapis[$num]) ? null : $request->terapis[$num],
                                    'kuantitas' => null,
                                    'harga' => DB::table('layanan')->where('id', $lay)->first()->harga,
                                    'harga_fix' => empty($request->harga_custom[$num]) ? null : unRupiahFormat($request->harga_custom[$num]),
                                    'created_at' => date("Y-m-d H:i:s"),
                                );
                                DB::table($this->table_detail)->insert($dataDetailIns2);
                            }
                        }
                    }
                }

                if (!empty($request->layanan_tambahan)) {
                    DB::table('transaksi_tambahan')
                        ->where('transaksi_id', $request->id)
                        ->delete();

                    foreach ($request->layanan_tambahan as $num => $layTam) {
                        if (!empty($layTam)) {
                            $dataTambahan = array();
                            $dataTambahan[] = array(
                                'transaksi_id' => $request->id,
                                'name' => $layTam,
                                'price' => empty($request->harga_tambahan[$num]) ? null : unRupiahFormat($request->harga_tambahan[$num]),
                                'created_at' => date("Y-m-d H:i:s"),
                                'updated_at' => date("Y-m-d H:i:s"),
                            );
                            DB::table($this->table_tambahan)->insert($dataTambahan);
                        }
                    }
                }

                $harga_transaksi = DB::table($this->table_detail)
                    // ->select(DB::raw('DISTINCT(layanan_id), harga'))
                    ->select(DB::raw('layanan_id, harga_fix'))
                    ->where('transaksi_id', $request->id)
                    ->get();

                foreach ($harga_transaksi as $harga) {
                    $total_harga += $harga->harga_fix;
                }

                $harga_transaksi_tambahan = DB::table($this->table_tambahan)
                    // ->select(DB::raw('DISTINCT(layanan_id), harga'))
                    ->select(DB::raw('price'))
                    ->where('transaksi_id', $request->id)
                    ->get();

                foreach ($harga_transaksi_tambahan as $price) {
                    $total_harga += $price->price;
                }

                $transId = DB::table($this->table)->where('id', $request->id)->update([
                    'total_biaya' => $total_harga,
                    'hutang_biaya' => $total_harga - $request->dp,
                    'created_at' => date("Y-m-d H:i:s"),
                ]);

                $dataRekam = array();
                if (!empty($_POST['rekam'])) {
                    $cekRekam = DB::table($this->table_rekam)->where('transaksi_id', $request->id)->count();

                    $dRekam = DB::table('rekam_medik')->where('status', 1)->get();

                    foreach ($dRekam as $p) {
                        $Name[$p->id] = null;

                        if (!empty($_POST['rekam'][$p->id])) {
                            if (is_array($_POST['rekam'][$p->id]) && count($_POST['rekam'][$p->id]) > 0) {
                                foreach ($_POST['rekam'][$p->id] as $rekam) {
                                    $Name[$p->id] .= $rekam;
                                }
                                $Name[$p->id] = $Name[$p->id];
                            } else {
                                $Name[$p->id] = $_POST['rekam'][$p->id];
                            }
                        }

                        if ($cekRekam > 0) {

                            $cekRekam_[$p->id] = DB::table($this->table_rekam)
                                ->where('transaksi_id', $request->id)
                                ->where('position', $p->id);

                            if ($cekRekam_[$p->id]->count() > 0) {
                                $cekRekam_[$p->id]->update([
                                    'name' => $Name[$p->id],
                                    'more_keterangan' => empty($request->rekam_more[$p->id]) ? null : $request->rekam_more[$p->id],
                                    'updated_at' => date("Y-m-d H:i:s"),
                                ]);
                            } else {
                                $dataRekamm[$p->id] = [
                                    'transaksi_id' => $request->id,
                                    'position' => $p->id,
                                    'name' => $Name[$p->id],
                                    'more_keterangan' => empty($request->rekam_more[$p->id]) ? null : $request->rekam_more[$p->id],
                                    'created_at' => date("Y-m-d H:i:s"),
                                    'position' => $p->id,
                                ];

                                DB::table($this->table_rekam)->insert($dataRekamm[$p->id]);
                            }
                        }

                        array_push($dataRekam, array(
                            'transaksi_id' => $request->id,
                            'name' => $Name[$p->id],
                            'more_keterangan' => empty($request->rekam_more[$p->id]) ? null : $request->rekam_more[$p->id],
                            'created_at' => date("Y-m-d H:i:s"),
                            'position' => $p->id,
                        ));
                    }

                    if ($cekRekam == 0) {
                        DB::table($this->table_rekam)->insert($dataRekam);
                    }
                }

                if ($transId) {
                    $mess['msg'] = 'Data sukses disimpan' . ($transId == 0 ? ", namun tidak ada perubahan" : " dan diubah");
                    $mess['cd'] = 200;
                } else {
                    $mess['msg'] = 'Data gagal disimpan';
                    $mess['cd'] = 500;
                }
            } else {

                $cekAktif = DB::table($this->table)
                    ->where('id', $request->id)
                    ->whereNotIn('status', [2, 3])->first();

                if (!empty($cekAktif)) {
                    $mess['msg'] = 'Oops, data transaksi ini telah non Aktif!';
                    $mess['cd'] = 500;
                    echo json_encode($mess);
                    die;
                }

                $member = DB::table($this->table_member)->where('user_id', $request->sno_member);

                if ($request->has('email')) {

                    if (!$this->valid_email($request->email) && !empty($request->email)) {
                        $mess['msg'] = 'Cek input Data Email!, silakan masukkan Email yang valid!';
                        $mess['cd'] = 500;
                        echo json_encode($mess);
                        die;
                    }

                    if ($member->count() > 0 and !empty($member->first()->email != $request->email)) {
                        $mess['msg'] = 'Email telah terdaftar sebagai member!.<br>Tidak diperkenankan merubah email!.';
                        $mess['cd'] = 500;
                        echo json_encode($mess);
                        return false;
                    } else {

                        if (!empty($request->email) && !empty($member->first()->user_id)) {
                            $this->rulesEmail($member->first()->user_id);
                        }
                    }
                }

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

                    DB::table($this->table)
                        ->where('id', $request->id)
                        ->update($this->fields($request, $member->first()->user_id));

                    // if ($request->has('paket')) {
                    //     DB::table('transaksi_detail')
                    //         ->where('transaksi_id', $request->id)
                    //         ->whereNotNull('paket_id')
                    //         ->delete();
                    //     foreach ($request->paket as $numP => $pkt) {
                    //         if (!empty($pkt)) {
                    //             $paket_layanan = DB::table('paket_detail')->where('paket_id', $pkt);
                    //             if ($paket_layanan->count() > 0) {
                    //                 $dataDetailIns1 = array();
                    //                 foreach ($paket_layanan->get() as $num => $pl) {
                    //                     $dataDetailIns1[] = array(
                    //                         'transaksi_id' => $request->id,
                    //                         'posisi' => $numP + 1,
                    //                         'paket_id' => $pkt,
                    //                         'layanan_id' => $pl->layanan_id,
                    //                         'pegawai_id' =>
                    //                         empty($request->pkt_layanan_terapis[$numP + 1][$num]) ? null : $request->pkt_layanan_terapis[$numP + 1][$num],
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

                    if ($request->has('layanan') && $request->has('category')) {

                        if (!empty($request->layanan)) {
                            DB::table('transaksi_detail')
                                ->where('transaksi_id', $request->id)
                                ->whereNull('paket_id')
                                ->delete();

                            foreach ($request->layanan as $num => $lay) {
                                if (!empty($lay)) {
                                    $dataDetailIns2 = array();
                                    $dataDetailIns2[] = array(
                                        'transaksi_id' => $request->id,
                                        'layanan_id' => $lay,
                                        'category_id' => empty($request->category[$num]) ? null : $request->category[$num],
                                        'pegawai_id' => empty($request->terapis[$num]) ? null : $request->terapis[$num],
                                        'kuantitas' => null,
                                        'harga' => DB::table('layanan')->where('id', $lay)->first()->harga,
                                        'harga_fix' => empty($request->harga_custom[$num]) ? null : unRupiahFormat($request->harga_custom[$num]),
                                        'created_at' => date("Y-m-d H:i:s"),
                                    );
                                    DB::table($this->table_detail)->insert($dataDetailIns2);
                                }
                            }
                        }
                    }

                    if (!empty($request->layanan_tambahan)) {

                        DB::table('transaksi_tambahan')
                            ->where('transaksi_id', $request->id)
                            ->delete();

                        foreach ($request->layanan_tambahan as $num => $layTam) {
                            if (!empty($layTam)) {
                                $dataTambahan = array();
                                $dataTambahan[] = array(
                                    'transaksi_id' => $request->id,
                                    'name' => $layTam,
                                    'price' => empty($request->harga_tambahan[$num]) ? null : unRupiahFormat($request->harga_tambahan[$num]),
                                    'created_at' => date("Y-m-d H:i:s"),
                                    'updated_at' => date("Y-m-d H:i:s"),
                                );
                                DB::table($this->table_tambahan)->insert($dataTambahan);
                            }
                        }
                    }

                    $harga_transaksi = DB::table($this->table_detail)
                        // ->select(DB::raw('DISTINCT(layanan_id), harga'))
                        ->select(DB::raw('layanan_id, harga_fix'))
                        ->where('transaksi_id', $request->id)
                        ->get();

                    foreach ($harga_transaksi as $harga) {
                        $total_harga += $harga->harga_fix;
                    }

                    $harga_transaksi_tambahan = DB::table($this->table_tambahan)
                        // ->select(DB::raw('DISTINCT(layanan_id), harga'))
                        ->select(DB::raw('price'))
                        ->where('transaksi_id', $request->id)
                        ->get();

                    foreach ($harga_transaksi_tambahan as $price) {
                        $total_harga += $price->price;
                    }

                    $transId = DB::table($this->table)->where('id', $request->id)->update([
                        'total_biaya' => $total_harga,
                        'hutang_biaya' => $total_harga - $request->dp,
                        'created_at' => date("Y-m-d H:i:s"),
                    ]);

                    $dataRekam = array();
                    if (!empty($_POST['rekam'])) {
                        $cekRekam = DB::table($this->table_rekam)->where('transaksi_id', $request->id)->count();

                        $dRekam = DB::table('rekam_medik')->where('status', 1)->get();

                        foreach ($dRekam as $p) {
                            $Name[$p->id] = null;

                            if (!empty($_POST['rekam'][$p->id])) {
                                if (is_array($_POST['rekam'][$p->id]) && count($_POST['rekam'][$p->id]) > 0) {
                                    foreach ($_POST['rekam'][$p->id] as $rekam) {
                                        $Name[$p->id] .= $rekam;
                                    }
                                    $Name[$p->id] = $Name[$p->id];
                                } else {
                                    $Name[$p->id] = $_POST['rekam'][$p->id];
                                }
                            }

                            if ($cekRekam > 0) {

                                $cekRekam_[$p->id] = DB::table($this->table_rekam)
                                    ->where('transaksi_id', $request->id)
                                    ->where('position', $p->id);

                                if ($cekRekam_[$p->id]->count() > 0) {
                                    $cekRekam_[$p->id]->update([
                                        'name' => $Name[$p->id],
                                        'more_keterangan' => empty($request->rekam_more[$p->id]) ? null : $request->rekam_more[$p->id],
                                        'updated_at' => date("Y-m-d H:i:s"),
                                    ]);
                                } else {
                                    $dataRekamm[$p->id] = [
                                        'transaksi_id' => $request->id,
                                        'position' => $p->id,
                                        'name' => $Name[$p->id],
                                        'more_keterangan' => empty($request->rekam_more[$p->id]) ? null : $request->rekam_more[$p->id],
                                        'created_at' => date("Y-m-d H:i:s"),
                                        'position' => $p->id,
                                    ];

                                    DB::table($this->table_rekam)->insert($dataRekamm[$p->id]);
                                }
                            }

                            array_push($dataRekam, array(
                                'transaksi_id' => $request->id,
                                'name' => $Name[$p->id],
                                'more_keterangan' => empty($request->rekam_more[$p->id]) ? null : $request->rekam_more[$p->id],
                                'created_at' => date("Y-m-d H:i:s"),
                                'position' => $p->id,
                            ));
                        }

                        if ($cekRekam == 0) {
                            DB::table($this->table_rekam)->insert($dataRekam);
                        }
                    }

                    $mess['msg'] = 'Data sukses disimpan' . ($transId == 0 ? ", namun tidak ada perubahan" : " dan diubah");
                    $mess['cd'] = 200;
                } else {
                    $mess['msg'] = 'Data gagal disimpan';
                    $mess['cd'] = 500;
                }
            }
            echo json_encode($mess);
        });
    }

    public function activations($idTrans = false)
    {
        // $optionBuilder = new OptionsBuilder();
        // $optionBuilder->setTimeToLive(60 * 20);

        // $message = array(
        //     'msg'   => "Persediaan Terbatas",
        //     'image'     => "http://xplorin.id/api/files/medium-1c2a6f855bdba2bef690102b8b367675312e1299.jpg",
        // );

        // $notificationBuilder = new PayloadNotificationBuilder('Test');
        // $notificationBuilder->setBody($message)->setSound('default');

        // $dataBuilder = new PayloadDataBuilder();
        // $dataBuilder->addData(['data' => 'my_data']);

        // $option = $optionBuilder->build();
        // $notification = $notificationBuilder->build();
        // $data = $dataBuilder->build();

        // $UpdateTransStatus = DB::table($this->table)->where('member_id', auth()->user()->member_id)->update([
        //     'status' => 2
        // ]);

        // $token = auth()->user()->fcm_tokens;
        // $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

        $gbr = DB::table('layanan')->get()->first()->gambar;
        $gambar_ = empty($gbr) ? asset('/images/noimage.jpg') : asset('/s-home/master-data/package/uploads/' . $gbr);

        $message = array(
            'msg'   => "Selamat, Reservasi Anda telah diaktivasi",
            'image'     => $gambar_,
        );

        $id = auth()->user()->fcm_tokens;
        $API_ACCESS_KEY = \config('fcm.server_key');

        $data = array(
            'body'      => $message,
            'title'     => "Status Reservasi",
            'vibrate'   => 1,
            'sound'     => 1,
        );

        $fields = array(
            'registration_ids'  => array($id),
            'data'              => $data
        );

        $headers = array(
            'Authorization: key=' . $API_ACCESS_KEY,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);
        curl_close($ch);

        $updateTrans = DB::table($this->table)->where('id', $idTrans)
            ->update([
                'status' => 2
            ]);

        return response()->json([
            'code' => 200,
            'message' => 'berhasil mengirimkan token dan mengaktivasi reservasi',
            'data' => $result
        ]);
    }

    public function _data()
    {
        return view('monitoring.order.content.data.table');
    }

    public function _json(Request $request)
    {
        if (request()->ajax()) {
            $data = DB::table($this->table)
                ->leftJoin(
                    $this->table_member,
                    $this->table_member . '.user_id',
                    '=',
                    $this->table . '.member_id'
                )
                ->select(
                    DB::raw("IF(DATE("
                        . $this->table . ".waktu_reservasi) = '"
                        . DATE('Y-m-d')
                        . "', IF((date_add(TIME("
                        . $this->table
                        . ".waktu_reservasi),interval 1 day) < '"
                        . DATE('H:i')
                        . "') AND (transaksi.print_act is null OR transaksi.print_act = 0), 'lebih',''),'') as button_aktif"),
                    $this->table . '.id',
                    $this->table . '.no_transaksi',
                    $this->table . '.waktu_reservasi',
                    $this->table . '.total_biaya',
                    $this->table . '.hutang_biaya',
                    $this->table . '.agent',
                    $this->table . '.status',
                    $this->table . '.print_act',
                    $this->table_member . '.nama'
                );

            if (!empty(session('cabang_session'))) {
                $data->where($this->table . '.lokasi_id', session('cabang_session'));
            }

            if (!empty(session('cabang_id'))) {
                $data->where($this->table . '.lokasi_id', base64_decode(session('cabang_id')));
            }

            if (!empty($request->starts) && !empty($request->ends)) {
                $data->whereBetween(
                    DB::raw('DATE(transaksi.waktu_reservasi)'),
                    [$request->starts, $request->ends]
                );
            }

            if (!empty($request->statuses)) {
                $data->where('transaksi.status', $request->statuses);
            }

            $data->where($this->table . '.status_pembayaran', 'pendaftaran')
                ->orderBy($this->table . '.no_transaksi', 'DESC')
                ->orderBy($this->table . '.created_at', 'DESC')
                ->get();

            return datatables()->of($data)
                ->addColumn('status_text', 'monitoring.order.content.data.status')
                ->addColumn('agent', 'monitoring.order.content.data.agent')
                ->addColumn('action', 'monitoring.order.content.data.action_button')
                ->rawColumns(['status_text', 'agent', 'action'])
                ->addIndexColumn()
                ->make(true);
        }
    }
}
