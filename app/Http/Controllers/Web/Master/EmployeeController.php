<?php

namespace App\Http\Controllers\Web\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_encode;
use Spatie\Permission\Models\Role;
use App\Models\User;

class EmployeeController extends Controller
{
    protected $table = 'pegawai';
    protected $table_branch = 'cabang';
    protected $table_branch_other = 'pegawai_cabang_detail';
    protected $table_category = 'kategori';
    protected $table_users = 'users';
    protected $table_services = 'layanan';
    protected $table_kualifikasi = 'kualifikasi_terapis';
    protected $table_detail_cabang = 'layanan_detail_cabang';

    private $dir = 'app/public/master-data/employee/uploads/';
    private $validate_message = [
        'cabang' => 'required|not_in:0',
        'nama' => 'required',
        'jabatan' => 'required',
        'role' => 'required|not_in:0',
        // 'komisi' => 'numeric',
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
            'cabang_id' => $request->cabang,
            'nama' => $request->nama,
            'role' => $request->role,
            'jabatan' => $request->jabatan,
            'komisi' => $request->komisi,
            'status' => 1,
        ];
        return array_merge($data_add, $data);
    }

    public function rules_mail($id)
    {
        return [
            'email' => 'required|email|unique:users,email,' . $id,
        ];
    }

    public function fields_user($request)
    {
        $addPass =   !empty($request->password) ? array(
            'password' => Hash::make(
                $request->password
            )
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
        $message = ($request->role != 3 && $request->role != '') ?
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
        return view('master-data.employee.index', [
            'js' => ['s-home/master-data/employee/js/employee.js'],
            'attribute' => [
                'm_data' => 'true',
                'menu_employee' => 'active menu-open',
                'title_bc' => 'Master Data - Pegawai',
                'desc_bc' => 'Digunakan untuk media menampilkan, menambah, mengubah dan menghapus pegawai',
            ]
        ]);
    }

    public function store(Request $request)
    {
        $mess = [];
        $filename = null;

        if (!empty($request->id)) {
            $id = DB::table($this->table)->where('id', $request->id)->first()->user_id;
            $message = ($request->role == 3 || $request->role != '') ?
                array_merge($this->validate_message, $this->rules_mail($id)) : $this->validate_message;

            $validator = \Validator::make($request->all(), $message);
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
            switch ($request->role) {
                case '':
                    $tambah = DB::table($this->table)->insert($this->fields($request, $filename));
                    if ($tambah) {
                        $mess['msg'] = 'Data sukses ditambahkan';
                        $mess['cd'] = 200;
                    } else {
                        $mess['msg'] = 'Data gagal ditambahkan';
                        $mess['cd'] = 500;
                    }
                    echo json_encode($mess);
                    break;
                case '3':

                    DB::transaction(function () use ($request, $filename) {
                        $mess_ = null;

                        $idUser = DB::table($this->table_users)->insertGetId($this->fields_user($request));

                        $role = Role::find($request->role);
                        $user = User::find($idUser);
                        $user->assignRole($role);

                        $Id = DB::table($this->table)->insertGetId($this->fields($request, $filename, $idUser));

                        if (!empty($request->kualifikasi)) {
                            $dataKualifikasi = array();
                            foreach ($request->kualifikasi as $num => $lyn) {
                                if (!empty($lyn)) {
                                    $dataKualifikasi[] = array(
                                        'pegawai_id' => $Id,
                                        'layanan_id' => $lyn,
                                        'created_at' => date("Y-m-d H:i:s"),
                                    );
                                }
                            }
                            DB::table($this->table_kualifikasi)->insert($dataKualifikasi);
                        }

                        if (!empty($request->cabang_lain)) {
                            $dataCbgLain = array();
                            foreach ($request->cabang_lain as $num => $cbgLain) {
                                if (!empty($cbgLain)) {
                                    $dataCbgLain[] = array(
                                        'pegawai_id' => $Id,
                                        'cabang_id' => $cbgLain,
                                        'created_at' => date("Y-m-d H:i:s"),
                                    );
                                }
                            }
                            DB::table($this->table_branch_other)->insert($dataCbgLain);
                        }

                        if ($Id) {
                            $mess_['msg'] = 'Data sukses ditambahkan';
                            $mess_['cd'] = 200;
                        } else {
                            $mess_['msg'] = 'Data gagal ditambahkan';
                            $mess_['cd'] = 500;
                        }

                        echo json_encode($mess_);
                    });
                    break;

                default:
                    $cekRoleOnwer = DB::table($this->table)->where('role', $request->role)->count();
                    if ($cekRoleOnwer > 0 && $request->role == 5) {
                        $mess['msg'] = 'Data Owner gagal ditambahkan!, sementara cukup 1 Owner.';
                        $mess['cd'] = 500;

                        echo json_encode($mess);
                        exit;
                    }

                    $idUser = DB::table($this->table_users)->insertGetId($this->fields_user($request));

                    $role = Role::find($request->role);
                    $user = User::find($idUser);
                    $user->assignRole($role);

                    $tambah = DB::table($this->table)->insert($this->fields($request, $filename, $idUser));

                    if ($tambah) {
                        $mess['msg'] = 'Data sukses ditambahkan';
                        $mess['cd'] = 200;
                    } else {
                        $mess['msg'] = 'Data gagal ditambahkan';
                        $mess['cd'] = 500;
                    }

                    echo json_encode($mess);
                    break;
            }
        }

        if (!empty($request->id)) {
            switch ($request->role) {
                case '':
                    try {
                        $affected = DB::table($this->table)->where('id', $request->id)->update($this->fields($request, $filename));
                        $mess['msg'] = 'Data sukses disimpan' . ($affected == 0 ? ", namun tidak ada perubahan" : " dan diubah");
                        $mess['cd'] = 200;
                        echo json_encode($mess);
                    } catch (Exception $ex) {
                        $mess['msg'] = 'Data gagal disimpan' . $ex;
                        $mess['cd'] = 500;
                        echo json_encode($mess);
                    }

                    break;
                case '3':
                    try {
                        $elmTable = DB::table($this->table)->where('id', $request->id);
                        $uId = $elmTable->get()->last()->user_id;

                        if (!empty($uId)) {
                            $affected_user = DB::table($this->table_users)->where('id', $uId)
                                ->update($this->fields_user_update($request));

                            $role = Role::find($request->role);
                            $user = User::find($uId);
                            $user->assignRole($role);
                        } else {
                            $uId = DB::table($this->table_users)->insertGetId($this->fields_user($request));

                            $role = Role::find($request->role);
                            $user = User::find($uId);
                            $user->assignRole($role);
                        }

                        if ($request->role == 3) {
                            if (!empty($uId)) {
                                DB::table($this->table_users)->where('id', $uId)->delete();
                            }

                            if (!empty($request->kualifikasi)) {
                                DB::table($this->table_kualifikasi)->where('pegawai_id', $request->id)->delete();
                                $dataKualifikasi = array();
                                foreach ($request->kualifikasi as $num => $lyn) {
                                    $dataKualifikasi[] = array(
                                        'pegawai_id' => $request->id,
                                        'layanan_id' => $lyn,
                                        'created_at' => date("Y-m-d H:i:s"),
                                    );
                                }
                                DB::table($this->table_kualifikasi)->insert($dataKualifikasi);
                            }

                            if (!empty($request->cabang_lain)) {
                                DB::table($this->table_branch_other)->where('pegawai_id', $request->id)->delete();
                                $dataCbgLain = array();
                                foreach ($request->cabang_lain as $num => $cbgO) {
                                    $dataCbgLain[] = array(
                                        'pegawai_id' => $request->id,
                                        'cabang_id' => $cbgO,
                                        'created_at' => date("Y-m-d H:i:s"),
                                    );
                                }
                                DB::table($this->table_branch_other)->insert($dataCbgLain);
                            }
                        }

                        $affected = DB::table($this->table)->where('id', $request->id)->update($this->fields($request, $filename, $uId));
                        $mess['msg'] = 'Data sukses disimpan' . ($affected == 0 ? ", namun tidak ada perubahan" : " dan diubah");
                        $mess['cd'] = 200;
                        echo json_encode($mess);
                    } catch (Exception $ex) {
                        $mess['msg'] = 'Data gagal disimpan' . $ex;
                        $mess['cd'] = 500;
                        echo json_encode($mess);
                    }
                    break;
                default:
                    try {

                        $elmTable = DB::table($this->table)->where('id', $request->id);
                        $uId = $elmTable->get()->last()->user_id;

                        if (!empty($uId)) {
                            $affected_user = DB::table($this->table_users)->where('id', $uId)
                                ->update($this->fields_user_update($request));

                            $role = Role::find($request->role);
                            $user = User::find($uId);
                            $user->assignRole($role);
                        } else {
                            $uId = DB::table($this->table_users)->insertGetId($this->fields_user($request));

                            $role = Role::find($request->role);
                            $user = User::find($uId);
                            $user->assignRole($role);
                        }

                        $affected = $elmTable->update($this->fields($request, $filename, $uId));

                        $mess['msg'] = 'Data sukses disimpan' . ($affected == 0 && $affected_user == 0 ? ", namun tidak ada perubahan" : " dan diubah");
                        $mess['cd'] = 200;
                        echo json_encode($mess);
                    } catch (Exception $ex) {
                        $mess['msg'] = 'Data gagal disimpan' . $ex;
                        $mess['cd'] = 500;
                        echo json_encode($mess);
                    }
                    break;
            }
        }
    }

    public function _option(Request $request)
    {
        if (empty($request)) {
            return abort(404);
        }

        if (!in_array($request->table, array('cabang_oth', 'role', $this->table_branch, $this->table_services))) {
            return abort(404);
        }

        if (!empty($request) && $request->table == 'role') {
            $cekRoleOnwer = DB::table($this->table)->where('role', 5)->count();
            $dataOld = array(
                array('id' => 1, 'nama' => 'Super Admin'),
                array('id' => 2, 'nama' => 'Finance'),
                array('id' => 3, 'nama' => 'Dokter'),
                array('id' => 4, 'nama' => 'Kasir' . $request->role),
            );

            $dataNew = $cekRoleOnwer == 0 ?
                array(array('id' => 5, 'nama' => 'Owner')) : array();

            $data = array_merge($dataOld, $dataNew);
        } else {
            if ($request->table == 'layanan') {
                $data_ = DB::table($request->table);
                $data_->leftJoin($this->table_category, $this->table_category . '.id', '=', $request->table . '.kategori_id');
                if (!empty($request->cabang_id)) {
                    $data_->leftJoin($this->table_detail_cabang, $this->table_detail_cabang . '.layanan_id', '=', $request->table . '.id');
                    $data_->where($this->table_detail_cabang . '.cabang_id', $request->cabang_id);
                }
                $data_->select($request->table . '.*', $this->table_category . '.nama as kategori');
                $data_->orderBy($this->table_category . '.nama', 'ASC');
                $data_->orderBy($request->table . '.nama', 'ASC');
                $data = $data_->get();
            } else {
                if ($request->table == 'cabang_oth') {
                    $data_ = DB::table($this->table_branch);
                    if (!empty($request->cabang_id)) {
                        $data_->whereNotIn('id', [$request->cabang_id]);
                    }
                } else {
                    $data_ = DB::table($request->table);
                }
                $data = $data_->get();
            }
        }

        echo json_encode($data);
    }

    public function create()
    {
        $data = [
            'action' => route('employees.store'),
            'js' => array('js' => 's-home/master-data/employee/js/employee.js'),
        ];
        return view('master-data.employee.content.form.form', $data);
    }

    public function update($id = false)
    {
        if (!empty($id)) {
            $data1 = DB::table($this->table)
                ->leftJoin(
                    $this->table_users,
                    $this->table . '.user_id',
                    '=',
                    $this->table_users . '.id'
                )
                ->select(
                    $this->table . '.id',
                    $this->table . '.cabang_id',
                    $this->table . '.nama',
                    $this->table . '.role',
                    $this->table . '.foto',
                    $this->table . '.jabatan',
                    $this->table . '.komisi',
                    $this->table_users . '.email'
                )
                ->where($this->table . '.id', $id)
                ->first();

            $data2 = DB::table($this->table_kualifikasi)
                ->select(
                    DB::raw('GROUP_CONCAT(' . $this->table_kualifikasi . '.layanan_id' . ') as pegawai')
                )
                ->where($this->table_kualifikasi . '.pegawai_id', $id)
                ->first();

            $data3 = DB::table($this->table_branch_other)
                ->select(
                    DB::raw('GROUP_CONCAT(' . $this->table_branch_other . '.cabang_id' . ') as cabang')
                )
                ->where($this->table_branch_other . '.pegawai_id', $id)
                ->first();

            $dataE = [
                'id' => $data1->id,
                'cabang_id' => $data1->cabang_id,
                'nama' => $data1->nama,
                'role' => $data1->role,
                'foto' => $data1->foto,
                'jabatan' => $data1->jabatan,
                'komisi' => $data1->komisi,
                'email' => $data1->email,
                'pegawai' => $data2->pegawai,
                'cabangLain' => $data3->cabang,
            ];

            if (!$dataE) {
                return abort(404);
            }

            $data = [
                'action' => route('employees.store'),
                'js' => array('js' => 's-home/master-data/employee/js/employee.js'),
                'dataE' => $dataE,
            ];
            return view('master-data.employee.content.form.form', $data);
        }
    }

    public function _data()
    {
        return view('master-data.employee.content.data.table');
    }

    public function _json()
    {
        if (request()->ajax()) {
            return datatables()->of(DB::table($this->table)
                ->leftJoin(
                    $this->table_branch,
                    $this->table . '.cabang_id',
                    '=',
                    $this->table_branch . '.id'
                )
                ->select(
                    DB::raw("(SELECT COUNT(*) FROM pegawai_cabang_detail WHERE pegawai_id = " . $this->table . ".id) as in_det_use"),
                    DB::raw("(SELECT COUNT(*) FROM kalendar_shift WHERE pegawai_id = " . $this->table . ".id) as in_kal_use"),
                    $this->table . '.foto',
                    $this->table . '.id',
                    $this->table . '.nama',
                    $this->table . '.role',
                    $this->table . '.jabatan',
                    $this->table_branch . '.nama as cabang'
                )
                ->orderBy('id', 'DESC')->get())
                ->addColumn('action', 'master-data.employee.content.data.action_button')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function destroy($id)
    {
        $mess = null;
        $data = DB::table($this->table)->where('id', $id);

        $image = $data->first()->foto;
        if (!empty($image)) {
            File::delete(storage_path($this->dir) . $image);
        }

        $hapus = $data->delete();

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
