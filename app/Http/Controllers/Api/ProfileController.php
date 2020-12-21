<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\MemberModel;
use App\Models\ReviewModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $dir = 'storage/master-data/member/uploads/';
    protected $false = 0;
    public function index()
    {
        $data_ = MemberModel::Join('users', 'users.id', '=', 'member.user_id')
            ->select('member.id as id_member', DB::raw("CONCAT('" . asset($this->dir) . "', '/', member.foto" . ") as foto"), 'users.name', 'users.email', 'users.email_verified_at', 'users.created_at', 'users.updated_at', 'member.jenis_kelamin as gender', 'member.telepon as phone', 'member.status as member_status', 'member.no_member as no_member')
            ->where('users.id', auth()->user()->id)
            ->where('member.status', 2)
            ->get();

        return response()->json([
            'code' => !empty($data_[0]) ? 200 : 500,
            'message' => !empty($data_[0]) ? 'Berhasil ambil data profile' : 'Silakan aktivasi akun Anda!',
            'data' => !empty($data_[0]) ? $data_ : null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store_upload(Request $request)
    {
        $member = new MemberModel;
        DB::transaction(function () use ($request, $member) {
            $foto_profile = null;
            if ($request->has('foto')) {
                $this->validate($request, [
                    'foto' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ]);

                $foto = DB::table('member')->where('status', 2)->where('user_id', auth()->user()->id)->first()->foto;
                File::delete($this->dir . $foto);

                $image = $request->file('foto');
                $input['imagename'] = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = $this->dir;
                $image->move($destinationPath, $input['imagename']);
                $foto_profile = $input['imagename'];

                $member->where('status', 2)->where('user_id', auth()->user()->id)->update([
                    'foto' => $foto_profile
                ]);
            }
        });

        $data = MemberModel::Join('users', 'users.id', '=', 'member.user_id')
            ->select('member.id as id_member', DB::raw("CONCAT('" . asset('/s-home/master-data/member/uploads') . "', '/', member.foto" . ") as foto"), 'users.name', 'users.email', 'users.email_verified_at', 'users.created_at', 'users.updated_at', 'member.jenis_kelamin as gender', 'member.telepon as phone')
            ->where('users.id', auth()->user()->id)
            ->where('member.status', 2)
            ->get();

        return response()->json([
            'code' => 200,
            'message' => 'Berhasil upload data',
            'data' => $data,
        ]);
    }

    public function updateFCM(Request $request)
    {
        $user = auth()->user();
        $user->fcm_tokens = $request->fcm_token;
        $user->save();

        return response()->json([
            'code' => 200,
            'message' => 'Berhasil update fcm token',
            'data' => $user,
        ]);
    }


    public function update(Request $request)
    {
        $request->user()->forceFill([
            'name' => $request->name,
        ])->save();

        MemberModel::where('user_id', auth()->user()->id)
            ->update([
                'nama' => $request->name,
                'jenis_kelamin' => $request->gender,
                'telepon' => $request->phone,
            ]);

        return response()->json([
            'code' => 200,
            'message' => 'Berhasil update data',
            'data' => MemberModel::Join('users', 'users.id', '=', 'member.user_id')
                ->select('member.id as id_member', DB::raw("CONCAT('" . asset('/s-home/master-data/member/uploads') . "', '/', member.foto" . ") as foto"), 'users.name', 'users.email', 'users.email_verified_at', 'users.created_at', 'users.updated_at', 'member.jenis_kelamin as gender', 'member.telepon as phone')
                ->where('users.id', auth()->user()->id)
                ->where('member.status', 2)
                ->get(),
        ]);
    }

    public function review(Request $request, ReviewModel $reviewModel)
    {

        DB::transaction(function () use ($request, $reviewModel) {

            $cek = $reviewModel->where('layanan_id', $request->layanan_id)
                ->where('member_id', auth()->user()->id)->first();

            if (empty($cek)) {
                $reviewModel->forceFill([
                    'star' => $request->star,
                    'member_id' => auth()->user()->id,
                    'layanan_id' => $request->layanan_id,
                    'keterangan' => $request->keterangan,
                ])->save();
            } else {
                $this->false += 1;
            }
        });

        return response()->json([
            'code' => $this->false == 0 ? 200 : 500,
            'message' => $this->false == 0 ? 'Berhasil mereview data layanan' . $this->false : 'Sudah pernah mereview data layanan',
            'data' => ReviewModel::leftJoin('member', 'member.id', '=', 'layanan_review.member_id')
                ->leftJoin('layanan', 'layanan.id', '=', 'layanan_review.layanan_id')
                ->where('member_id', auth()->user()->id)
                ->select(
                    'member.nama',
                    'layanan.nama as layanan',
                    'layanan_review.star',
                    'layanan_review.keterangan',
                    'layanan_review.created_at as tanggal'
                )->first(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}