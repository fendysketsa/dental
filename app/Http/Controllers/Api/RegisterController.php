<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Api\RegMember;
use App\Models\Api\RegUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Notifications\ApiNotifAuth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\Messages\MailMessage;

use PHPMailer\PHPMailer\OAuth;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\POP3;
use PHPMailer\PHPMailer\SMTP;
use Illuminate\Contracts\Routing\ResponseFactory;

use Illuminate\Support\Facades\Crypt;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'referal'   => 'required|string|max:255',
            'email'     => 'required|string|email|max:255',
        ]);

        $email = DB::table('member')->where('email', $request->email)->where('referal_code', $request->referal);

        if (!empty($email->get())) {

            if ($email->get()->first()->status == 2) {
                $mess['msg'] = 'Oops!, Akun Anda sudah aktif!';
                $mess['cd'] = 500;
                return response()->json($mess);
            }

            $email->update(['status' => 2]);

            $mess['msg'] = 'Akun Anda berhasil diaktifkan!';
            $mess['cd'] = 200;
            return response()->json($mess);

            // $data = [
            //     'header' => 'Selamat!',
            //     'images' => asset('images/handshake.png'),
            //     'message' => 'Akun Anda berhasil diaktifkan!',
            //     'messageFoot' => 'Silakan login melalui Medina Dental Apps untuk melakukan proses selanjutnya.'
            // ];

            // return response()
            //     ->view('setting.congrate.index', $data, 200)
            //     ->header('Content-Type', $type);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $userData = new RegUser();
        $cekUserData = $userData->where('email', $request->email);
        $UserDataPassword = $cekUserData->first();

        if ($cekUserData->count() == 0) {

            $request->validate([
                'name'          => 'required|string|max:255',
                'email'         => 'required|string|email|max:255|unique:users',
                'password'      => 'required|string|min:8|same:konfirmasi_password',
                'telepon'       => 'required|numeric|min:7',
                'jenis_kelamin' => 'required|string',
                'tgl_lahir'     => 'required|string',
                'alamat'        => 'required|string',
                'nik'           => 'required|string',
                'profesi'       => 'required|string',
                'agama'         => 'required|string',
                'status_member' => 'required|string',
                'instansi'      => 'required|string',
            ]);

            $memberData = new RegMember();

            DB::transaction(function () use ($userData, $memberData, $request) {

                if ($request->has('from_new_session') && $request->from_new_session == 'gugel_opo_fesbuk') {
                    $partUs = explode('@', $request->email);
                    $userDefPass = Crypt::encryptString($partUs[0]);
                    $passWord = $partUs[0];
                } else {
                    $userDefPass = Crypt::encryptString($request->password);
                    $passWord = $request->password;
                }

                $userData['password'] = Hash::make($passWord);
                $userData['rec_pass'] = $userDefPass;
                $userData->fill($request->all());
                $userSimpan = $userData->save();

                if ($userSimpan) {

                    $memberData['telepon'] = $request->telepon;
                    $memberData['user_id'] = $userData->id;
                    $memberData['nama'] = $request->name;
                    $memberData['no_member'] = $memberData->getAutoNoMember();

                    $memberData['tgl_lahir'] = $request->tgl_lahir;
                    $memberData['alamat'] = $request->alamat;
                    $memberData['nik'] = $request->nik;
                    $memberData['profesi'] = $request->profesi;
                    $memberData['agama'] = $request->agama;
                    $memberData['status_member'] = $request->status_member;
                    $memberData['instansi'] = $request->instansi;

                    $memberData->fill($request->all());

                    if ($request->has('from_new_session') && $request->from_new_session == 'gugel_opo_fesbuk') {
                        $memberData['status'] = 2;
                    } else {
                        $memberData['status'] = 1;
                    }

                    $memberData->save();

                    if ($request->has('from_new_session') && $request->from_new_session == 'gugel_opo_fesbuk') {

                        $dataLogin = [
                            'use_password' => Crypt::decryptString($userDefPass),
                            'code' => 200,
                            'message' => 'Akun Anda telah terdaftar',
                        ];

                        return response()->json($dataLogin);
                    }
                }

                //     if (empty($request->has('from_new_session'))) {

                //         require_once './../vendor/autoload.php';

                //         $account    = "informasi@portalams.co.id";
                //         $password   = "sampleajalah&&**123";

                //         $url = url('/api/actived/member/' . base64_encode($request->email));
                //         $subject = "New Member";
                //         $greeting = 'Hello, ' . $request->name;

                //         $message = '<html><body><h3>' . $greeting . '</h3>';
                //         $message .= '<p>Silakan klik button di bawah ini untuk mengaktivasi akun Anda!</p>';
                //         $message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
                //         $message .= "<tr style='background: #eee;'><td><strong>Name:</strong> </td><td>" . strip_tags($request->name) . "</td></tr>";
                //         $message .= "<tr style='background: #eee;'><td><strong>Email:</strong> </td><td>" . strip_tags($request->email) . "</td></tr>";
                //         $addURLS = $url;

                //         if (($addURLS) != '') {
                //             $message .= "<tr><td><strong>Aktivasi Link:</strong> </td><td> <a target='_blank' href='" . strip_tags($addURLS) . "' > <button> Klik Aktivasi </button> </a></td></tr>";
                //         }

                //         $message .= "<tr><td><strong>Alternatif Link:</strong> </td>
                //                             <td> " . strip_tags($addURLS) . "
                //                     </td></tr>";

                //         $message .= "</table>";
                //         $message .= "</body>
                // <p>Terimakasih, telah menggunakan aplikasi ini untuk kemudahan Anda bertransaksi!</p>
                // </html>";

                //         $mail = new PHPMailer();
                //         $mail->IsSMTP();

                //         $mail->SMTPDebug = 0;
                //         $mail->CharSet = 'UTF-8';
                //         $mail->Host = 'portalams.co.id';
                //         $mail->SMTPAuth = true;
                //         $mail->Port = 465;
                //         $mail->Username = $account;
                //         $mail->Password = $password;
                //         $mail->SMTPSecure = 'ssl';
                //         $mail->Priority = 1;

                //         $mail->SetFrom('admin@medinadental.clinic', 'Admin - Medina Dental');
                //         $mail->Subject = $subject;
                //         $mail->IsHTML(true);
                //         $mail->Body = $message;

                //         $address = $request->email;
                //         $mail->AddAddress($address, "New Member - " . $request->name);

                //         $address2 = "cafeynu_88@yahoo.com";
                //         $mail->AddAddress($address2, "CC - Aktivasi - Yahoo");

                //         $address3 = "fendysketsa@gmail.com";
                //         $mail->AddAddress($address3, "CC - Aktivasi - Google");

                //         $sending = $mail->send();

                //         if (!$sending) {
                //             Notification::route('mail', $request->email)
                //                 ->notify(new ApiNotifAuth($userData));
                //         }
                //     }
            });

            if (empty($request->has('from_new_session'))) {
                return response()->json([
                    'code' => 200,
                    'message' => 'Anda Berhasil membuat akun, silakan cek email Anda untuk verifikasi!'
                ]);
            } else {

                if ($request->has('from_new_session') && $request->from_new_session == 'gugel_opo_fesbuk') {
                    $partUs = explode('@', $request->email);
                    $userDefPass = Crypt::encryptString($partUs[0]);
                } else {
                    $userDefPass = Crypt::encryptString($request->password);
                }

                $dataLogin = [
                    'use_password' => Crypt::decryptString($userDefPass),
                    'code' => 200,
                    'message' => 'Akun Anda telah terdaftar',
                ];

                return response()->json($dataLogin);
            }
        } else {

            if ($request->has('from_new_session') && $request->from_new_session == 'gugel_opo_fesbuk') {
                $partUs = explode('@', $request->email);
                $userDefPass = Crypt::encryptString($partUs[0]);
            } else {
                $userDefPass = Crypt::encryptString($request->password);
            }

            if ($request->has('from_new_session') && $request->from_new_session == 'gugel_opo_fesbuk') {
                $memberDataUpdate = RegMember::where('email', $request->email);
                $memberDataUpdate->update([
                    'status' => 2,
                ]);

                $userDataUpdate = RegUser::where('email', $request->email);
                $UserDataPassword_ = $userDataUpdate->first();

                if ($UserDataPassword_->rec_pass == null && empty($UserDataPassword_->rec_pass)) {
                    $userDataUpdate->update([
                        'rec_pass' => $userDefPass,
                    ]);
                }
            }

            $dataSessionGoFB = ($request->has('from_new_session') && $request->from_new_session == 'gugel_opo_fesbuk') ? [
                'use_password' => Crypt::decryptString($UserDataPassword->rec_pass),
            ] : [];

            $dataLogin = [
                'code' => 200,
                'message' => 'Akun Anda telah terdaftar',
            ];

            return response()->json(array_merge($dataSessionGoFB, $dataLogin));
        }
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
    public function update(Request $request, $id)
    {
        //
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
