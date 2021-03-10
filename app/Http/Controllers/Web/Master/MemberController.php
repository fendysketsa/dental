<?php

namespace App\Http\Controllers\Web\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_encode;
use App\Models\MemberModel;

class MemberController extends Controller
{
    protected $table = 'member';
    protected $table_users = 'users';

    private $dir = 'app/public/master-data/member/uploads/';
    private $validate_message = [
        'nama' => 'required',
        'telepon' => 'required',
        'alamat' => 'required',
        'jenis_kelamin' => 'required',
        'foto' => 'image|mimes:jpeg,png,jpg|max:2048',
    ];

    private $validate_message_users = [
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8'
    ];

    public function fields($request, $foto = false, $idUser = false)
    {
        $data_add = !empty($foto) ? ['foto' => $foto] : ['foto' => null];
        $data = [
            'user_id' => $idUser ? $idUser : null,
            'nama' => $request->nama,
            'no_member' => $request->no_member,
            'nama_panggilan' => $request->nama_panggilan,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tgl_lahir' => date("Y-m-d", strtotime($request->tanggal_lahir)),
            'alamat' => $request->alamat,
            'domisili' => $request->domisili,
            'email' => $request->email ? $request->email : '-',
            'telepon' => $request->telepon,
            'media_sosial' => $request->media_sosial,
            'saldo' => $request->saldo ? $request->saldo : 0,
        ];
        return array_merge($data_add, $data);
    }

    public function rules_mail($id)
    {
        return [
            'email' => 'required|email|unique:users,email,' . $id,
        ];
    }

    public function rules_no_member($id)
    {
        return [
            'no_member' => 'required|unique:member,no_member,' . $id,
        ];
    }

    public function fields_user($request)
    {
        $addPass =   !empty($request->password) ? array(
            'password' => $request->password
        ) : array();
        $data = [
            'name' => $request->nama,
            'email' => $request->email,
        ];

        return array_merge($addPass, $data);
    }

    public function fields_user_update($request)
    {
        $data_nama = array('name' => $request->nama);
        $data_email = !empty($request->email) ? array('email' => $request->email) : [];
        $data_pass = !empty($request->password) ? array('password' => Hash::make($request->password)) : [];

        return array_merge($data_nama, $data_email, $data_pass);
    }

    public function validated($mess, $request)
    {
        $mess = null;
        $message = ($request->access == 1 && $request->access != '') ?
            array_merge($this->validate_message, $this->validate_message_users) : $this->validate_message;

        $validator = \Validator::make($request->all(), $message);
        $this->m_message($validator);
    }

    public function m_message($validator)
    {
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
        return view('master-data.member.index', [
            'action' => route('members.store'),
            'autoNom' => MemberModel::getAutoNoMember(),
            'js' => ['s-home/master-data/member/js/member.js'],
            'attribute' => [
                'm_data' => 'true',
                'menu_member' => 'active menu-open',
                'title_bc' => 'Master Data - Pelanggan',
                'desc_bc' => 'Digunakan untuk media menampilkan, menambah, mengubah dan menghapus pelanggan',
            ]
        ]);
    }

