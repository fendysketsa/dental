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
use Illuminate\Support\Facades\File;

class ReservationPaketController extends Controller
{
    protected $table_detail = 'transaksi_detail';

    public function index(Request $request)
    {
        $action_cancel = Input::get('action');
        if (!empty($action_cancel) && $action_cancel == 'cancel' && !empty($request->transaksi)) {

            DB::transaction(function () use ($request) {
                $dataCek = ReservationModel::where('member_id', $request->member)
                    ->where('member_id', $request->transaksi_id);
                if ($dataCek->first()->status != 4) {
                    $dataCek->update([
                        'status' => 3
                    ]);
                }
            });
        }

        if (!empty($request->transaksi_id)) {
            $dataON = DB::table('transaksi')
                ->select('transaksi.*', DB::raw('IF(transaksi.status = 2, "true", "false") as status_aktifasi'))
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

            return [
                'code' => 200,
                'message' => 'Berhasil get data transaksi paket',
                'data' => [
                    'transaksi' => $dataON,
                    'transaksi_detail' => [
                        'paket' => $dataDetailPaket,
                        'paket_detail' => $dataDetailLayanan,
                    ],
                    'lokasi' => $dataDetailLokasi,
                ]
            ];
        }

        $action_proses = Input::get('process');
        if (!empty($action_proses)) {
            $process = $action_proses == 'on-going' ? 1 : ($action_proses == 'unfinished' ? 3 : 2);
            return new ReservationCollection(ReservationModel::leftJoin('lokasi', 'lokasi.id', '=', 'transaksi.lokasi_id')
                ->where('member_id', $request->member)
                ->where('status', $process)
                ->select('transaksi.*', 'lokasi.nama as nama_lokasi')
                ->get());
        }

        return new ReservationCollection(ReservationModel::where('member_id', $request->member)->get());
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
                'jumlah_orang' => 1,
                'bank_id' => $request->bank_id
            ]);
            $transaksi->fill($request->all());
            $transaksi->save();
            if ($request->has('paket_id')) {
                $layanan = DB::table('paket_detail')
                    ->leftJoin('paket', 'paket.id', '=', 'paket_detail.paket_id')
                    ->where('paket_id', $request->paket_id)->get();
                foreach ($layanan as $numP => $fills) {
                    $transaksiDetail = new ReservationDetailModel();
                    $transaksiDetail->posisi = $numP + 1;
                    $transaksiDetail->paket_id = $request->paket_id;
                    $transaksiDetail->layanan_id = $fills->layanan_id;
                    $transaksiDetail->transaksi_id = $transaksi->id;
                    $transaksiDetail->harga = ($request->total_biaya / count($layanan));
                    $transaksiDetail->save();
                }
            }
        });

        return response()->json([
            'code' => 200,
            'message' => 'Berhasil simpan data transaksi paket',
            'data' => ReservationModel::where('member_id', $request->member_id)
                ->where('id', $transaksi->id)
                ->first()
        ]);
    }
}
