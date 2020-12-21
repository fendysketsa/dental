<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction\TransaksiModel;
use App\Models\Api\TerapistModel;
use App\Models\LayananModel;
use App\Models\CabangModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Api\ReservationModel;
use App\Api\ReservationDetailModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Http\Resources\ReservationCollection;
use App\Http\Resources\ReservationScheduleCollection;
use Illuminate\Support\Facades\File;

class ReservationController extends Controller
{
    protected $table_detail = 'transaksi_detail';

    public function index(Request $request)
    {
        $action_cancel = Input::get('action');
        if (!empty($action_cancel) && $action_cancel == 'cancel' && !empty($request->transaksi)) {

            DB::transaction(function () use ($request) {
                $dataCek = ReservationModel::where('member_id', $request->member)
                    ->where('id', $request->transaksi);

                if ($dataCek->count() > 0) {
                    if ($dataCek->first()->status != 1) {
                        $dataCek->update([
                            'status' => 1,
                        ]);

                        // $dataUpdate = DB::table('transaksi_detail')->where('transaksi_id', $request->transaksi);
                        // $dataUpdate->update([
                        //     'pegawai_id' => NULL,
                        // ]);
                    }
                }
            });
        }

        if (!empty($request->transaksi_id)) {
            $dataON = DB::table('transaksi')
                ->select(
                    'transaksi.*',
                    DB::raw('IF(transaksi.status = 2, "true", "false") as status_aktifasi'),
                    DB::raw('IF(transaksi.status = 2, "ON GOING", IF(transaksi.status = 3, "FINISHED", IF(transaksi.status = 1, "CANCELED", "NON ACTIVED"))) as status_text')
                )
                ->where('transaksi.id', $request->transaksi_id)->get()->first();

            $dataDetailNonPaket = DB::table('transaksi_detail')
                ->whereNull('paket_id')
                ->groupBy('layanan_id')
                ->where('transaksi_id', $request->transaksi_id)->get();

            $dataDetailPaket = DB::table('transaksi_detail')
                ->whereNotNull('paket_id')
                ->groupBy('layanan_id')
                ->where('transaksi_id', $request->transaksi_id)->get();

            $dataDetailLokasi = DB::table('transaksi')
                ->LeftJoin('lokasi', 'lokasi.id', '=', 'transaksi.lokasi_id')
                ->LeftJoin('cabang', 'cabang.id', '=', 'lokasi.cabang_id')
                ->select('lokasi.nama as lokasi', 'cabang.nama as cabang')
                ->where('transaksi.id', $request->transaksi_id)->get();

            $dataDetailLayanan = DB::table('transaksi_detail')
                ->LeftJoin('layanan', 'layanan.id', '=', 'transaksi_detail.layanan_id')
                ->select('layanan.*')
                ->groupBy('layanan.id')
                ->where('transaksi_detail.transaksi_id', $request->transaksi_id)->get();

            $dataDetailTerapis = DB::table('transaksi_detail')
                ->LeftJoin('pegawai', 'pegawai.id', '=', 'transaksi_detail.pegawai_id')
                ->select('pegawai.*', DB::raw("CONCAT('" . asset('/s-home/master-data/employee/uploads') . "', '/', pegawai.foto" . ") as foto_terapis"))
                ->where('pegawai.role', 3)
                ->groupBy('pegawai.id')
                ->where('transaksi_detail.transaksi_id', $request->transaksi_id)->get();

            return [
                'code' => 200,
                'message' => 'Berhasil get data transaksi',
                'data' => [
                    'transaksi' => $dataON,
                    'transaksi_detail' => [
                        'paket' => $dataDetailPaket,
                        'non-paket' => $dataDetailNonPaket
                    ],
                    'lokasi' => $dataDetailLokasi,
                    'layanan' => [
                        'detail_layanan' => $dataDetailLayanan,
                        'detail_terapis' => $dataDetailTerapis,
                    ]

                ]
            ];
        }

        $action_proses = Input::get('process');
        if (!empty($action_proses)) {
            $process = $action_proses == 'on-going' ? 2 : ($action_proses == 'unfinished' ? 1 : 3);
            $processUnfin = $action_proses == 'on-going' ? 2 : ($action_proses == 'unfinished' ? 4  : 3);
            return new ReservationCollection(ReservationModel::leftJoin('lokasi', 'lokasi.id', '=', 'transaksi.lokasi_id')
                ->where('member_id', $request->member)
                ->whereIn('status', [$process, $processUnfin])
                ->select('transaksi.*', 'lokasi.nama as nama_lokasi')
                ->orderBy('transaksi.id', 'DESC')
                ->get());
        }

        return new ReservationCollection(ReservationModel::where('member_id', $request->member)
            ->where('status', 2)->where('status_pembayaran', 'pendaftaran')->orderBy('id', 'DESC')->get());
    }