    public function store(Request $request)
    {
        $mess = null;
        $filename = null;

        if (!empty($request->id)) {
            $id = DB::table($this->table)->where('id', $request->id)->first();
            $message = (!empty($request->access) && $request->access == 1) ?
                array_merge($this->validate_message, $this->rules_mail($id->user_id)) : array_merge($this->rules_no_member($id->id), $this->validate_message);

            $validator = \Validator::make($request->all(), array_merge($this->rules_no_member($id->id), $message));
            $this->m_message($validator);
        } else {
            $validator = $this->validated($mess, $request);
        }

        if ($request->hasFile('foto') == 1) {
            $extension = $request->file('foto')->getClientOriginalExtension();
            if (!empty($request->id)) {
                $image = DB::table($this->table)->where('id', $request->id)->first()->foto;
                File::delete(storage_path($this->dir) . $image);
            }
            $filename = uniqid() . '_' . time() . '.' . $extension;
            $request->file('foto')->move(storage_path($this->dir), $filename);
        } else {
            if (!empty($request->id)) {
                if (empty($request->old_img) && $request->old_img == '') {
                    $image = DB::table($this->table)->where('id', $request->id)->first()->foto;
                    File::delete(storage_path($this->dir) . $image);
                } else {
                    $filename = $request->old_img;
                }
            }
        }


        if (empty($request->id)) {
            switch ($request->access) {
                case '':
                    $tambah = DB::table($this->table)->insert($this->fields($request));
                    if ($tambah) {
                        $mess['msg'] = 'Data sukses ditambahkan';
                        $mess['cd'] = 200;
                    } else {
                        $mess['msg'] = 'Data gagal ditambahkan';
                        $mess['cd'] = 500;
                    }
                    break;

                default:
                    $idUser = DB::table($this->table_users)->insertGetId($this->fields_user($request));
                    $tambah = DB::table($this->table)->insert($this->fields($request, $filename, $idUser));

                    if ($tambah) {
                        $mess['msg'] = 'Data sukses ditambahkan';
                        $mess['cd'] = 200;
                    } else {
                        $mess['msg'] = 'Data gagal ditambahkan';
                        $mess['cd'] = 500;
                    }
                    break;
            }
        }

        if (!empty($request->id)) {
            switch ($request->access) {
                case '':
                    try {
                        $elmTable = DB::table($this->table)->where('id', $request->id);
                        $uId = $elmTable->first()->user_id;

                        if (empty($request->email)) {
                            DB::table($this->table_users)->where('id', $uId)->delete();
                        }

                        $affected = $elmTable->update($this->fields($request, $filename));
                        $mess['msg'] = 'Data sukses disimpan' . ($affected == 0 ? ", namun tidak ada perubahan" : " dan diubah");
                        $mess['cd'] = 200;
                    } catch (Exception $ex) {
                        $mess['msg'] = 'Data gagal disimpan' . $ex;
                        $mess['cd'] = 500;
                    }
                    break;
                default:
                    try {
                        $elmTable = DB::table($this->table)->where('id', $request->id);
                        $uId = $elmTable->first()->user_id;

                        $affected_user = 0;
                        if (!empty($uId)) {
                            $affected_user = DB::table($this->table_users)->where('id', $uId)->update($this->fields_user_update($request));
                        } else {
                            if (!empty($request->email)) {
                                $uId = DB::table($this->table_users)->insertGetId($this->fields_user($request));
                            }
                        }

                        $affected = $elmTable->update($this->fields($request, $filename, $uId));

                        $mess['msg'] = 'Data sukses disimpan' . ($affected == 0 && $affected_user == 0 ? ", namun tidak ada perubahan" : " dan diubah");
                        $mess['cd'] = 200;
                    } catch (Exception $ex) {
                        $mess['msg'] = 'Data gagal disimpan' . $ex;
                        $mess['cd'] = 500;
                    }
                    break;
            }
        }
        echo json_encode($mess);
    }

    public function update($id)
    {
        if (!empty($id)) {
            $dataE = DB::table($this->table)
                ->leftJoin($this->table_users, $this->table . '.user_id', '=', $this->table_users . '.id')
                ->select($this->table . '.*', $this->table_users . '.email')
                ->where($this->table . '.id', $id)->get()->last();

            if (!$dataE) {
                return abort(404);
            }
            $data = [
                'action' => route('members.store'),
                'autoNom' => MemberModel::getAutoNoMember(),
                'dataE' => $dataE,
            ];
            return view('master-data.member.content.form.modal.form', $data);
        }
    }

    public function _data()
    {
        return view('master-data.member.content.data.table');
    }

    public function _json()
    {
        if (request()->ajax()) {
            return datatables()->of(DB::table($this->table)
                ->select(
                    DB::raw("(SELECT COUNT(*) FROM transaksi WHERE member_id = " . $this->table . ".id) as in_member_use"),
                    $this->table . '.id',
                    $this->table . '.foto',
                    $this->table . '.no_member',
                    $this->table . '.referal_code',
                    $this->table . '.nama',
                    $this->table . '.email',
                    $this->table . '.telepon',
                    DB::raw('IF(saldo, saldo, 0) as saldo')
                )
                ->orderBy($this->table . '.id', 'DESC')
                ->get())
                ->addColumn('action', 'master-data.member.content.data.action_button')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function destroy($id)
    {
        $mess = null;
        $idUser = DB::table($this->table)->where('id', $id);

        if (!empty($idUser->get()->last()->user_id)) {
            DB::table($this->table_users)->where('id', $idUser->get()->last()->user_id)->delete();
        }

        $image = $idUser->first()->foto;
        if (!empty($image)) {
            File::delete(storage_path($this->dir) . $image);
        }

        $hapus = $idUser->delete();

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
