<?php

namespace App\Http\Controllers\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\NotifMessageMember;
use App\Models\LogStokProdukModel;

class PaymentCashierController extends Controller
{
    protected $table = 'transaksi';
    protected $table_detail = 'transaksi_detail';
    protected $table_tambahan = 'transaksi_tambahan';
    protected $table_room = 'room';
    protected $table_produk = 'produk';
    protected $table_member = 'member';
    protected $table_diskon = 'diskon';

    protected $validate_message = [
        'cara_bayar' => 'required|not_in:0',
        'bayar' => 'required|numeric|min:10000'
    ];

    public function index()
    {
        return view('trans.payment.index', [
            'js' => [
                's-home/dist/js/sprintf.js',
                's-home/trans/cashier/js/cashier_form.js',
                's-home/trans/cashier/js/cashier.js',
                's-home/dist/js/recta.js',
                // 'https://cdn.jsdelivr.net/npm/recta/dist/recta.js'
            ],
            'attribute' => [
                'm_payment' => 'true',
                'menu_Cas' => 'active menu-open',
                'title_bc' => 'Kasir',
                'desc_bc' => 'Digunakan untuk media menampilkan transaksi pendaftaran member'
            ],

        ]);
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
                        'status' => 3,
                        'tanggal_bayar' => date('Y-m-d H:i:s'),
                        'status_pembayaran' => 'terbayar'
                    ]);

                $notif_ = new NotifMessageMember;
                $notif_['transaksi_id'] = $request->order_id;
                $notif_['member_id'] = auth()->user()->id;
                $notif_['judul'] = 'Selamat Datang Kembali';
                $notif_['keterangan'] = 'Terimakasih telah menggunakan layanan kami';
                $notif_['read'] = false;
                $notif_['review'] = false;
                $notif_['alreadyReview'] = true;
                $notif_['gambar'] = asset('/images/notification.jpg');
                $notif_['created_at'] = date(now());
                $notif_->save();

                $gambar_ = asset('/images/notification.jpg');

                $message = array(
                    'msg'   => "Terimakasih telah menggunakan layanan kami",
                    'image' => $gambar_,
                );

                $id = auth()->user()->fcm_tokens;
                $API_ACCESS_KEY = \config('fcm.server_key');

                $data = array(
                    'body'    => $message,
                    'title'   => "Selamat Datang Kembali",
                    'vibrate' => 1,
                    'sound'   => 1,
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

                if ($dataOrder) {
                    $mess['msg'] = 'Data sukses disimpan' . ($dataOrder == 0 ? ", namun belum ke proses info pembayaran" : " dan terkirim ke info pembayaran");
                    $mess['cd'] = 200;
                } else {
                    $mess['msg'] = 'Data gagal disimpan';
                    $mess['cd'] = 500;
                }

                echo json_encode($mess);
            });
        }
    }

    public function form_order()
    {
        return view('trans.payment.content.form.form_right_order');
    }
    public function create($id = false)
    {
        $form = Input::get('form');
        if (empty($form)) {
            return view('trans.payment.content.index_form', [
                'action' => 'x',
                'js' => [
                    's-home/trans/cashier/js/cashier_form.js',
                    's-home/trans/cashier/js/cashier.js',
                ],
                'attribute' => [
                    'm_cashier' => 'true',
                    'menu_cashier' => 'active menu-open',
                    'title_bc' => 'Form Kasir',
                    'desc_bc' => 'Digunakan untuk media menginput data member, transaksi layanan, produk, paket'
                ]
            ]);
        }
        return view('trans.payment.content.form.form_' . $form, [
            'action' => route('cashiers.form.store'),
            'attribute' => [
                'm_cashier' => 'true',
                'menu_cashier' => 'active menu-open',
                'title_bc' => 'Form Kasir',
                'desc_bc' => 'Digunakan untuk media menginput data member, transaksi layanan, produk, paket'
            ]
        ]);
    }

    public function update($id)
    {
        if (!empty($id)) :
            $dataTrans = DB::table($this->table)
                ->leftJoin($this->table_room, $this->table_room . '.id', '=', $this->table . '.room_id')
                ->select($this->table . '.*', $this->table_room . '.price as harga_ruangan')
                ->where($this->table . '.id', $id)->get();

            $dataLayanan = DB::table($this->table_detail)
                ->select(
                    DB::raw('GROUP_CONCAT(IF(layanan_id, layanan_id, 0)) as layanan'),
                    DB::raw('GROUP_CONCAT(IF(pegawai_id, pegawai_id, 0)) as terapis')
                )
                ->whereNull('paket_id')
                ->where('transaksi_id', $id)->get();

            $dataLayananTambahan = DB::table($this->table_tambahan)
                ->select('name', 'price')
                ->where('transaksi_id', $id)->get();

            $dataProduk = DB::table($this->table_detail)
                ->select(
                    DB::raw('GROUP_CONCAT(IF(produk_id, produk_id, 0)) as produk'),
                    DB::raw('GROUP_CONCAT(IF(kuantitas, kuantitas, 0)) as jumlah'),
                    DB::raw('GROUP_CONCAT(IF(harga, harga, 0)) as harga')
                )
                ->whereNotNull('produk_id')
                ->where('transaksi_id', $id)->get();

            $dataPaketLayanan = DB::table($this->table_detail)
                ->select(
                    DB::raw('GROUP_CONCAT(IF(pegawai_id, pegawai_id, 0)) as terapis')
                )
                ->whereNotNull('paket_id')
                ->where('transaksi_id', $id)
                ->groupBy('posisi')
                ->orderBy('posisi', 'ASC')
                ->orderBy('id', 'ASC')
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

        return view('trans.payment.content.index_form', [
            'data' => !empty($id) ? $dataTrans : null,
            'services' => !empty($id) ? $dataLayanan : null,
            'services_add' => !empty($id) ? $dataLayananTambahan : null,
            'produk' => !empty($id) ? $dataProduk : null,
            'posisi' => !empty($id) ? $dataPaketLayananPosisi : null,
            'pktservices' => !empty($id) ? $dataPaketLayanan : null,
            'paket' => !empty($id) ? $dataPaket : null,
            'action' => route('cashiers.update', $id)
        ]);
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

    public function store(Request $request, LogStokProdukModel $LogStokProdukModel)
    {
        $mess = null;
        $total_harga = 0;
        $this->validated($mess, $request);

        DB::transaction(function () use ($request, $mess, $total_harga, $LogStokProdukModel) {

            if ($request->has('paket')) {
                DB::table('transaksi_detail')
                    ->where('transaksi_id', $request->id)
                    ->whereNotNull('paket_id')
                    ->delete();
                foreach ($request->paket as $numP => $pkt) {
                    if (!empty($pkt)) {
                        $paket_layanan = DB::table('paket_detail')->where('paket_id', $pkt);
                        if ($paket_layanan->count() > 0) {
                            $dataDetailIns1 = array();
                            foreach ($paket_layanan->get() as $num => $pl) {
                                $dataDetailIns1[] = array(
                                    'transaksi_id' => $request->id,
                                    'posisi' => $numP + 1,
                                    'paket_id' => $pkt,
                                    'layanan_id' => $pl->layanan_id,
                                    'pegawai_id' =>
                                    empty($request->pkt_layanan_terapis[$numP + 1][$num]) ? null : $request->pkt_layanan_terapis[$numP + 1][$num],
                                    'kuantitas' => null,
                                    'harga' => DB::table('paket')->where('id', $pkt)->first()->harga / $paket_layanan->count(),
                                    'created_at' => date("Y-m-d H:i:s"),
                                );
                            }
                            DB::table($this->table_detail)->insert($dataDetailIns1);
                        }
                    }
                }
            }

            if ($request->has('layanan')) {
                DB::table('transaksi_detail')
                    ->where('transaksi_id', $request->id)
                    ->whereNull('paket_id')
                    ->whereNotNull('layanan_id')
                    ->delete();
                foreach ($request->layanan as $num => $lay) {
                    if (!empty($lay)) {
                        $dataDetailIns2 = array();
                        $dataDetailIns2[] = array(
                            'transaksi_id' => $request->id,
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

            if ($request->has('produk')) {
                $Notransaksi = DB::table($this->table)
                    ->select(DB::raw('no_transaksi'))
                    ->where('id', $request->id)
                    ->first()->no_transaksi;

                $dataProdukInDB = DB::table('transaksi_detail')
                    ->where('transaksi_id', $request->id)
                    ->whereNotNull('produk_id')
                    ->select('produk_id', 'kuantitas')
                    ->orderBy('id', 'ASC')->get();

                foreach ($dataProdukInDB as $jumP => $rP) {
                    if ($request->produk[$jumP] != $rP->produk_id) {
                        DB::table('produk')->where('id', $rP->produk_id)->update([
                            'stok' => DB::table('produk')->where('id', $rP->produk_id)->first()->stok + $rP->kuantitas,
                        ]);

                        $data[$num]['produk_id'] = $rP->produk_id;
                        $data[$num]['tanggal'] = date('Y-m-d H:i:s');
                        $data[$num]['masuk'] = $rP->kuantitas;
                        $data[$num]['keluar'] = 0;
                        $data[$num]['sisa'] = DB::table('produk')->where('id', $rP->produk_id)->first()->stok;
                        $data[$num]['keterangan'] = "<strong>Penjualan Produk (Batal)</strong> No Nota: " . $Notransaksi;

                        $LogStokProdukModel->forceFill($data[$num]);
                        $LogStokProdukModel->save();

                        DB::table('transaksi_detail')
                            ->where('transaksi_id', $request->id)
                            ->where('produk_id', $rP->produk_id)
                            ->delete();
                    }
                }

                foreach ($request->produk as $num => $prd) {

                    if (!empty($prd)) {

                        $onStok[$num] = DB::table($this->table_detail)
                            ->where('transaksi_id', $request->id)
                            ->where('produk_id', $prd)->first();

                        if (empty($onStok[$num])) {
                            $dataDetailIns3 = array();
                            $dataDetailIns3[] = array(
                                'transaksi_id' => $request->id,
                                'produk_id' => $prd,
                                'layanan_id' => null,
                                'pegawai_id' => null,
                                'kuantitas' => $request->jml_produk[$num],
                                'harga' => DB::table('produk')->where('id', $prd)->first()->harga_jual_member,
                                'created_at' => date("Y-m-d H:i:s"),
                            );

                            $updateStok = DB::table($this->table_detail)->insert($dataDetailIns3);

                            if ($updateStok) {
                                DB::table('produk')->where('id', $prd)->update([
                                    'stok' => DB::table('produk')->where('id', $prd)->first()->stok - $request->jml_produk[$num],
                                ]);

                                $data[$num]['produk_id'] = $prd;
                                $data[$num]['tanggal'] = date('Y-m-d H:i:s');
                                $data[$num]['masuk'] = 0;
                                $data[$num]['keluar'] = $request->jml_produk[$num];
                                $data[$num]['sisa'] = DB::table('produk')->where('id', $prd)->first()->stok;
                                $data[$num]['keterangan'] = "<strong>Penjualan Produk</strong> No Nota: " . $Notransaksi;

                                $LogStokProdukModel->forceFill($data[$num]);
                                $LogStokProdukModel->save();
                            }
                        } else if (!empty($onStok[$num]) && ($onStok[$num]->kuantitas > $request->jml_produk[$num])) {
                            $updateStok = DB::table($this->table_detail)
                                ->where('transaksi_id', $request->id)
                                ->where('produk_id', $prd)
                                ->update([
                                    'kuantitas' => $request->jml_produk[$num]
                                ]);

                            if ($updateStok) {
                                DB::table('produk')->where('id', $prd)->update([
                                    'stok' => DB::table('produk')->where('id', $prd)->first()->stok + ($onStok[$num]->kuantitas - $request->jml_produk[$num]),
                                ]);

                                $data[$num]['produk_id'] = $prd;
                                $data[$num]['tanggal'] = date('Y-m-d H:i:s');
                                $data[$num]['masuk'] = $onStok[$num]->kuantitas - $request->jml_produk[$num];
                                $data[$num]['keluar'] = $request->jml_produk[$num];
                                $data[$num]['sisa'] = DB::table('produk')->where('id', $prd)->first()->stok;
                                $data[$num]['keterangan'] = "<strong>Penjualan Produk (Update Kuantiti - <i>kurang</i>)</strong> No Nota: " . $Notransaksi;

                                $LogStokProdukModel->forceFill($data[$num]);
                                $LogStokProdukModel->save();
                            }
                        } else if (!empty($onStok[$num]) && ($request->jml_produk[$num] > $onStok[$num]->kuantitas)) {

                            $updateStok = DB::table($this->table_detail)
                                ->where('transaksi_id', $request->id)
                                ->where('produk_id', $prd)
                                ->update([
                                    'kuantitas' => $request->jml_produk[$num]
                                ]);

                            if ($updateStok) {
                                DB::table('produk')->where('id', $prd)->update([
                                    'stok' => DB::table('produk')->where('id', $prd)->first()->stok - ($request->jml_produk[$num] - $onStok[$num]->kuantitas),
                                ]);

                                $data[$num]['produk_id'] = $prd;
                                $data[$num]['tanggal'] = date('Y-m-d H:i:s');
                                $data[$num]['masuk'] = 0;
                                $data[$num]['keluar'] = $request->jml_produk[$num] - $onStok[$num]->kuantitas;
                                $data[$num]['sisa'] = DB::table('produk')->where('id', $prd)->first()->stok;
                                $data[$num]['keterangan'] = "<strong>Penjualan Produk (Update Kuantiti  - <i>tambah</i>)</strong> No Nota: " . $Notransaksi;

                                $LogStokProdukModel->forceFill($data[$num]);
                                $LogStokProdukModel->save();
                            }
                        }
                    }
                }
            }

            $harga_transaksi = DB::table($this->table_detail)
                // ->select(DB::raw('DISTINCT(layanan_id), harga, kuantitas'))
                ->select(DB::raw('layanan_id, harga, kuantitas'))
                ->where('transaksi_id', $request->id)
                ->get();

            foreach ($harga_transaksi as $harga) {
                $total_harga += $harga->kuantitas ? ($harga->kuantitas * $harga->harga) : $harga->harga;
            }

            $transId = DB::table($this->table)->where('id', $request->id)->update([
                'cara_bayar_kasir' => $request->cara_bayar,
                'metode_bayar' => $request->cara_bayar == 2 ? $request->cara_bayar_select : null,
                'kd_kartu' => $request->cara_bayar == 2 ? $request->bank_select : null,
                'no_kartu' => $request->cara_bayar == 2 ? $request->nomor_kartu : null,
                'kembalian' => $request->kembalian,
                'nominal_bayar' => $request->bayar,
                'total_biaya' => $total_harga,
                'hutang_biaya' => $total_harga - $request->dp,
                'diskon' => $request->grand_total,
                'created_at' => date("Y-m-d H:i:s"),
            ]);

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

    public function _detail(Request $request)
    {
        echo json_encode(DB::table($this->table_member)
            ->where('id', $request->id)
            ->get());
    }

    public function _option(Request $request)
    {
        if (empty($request->table)) {
            return abort(403);
        }

        if (in_array($request->table, array($this->table_diskon, 'cara_bayar', 'bank_select', 'cara_bayar_select', 'voucher'))) {
            if ($request->table == 'diskon') {
                $data = DB::table($request->table)->where('berlaku_sampai', '>', date('Y-m-d'))->get();
            }

            if ($request->table == 'voucher') {
                $data = DB::table($request->table)->where('berlaku_sampai', '>', date('Y-m-d'))->get();
            }

            if ($request->table == 'cara_bayar') {
                $data = array(
                    array('id' => 1, 'nama' => 'Pembayaran dengan Cash'),
                    array('id' => 2, 'nama' => 'Pembarayan dengan Card'),
                );
            }

            if ($request->table == 'bank_select') {
                $data = array(
                    array('id' => 1, 'nama' => 'BCA'),
                    array('id' => 2, 'nama' => 'BNI'),
                    array('id' => 3, 'nama' => 'BRI'),
                    array('id' => 4, 'nama' => 'Mandiri'),
                    array('id' => 5, 'nama' => 'Lainnya'),
                );
            }

            if ($request->table == 'cara_bayar_select') {
                $data = array(
                    array('id' => 1, 'nama' => 'Debit'),
                    array('id' => 2, 'nama' => 'Visa'),
                    array('id' => 3, 'nama' => 'Master Card'),
                    array('id' => 4, 'nama' => 'Transfer'),
                );
            }

            echo json_encode($data);
        }
    }

    public function _data()
    {
        return view('trans.payment.content.data.table');
    }

    public function _json(Request $request)
    {
        if (request()->ajax()) {
            $data = DB::table($this->table)
                ->leftJoin($this->table_member, $this->table . '.member_id', '=', $this->table_member . '.user_id')
                ->select($this->table . '.*', $this->table_member . '.no_member as no_member', $this->table_member . '.nama as nama_member');

            if (!empty(session('cabang_id'))) {
                $data->where($this->table . '.lokasi_id', base64_decode(session('cabang_id')));
            }

            if (!empty(session('cabang_session'))) {
                $data->where($this->table . '.lokasi_id', session('cabang_session'));
            }

            if (!empty($request->starts) && !empty($request->ends)) {
                $data->whereBetween(
                    DB::raw('DATE(transaksi.waktu_reservasi)'),
                    [$request->starts, $request->ends]
                );
            }

            $data->where($this->table . '.status', 2)
                ->where('status_pembayaran', 'pembayaran')
                ->orderBy('id', 'DESC')
                ->get();

            return datatables()->of($data)
                ->addColumn('agent', 'trans.payment.content.data.agent')
                ->addColumn('action', 'trans.payment.content.data.action_button')
                ->rawColumns(['action', 'agent'])
                ->addIndexColumn()
                ->make(true);
        }
    }
}
