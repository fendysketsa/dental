<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;


class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily-report-sales:gula';

    private $total_pemasukan = 0;
    private $total_pengeluaran = 0;
    private $total_modale = 0;
    private $total_kunjungan = 0;

    protected $table = 'transaksi';
    protected $table_pengeluaran = 'pengeluaran';
    protected $table_pembelian = 'pembelian';
    protected $table_modal = 'set_modal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $date = Carbon::parse(now())->format('Y-m-d');
        $tgl_pendp_kemaren = date('l, d F Y', strtotime($date . ' - 1 days'));

        $details = [
            'title' => 'Daily Report Summary',
            'tanggal' => $tgl_pendp_kemaren,
            'url_pos' => 'https://pos.gulawaxing.com',
            'back_pos' => 'https://pos.gulawaxing.com/login?next=reports/sales/summary',
            'pendapatan' => $this->generate_pendapatan(),
            'penjualan' => $this->generate_pendapatan('penjualan'),
            'pengeluaran_pembelian' => $this->generate_pendapatan('pengeluaran_pembelian'),
            'kunjungan' => $this->generate_pendapatan('kunjungan'),
        ];

        $mail = \Mail::to('antoniusetiawan@gmail.com')
            ->cc('anggitrestupi@gmail.com')
            ->bcc('fendysketsa@gmail.com')
            ->send(new \App\Mail\sendMailSales($details));

        if (!$mail) {
            return $this->SendMailCron();
        }
    }

    public function index()
    {
        $date = Carbon::parse(now())->format('Y-m-d');
        $tgl_pendp_kemaren = date('l, d F Y', strtotime($date . ' - 1 days'));

        return view('daily.report.mail', [
            'title' => 'Daily Report Summary',
            'tanggal' => $tgl_pendp_kemaren,
            'url_pos' => 'https://pos.gulawaxing.com',
            'back_pos' => 'https://pos.gulawaxing.com/login?next=reports/sales/summary',
            'pendapatan' => $this->generate_pendapatan(),
            'penjualan' => $this->generate_pendapatan('penjualan'),
            'pengeluaran_pembelian' => $this->generate_pendapatan('pengeluaran_pembelian'),
            'kunjungan' => $this->generate_pendapatan('kunjungan'),
        ]);
    }

    public function SendMailCron()
    {
        require_once './vendor/autoload.php';

        $account    = "informasi@portalams.co.id";
        $password   = "sampleajalah&&**123";
        $subject = 'Daily Sales Summary - Gula Waxing';

        //yahoo saja
        // $email = '';

        $mail = new PHPMailer();
        $mail->IsSMTP();

        $mail->SMTPDebug = 0;
        $mail->CharSet = 'UTF-8';
        $mail->Host = 'portalams.co.id';
        $mail->SMTPAuth = true;
        $mail->Port = 465;
        $mail->Username = $account;
        $mail->Password = $password;
        $mail->SMTPSecure = 'ssl';
        $mail->Priority = 1;

        $mail->SetFrom('admin@gula.layana.id', 'Administrator - Gula Waxing');
        $mail->Subject = $subject;
        $mail->IsHTML(true);
        $mail->Body = $this->index();

        // $address = $email;
        // $mail->AddAddress($address, "Sales Summary - For Owner");

        //yahoo saja
        $address2 = "cafeynu_88@yahoo.com";
        $mail->AddAddress($address2, "CC - Sales Summary - Yahoo");
        $mail->send();
    }

    public function generate_pendapatan($req = false)
    {
        $tanggal_sekarang = Carbon::parse(now())->format('Y-m-d');
        $starts = date('Y-m-d', strtotime($tanggal_sekarang . ' - 1 days'));
        $ends = date('Y-m-d', strtotime($tanggal_sekarang . ' - 1 days'));

        if (!empty($req) && $req == 'kunjungan') {
            $this->total_kunjungan = 0;
            $dataKun = DB::table($this->table)
                ->leftJoin(
                    'member',
                    'member.id',
                    '=',
                    'transaksi.member_id'
                )
                ->select(
                    DB::raw('(SELECT count(*)
                    FROM transaksi
                    WHERE DATE(transaksi.created_at) = "' . $starts . '"
                    AND member_id = member.id) as kunjungan')
                )
                ->where(DB::raw('DATE(transaksi.created_at)'), $starts);

            if ($req == 'kunjungan') {
                $this->total_kunjungan = 0;
            }

            foreach ($dataKun->get() as $row) {
                $this->total_kunjungan += $row->kunjungan;
            }

            if ($req == 'kunjungan') {
                return $this->total_kunjungan;
            }
        }

        if (empty($req) || $req == 'penjualan') {
            $data1 = DB::table($this->table)
                ->select(
                    $this->table . '.id',
                    DB::raw('DATE(' . $this->table . '.created_at) as tanggal'),
                    $this->table . '.no_transaksi',
                    $this->table . '.total_biaya'
                );

            $data1->where($this->table . '.status', 3)
                ->where($this->table . '.status_pembayaran', 'terbayar');

            if (!empty($starts) && !empty($ends)) {
                $data1->whereBetween(
                    DB::raw('DATE(' . $this->table . '.created_at)'),
                    [$starts, $ends]
                );
            }

            $data_pemasukan = $data1->orderBy(DB::raw('DATE(' . $this->table . '.created_at)'), 'DESC')
                ->orderBy($this->table . '.no_transaksi', 'DESC');

            if ((!empty($req) && $req == 'penjualan') || empty($req)) {
                $this->total_pemasukan = 0;
            }

            foreach ($data_pemasukan->get() as $row) {
                $this->total_pemasukan += $row->total_biaya;
            }

            if (!empty($req) && $req == 'penjualan') {
                return $this->total_pemasukan;
            }
        }

        if (empty($req) || $req == 'pengeluaran_pembelian') {
            $data2 = DB::table($this->table_pengeluaran)
                ->leftJoin('pegawai', 'pegawai.id', '=', 'pengeluaran.pegawai_id');

            if (!empty($starts) && !empty($ends)) {
                $data2->whereBetween(
                    DB::raw('DATE(pengeluaran.created_at)'),
                    [$starts, $ends]
                );
            }

            $data2->select(
                $this->table_pengeluaran . '.id',
                DB::raw('DATE(' . $this->table_pengeluaran . '.created_at) as tanggal'),
                $this->table_pengeluaran . '.no_pengeluaran as no_transaksi',
                'pegawai.nama as operator',
                $this->table_pengeluaran . '.total_pengeluaran as total_biaya'
            )
                ->orderBy(DB::raw('DATE(' . $this->table_pengeluaran . '.created_at)'), 'DESC')
                ->orderBy($this->table_pengeluaran . '.no_pengeluaran', 'DESC');

            $dataJson1 = $data2->get();

            foreach ($dataJson1 as $row) {
                $this->total_pengeluaran += $row->total_biaya;
            }

            $data2a = DB::table($this->table_pembelian)
                ->leftJoin('pegawai', 'pegawai.id', '=', 'pembelian.pegawai_id')
                ->leftJoin('supplier', 'supplier.id', '=', 'pembelian.supplier_id');

            if (!empty($starts) && !empty($ends)) {
                $data2a->whereBetween(
                    DB::raw('DATE(pembelian.created_at)'),
                    [$starts, $ends]
                );
            }

            $data2a->select(
                $this->table_pembelian . '.id',
                DB::raw('DATE(' . $this->table_pembelian . '.created_at) as tanggal'),
                $this->table_pembelian . '.no_pembelian as no_transaksi',
                'pegawai.nama as operator',
                'supplier.nama as supplier',
                $this->table_pembelian . '.total_pembelian as total_biaya'
            )
                ->orderBy(DB::raw('DATE(' . $this->table_pembelian . '.created_at)'), 'DESC')
                ->orderBy($this->table_pembelian . '.no_pembelian', 'DESC');

            $dataJson2 = $data2a->get();

            if ($req == 'pengeluaran_pembelian') {
                $this->total_pengeluaran;
            }

            foreach ($dataJson2 as $row) {
                $this->total_pengeluaran += $row->total_biaya;
            }

            if ($req == 'pengeluaran_pembelian') {
                return $this->total_pengeluaran;
            }
        }

        $data3 = DB::table($this->table_modal)
            ->leftJoin('shift', 'shift.id', '=', 'set_modal.shift_id')
            ->leftJoin('pegawai', 'pegawai.id', '=', 'set_modal.pegawai_id');

        if (!empty($starts) && !empty($ends)) {
            $data3->whereBetween(
                DB::raw('DATE(set_modal.created_at)'),
                [$starts, $ends]
            );
        }

        $data3->select(
            $this->table_modal . '.id',
            DB::raw('DATE(' . $this->table_modal . '.created_at) as tanggal'),
            $this->table_modal . '.nominal as total_biaya',
            'shift.nama as shift',
            'pegawai.nama as operator'
        )
            ->orderBy(DB::raw('DATE(' . $this->table_modal . '.created_at)'), 'DESC')
            ->orderBy($this->table_modal . '.id', 'DESC');

        $dataJson3 = $data3->get();
        foreach ($dataJson3 as $row) {
            $this->total_modale += $row->total_biaya;
        }

        return $this->total_pemasukan - ($this->total_pengeluaran + $this->total_modale);
    }
}