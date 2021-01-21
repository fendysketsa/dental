<?php

namespace App\Http\Controllers\Web\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_encode;
use Illuminate\Support\Facades\File as DeleteFile;

class ProfileController extends Controller
{
    protected $table = 'users';
    protected $table_pegawai = 'pegawai';

    private $dir = 'app/public/master-data/employee/uploads/';
    private $validate_message = [
        'name' => 'required',
    ];

    private $validate_password = [
        'password' => 'same:confirm_password|min:8',
        'confirm_password' => 'sometimes|required_with:password'
    ];

    public function fields($request)
    {
        $addData = (!empty($request->password) && !empty($request->confirm_password)) ?
            [
                'password' => Hash::make($request->password)
            ] : [];

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        return array_merge($addData, $data);
    }

    public function rules_mail($id)
    {
        return [
            'email' => 'required|email|unique:users,email,' . $id,
        ];
    }

    public function validated($mess, $request)
    {
        $vMes = null;
        if (!empty($request->password) or !empty($request->confrim_password)) {
            $vMes = array_merge($this->validate_message, $this->rules_mail(auth()->user()->id), $this->validate_password);
        } else {
            $vMes = array_merge($this->validate_message, $this->rules_mail(auth()->user()->id));
        }

        $validator = \Validator::make($request->all(), $vMes);
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
        return view('setting.profile.index', [
            'action' => route('profiles.store'),
            'js' => [
                's-home/setting/profile/js/profile.js',
            ],
            'attribute' => [
                'title_bc' => 'User Profiles',
                'desc_bc' => 'Digunakan untuk media menampilkan detail dan mengubah user',
            ]
        ]);
    }

    public function _detail()
    {
        return view('setting.profile.content.detail.detail');
    }

    public function create()
    {
        $data = DB::table($this->table)
            ->where('id', auth()->user()->id)
            ->first();
        return view('setting.profile.content.form.form', [
            'action' => route('profiles.store'),
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $mess = null;
        $roles = array('?', 'Super Admin', 'Finance', '?', 'Kasir', 'Owner');
        $this->validated($mess, $request);

        try {
            $dataPegawai = DB::table($this->table_pegawai)->where('user_id', auth()->user()->id);
            $affected = $dataPegawai->update([
                'nama' => $request->name
            ]);

            $dataUser = DB::table($this->table)->where('id', auth()->user()->id);
            $affected_ = $dataUser->update($this->fields($request));

            $mess['msg'] = 'Data sukses disimpan' . (($affected == 0 && $affected_ == 0) ? ", namun tidak ada perubahan" : " dan diubah");
            $mess['cd'] = 200;

            if (!empty($dataPegawai->first())) {
                $mess['data'] = [
                    'nama' => $dataPegawai->first()->nama,
                    'jabatan' => $dataPegawai->first()->jabatan,
                    'foto' => $dataPegawai->first()->foto,
                    'role' => $roles[$dataPegawai->first()->role],
                    'email' => DB::table($this->table)->where('id', auth()->user()->id)->first()->email,
                ];
            } else {
                $jabatan = explode("-", auth()->user()->getRoleNames()[0]);

                $mess['data'] = [
                    'nama' => $dataUser->first()->name,
                    'email' => $dataUser->first()->email,
                    'jabatan' => count($jabatan) > 1 ? 'Administrator' : 'Pegawai',
                    'role' => ucwords(str_replace("-", " ", auth()->user()->getRoleNames()[0])),
                ];
            }
        } catch (Exception $ex) {
            $mess['msg'] = 'Data gagal disimpan' . $ex;
            $mess['cd'] = 500;
        }
        echo json_encode($mess);
    }

    public function _upload(Request $request)
    {
        $image = $request->photo;  // your base64 encoded
        $image = str_replace('data:image/jpeg;base64,', '', $image);
        $image = str_replace('data:image/jpg;base64,', '', $image);
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName =  'foto-profile-user-' . auth()->user()->id . '.png';
        if ($image != "") {
            if (!empty(auth()->user()->id)) {
                $Pegawai = DB::table($this->table_pegawai)->where('user_id', auth()->user()->id);

                if (!empty($Pegawai)) {
                    $image_ = $Pegawai->first();
                    if (!empty($image_->foto)) {
                        DeleteFile::delete(storage_path($this->dir) . $image_);
                    }

                    $up = \Storage::disk('profile')->put($imageName, base64_decode($image));
                    $Pegawai->update([
                        'foto' => $imageName,
                    ]);
                }

                if ($up) {
                    $mess['status'] = 1;
                } else {
                    $mess['status'] = 0;
                }

                echo json_encode($mess);
            }
        }
    }
}