    public function _jadwal()
    {
        $data = new ReservationScheduleCollection(
            ReservationModel::leftJoin(
                'member',
                'member.id',
                '=',
                'transaksi.member_id'
            )
                ->select('transaksi.id as transaksi_id')
                ->where('member.user_id', auth()->user()->id)
                ->where('transaksi.status', 2)
                ->where('transaksi.status_pembayaran', 'pendaftaran')
                ->where(DB::raw('DATE(transaksi.waktu_reservasi)'), '>=', DATE('Y-m-d'))
                ->orderBy('transaksi.created_at', 'DESC')
                ->get()
        );

        foreach ($data as $rw) {
            if (!empty($rw->transaksi_id)) {
                $dataON = DB::table('transaksi')
                    ->select('transaksi.*', DB::raw('IF(transaksi.status = 2, "true", "false") as status_aktifasi'))
                    ->where('transaksi.id', $rw->transaksi_id)->get()->first();

                $dataDetailNonPaket = DB::table('transaksi_detail')
                    ->whereNull('paket_id')
                    ->groupBy('layanan_id')
                    ->where('transaksi_id', $rw->transaksi_id)->get();

                $dataDetailPaket = DB::table('transaksi_detail')
                    ->whereNotNull('paket_id')
                    ->groupBy('layanan_id')
                    ->where('transaksi_id', $rw->transaksi_id)->get();

                $dataDetailLokasi = DB::table('transaksi')
                    ->LeftJoin('lokasi', 'lokasi.id', '=', 'transaksi.lokasi_id')
                    ->LeftJoin('cabang', 'cabang.id', '=', 'lokasi.cabang_id')
                    ->select('lokasi.nama as lokasi', 'cabang.nama as cabang')
                    ->where('transaksi.id', $rw->transaksi_id)->get();

                $dataDetailLayanan = DB::table('transaksi_detail')
                    ->LeftJoin('layanan', 'layanan.id', '=', 'transaksi_detail.layanan_id')
                    ->select('layanan.*')
                    ->groupBy('layanan.id')
                    ->where('transaksi_detail.transaksi_id', $rw->transaksi_id)->get();

                $dataDetailTerapis = DB::table('transaksi_detail')
                    ->LeftJoin('pegawai', 'pegawai.id', '=', 'transaksi_detail.pegawai_id')
                    ->select('pegawai.*', DB::raw("CONCAT('" . asset('/s-home/master-data/employee/uploads') . "', '/', pegawai.foto" . ") as foto_terapis"))
                    ->where('pegawai.role', 3)
                    ->groupBy('transaksi_detail.layanan_id')
                    ->where('transaksi_detail.transaksi_id', $rw->transaksi_id)->get();

                return [
                    'code' => 200,
                    'message' => 'Berhasil ambil schedule member',
                    'data' => [
                        'transaksi' => $dataON,
                        'link_detail' => url('api/reservation?member=' . auth()->user()->id . '&transaksi_id=' . $rw->transaksi_id),
                        'transaksi_detail' => [
                            'paket' => $dataDetailPaket,
                            'non-paket' => $dataDetailNonPaket
                        ],
                        'lokasi' => $dataDetailLokasi,
                        'layanan' => [
                            'detail_layanan' => $dataDetailLayanan,
                            'detail_terapis' => $dataDetailTerapis,
                        ]

                    ]
                ];
            }
        }
    }

    public function store_upload(Request $request)
    {

        $this->validate($request, [
            'bukti' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $image = $request->file('bukti');
        $input['imagename'] = time() . '.' . $image->getClientOriginalExtension();
        $destinationPath = public_path('/images');
        $image->move($destinationPath, $input['imagename']);

        $transaksi = new ReservationModel;
        $data = $transaksi::where('member_id', $request->member_id)
            ->where('id', $request->transaksi_id);

        if (!empty($data->first()->bukti_bayar)) {
            File::delete($destinationPath . '/' . $data->first()->bukti_bayar);
        }

        $data->update([
            'bukti_bayar' => $input['imagename']
        ]);

        return response()->json([
            'code' => 200,
            'message' => 'Berhasil mengupload bukti bayar'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|numeric',
            'jumlah_orang' => 'required|numeric',
            'waktu_reservasi' => 'required',
        ]);

        $transaksi = new ReservationModel;
        DB::transaction(function () use ($request, $transaksi) {
            $image_bukti = null;
            if ($request->has('bukti')) {
                $this->validate($request, [
                    'bukti' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ]);
                $image = $request->file('bukti');
                $input['imagename'] = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/images');
                $image->move($destinationPath, $input['imagename']);
                $image_bukti = $input['imagename'];
            }

            $transaksi->forceFill([
                'no_transaksi' => TransaksiModel::getAutoNoTransaksi(),
                'status' => 2,
                'agent' => 'Android',
                'status_pembayaran' => 'pendaftaran',
                'dp' => 0,
                'bukti_bayar' => $image_bukti,
                'bank_id' => $request->bank_id
            ]);
            $transaksi->fill($request->all());
            $transaksi->save();

            foreach ($request->layanan as $fills) {
                $transaksiDetail = new ReservationDetailModel();
                $transaksiDetail->fill($fills);
                $transaksiDetail->transaksi_id = $transaksi->id;
                $transaksiDetail->save();
            }
        });

        return response()->json([
            'code' => 200,
            'message' => 'Berhasil simpan data transaksi Anda',
            'data' => ReservationModel::where('member_id', $request->member_id)
                ->where('id', $transaksi->id)
                ->first()
        ]);
    }
}
