<?php

namespace App\Http\Controllers\Web\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_encode;
use function GuzzleHttp\json_decode;

class DiscountController extends Controller
{
    protected $table = 'diskon';
    protected $table_layanan = 'layanan';
    protected $table_detail = 'discount_detail';
    protected $table_disc_services = 'discount_services';

    private $param = ['Rp', '%'];
    private $validate_message = [
        'berlaku_dari' => 'required',
        'berlaku_sampai' => 'required|after:berlaku_dari',
        'nama' => 'required',
        'nominal' => 'required|between:0,99.99',
    ];

    public function fields($request)
    {
        return [
            'nama' => $request->nama,
            'nominal' => unRupiahFormat($request->nominal),
            'param' => $request->param,
            'berlaku_dari' => $request->berlaku_dari ? date("Y-m-d", strtotime($request->berlaku_dari)) : null,
            'berlaku_sampai' => $request->berlaku_sampai ? date("Y-m-d", strtotime($request->berlaku_sampai)) : null,
        ];
    }

    public function validated($mess, $request)
    {
        $attributeNames = array(
            'param' => 'parameter',
            // 'product' => 'produk',
        );

        $message = array(
            'param' => 'required|not_in:0',
            // 'product' => 'required|not_in:0',
        );

        $customMessages = [
            'param.required' => 'Bidang pilihan :attribute wajib dipilih',
            // 'product.required' => 'Bidang pilihan :attribute wajib dipilih',
        ];
        $validator = \Validator::make(
            $request->all(),
            array_merge(
                $this->validate_message,
                $message
            ),
            $customMessages
        );
        $validator->setAttributeNames($attributeNames);

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

    public function _addCustomeValidate($request)
    {
        $custVal = DB::select("SELECT * FROM diskon
                WHERE (berlaku_dari >= '" . date("Y-m-d", strtotime($request->berlaku_dari)) . "' OR berlaku_sampai >= '" . date("Y-m-d", strtotime($request->berlaku_dari)) . "')
                AND ( berlaku_dari <= '" . date("Y-m-d", strtotime($request->berlaku_sampai)) . "' OR berlaku_sampai <= '" . date("Y-m-d", strtotime($request->berlaku_sampai)) . "')
                AND id != '" . $request->id . "'");
        if (!empty($custVal)) {
            $mess['msg'] = 'Data gagal disimpan, rentang waktu telah terpakai!';
            $mess['cd'] = 500;
            echo json_encode($mess);
            exit;
        }
    }

    public function index()
    {
        $data = [
            'js' => array('js' => 's-home/master-data/discount/js/discount.js'),
            'attribute' => array(
                'm_data' => 'true',
                'menu_discount' => 'active menu-open',
                'title_bc' => 'Master Data - Diskon',
                'desc_bc' => 'Digunakan untuk media menampilkan, menambah, mengubah dan menghapus diskon'
            ),
        ];
        return view('master-data.discount.index', $data);
    }

    public function create()
    {
        return view('master-data.discount.content.form.form', [
            'js' => array('js' => 's-home/master-data/discount/js/discount.js'),
            'dataParam' => $this->param,
            'action' => route('discounts.store'),
        ]);
    }

    public function update($id = false)
    {
        if (!empty($id)) {
            $data1 = DB::table($this->table)->where($this->table . '.id', $id)->first();
            $data2 = DB::table($this->table_detail)
                ->select(
                    DB::raw('GROUP_CONCAT(' . $this->table_detail . '.product_id' . ') as product')
                )
                ->where($this->table_detail . '.discount_id', $id)
                ->first();

            $data3 = DB::table($this->table_disc_services)
                ->select(
                    DB::raw('GROUP_CONCAT(' . $this->table_disc_services . '.layanan_id' . ') as services')
                )
                ->where($this->table_disc_services . '.discount_id', $id)
                ->first();

            $dataE = [
                'id' => $data1->id,
                'nama' => $data1->nama,
                'nominal' => $data1->nominal,
                'param' => $data1->param,
                'berlaku_dari' => $data1->berlaku_dari,
                'berlaku_sampai' => $data1->berlaku_sampai,
                'product' => $data2->product,
                'services' => $data3->services
            ];

            if (!$dataE) {
                return abort(404);
            }
            return view('master-data.discount.content.form.form', [
                'js' => array('js' => 's-home/master-data/discount/js/discount.js'),
                'dataParam' => $this->param,
                'action' => route('discounts.store'),
                'dataE' => $dataE,
            ]);
        }
    }

    public function store(Request $request)
    {
        $mess = null;
        $this->validated($mess, $request);
        $this->_addCustomeValidate($request);
        if (empty($request->id)) {
            $tambahId = DB::table($this->table)->insertGetId($this->fields($request));

            if (!empty($request->product)) {
                $dataDetailIns = array();
                foreach ($request->product as $num => $prd) {
                    $dataDetailIns[] = array(
                        'discount_id' => $tambahId,
                        'product_id' => $prd,
                        'created_at' => date("Y-m-d H:i:s"),
                    );
                }
                DB::table($this->table_detail)->insert($dataDetailIns);
            }

            if (!empty($request->services)) {
                $dataServIns = array();
                foreach ($request->services as $num => $srv) {
                    $dataServIns[] = array(
                        'discount_id' => $tambahId,
                        'layanan_id' => $srv,
                        'created_at' => date("Y-m-d H:i:s"),
                    );
                }
                DB::table($this->table_disc_services)->insert($dataServIns);
            }

            if ($tambahId) {
                $mess['msg'] = 'Data sukses ditambahkan';
                $mess['cd'] = 200;
            } else {
                $mess['msg'] = 'Data gagal ditambahkan';
                $mess['cd'] = 500;
            }
        }

        if (!empty($request->id)) {
            try {
                $affected = DB::table($this->table)->where('id', $request->id)->update($this->fields($request));
                if (!empty($request->product)) {
                    DB::table($this->table_detail)->where('discount_id', $request->id)->delete();
                    $dataDetailIns = null;
                    foreach ($request->product as $num => $prd) {
                        $dataDetailIns[] = array(
                            'discount_id' => $request->id,
                            'product_id' => $prd,
                            'created_at' => date("Y-m-d H:i:s"),
                        );
                    }
                    DB::table($this->table_detail)->insert($dataDetailIns);
                }

                if (!empty($request->services)) {
                    DB::table($this->table_disc_services)->where('discount_id', $request->id)->delete();
                    $dataServIns = null;
                    foreach ($request->services as $num => $srv) {
                        $dataServIns[] = array(
                            'discount_id' => $request->id,
                            'layanan_id' => $srv,
                            'created_at' => date("Y-m-d H:i:s"),
                        );
                    }
                    DB::table($this->table_disc_services)->insert($dataServIns);
                }
                $mess['msg'] = 'Data sukses disimpan' . ($affected == 0 ? ", namun tidak ada perubahan" : " dan diubah");
                $mess['cd'] = 200;
            } catch (Exception $ex) {
                $mess['msg'] = 'Data gagal disimpan' . $ex;
                $mess['cd'] = 500;
            }
        }
        echo json_encode($mess);
    }

    public function _option(Request $request)
    {
        if (empty($request)) {
            return abort(404);
        }
        $data = null;
        if (in_array($request->table, array('product', 'services'))) {
            if ($request->table == 'product') {
                $data = DB::table('produk')
                    ->leftJoin('discount_detail', 'discount_detail.product_id', '=', 'produk.id')
                    ->leftJoin(
                        'diskon',
                        function ($joins_) {
                            $joins_->on(
                                'diskon.id',
                                '=',
                                'discount_detail.discount_id'
                            );
                        }
                    )
                    ->select(
                        DB::RAW('(SELECT IF(dd.discount_id, "diskon", "") FROM discount_detail dd LEFT JOIN diskon d ON d.id = dd.discount_id
                                    WHERE dd.product_id = produk.id AND d.berlaku_sampai > CURDATE() LIMIT 1) AS produk_discount'),
                        DB::RAW('(SELECT IF(dd.discount_id, dd.discount_id, "") FROM discount_detail dd LEFT JOIN diskon d ON d.id = dd.discount_id
                                    WHERE dd.product_id = produk.id AND d.berlaku_sampai > CURDATE() LIMIT 1) AS grp_discount'),
                        "produk.*"
                    )
                    ->groupBy('produk.id')
                    ->get();
            }

            if ($request->table == 'services') {

                $datas = DB::table('kategori')
                    ->select('kategori.id', 'kategori.nama')
                    ->where('kategori.jenis', 1)
                    ->orderBy('kategori.nama', 'ASC')
                    ->get();

                foreach ($datas as $num => $layanan) {
                    $data[$num] = [
                        'id' => $layanan->id,
                        'nama' => $layanan->nama,
                        'data' => DB::table($this->table_layanan)
                            ->leftJoin('discount_services', 'discount_services.layanan_id', '=', 'layanan.id')
                            ->leftJoin(
                                'diskon',
                                function ($joins_) {
                                    $joins_->on(
                                        'diskon.id',
                                        '=',
                                        'discount_services.discount_id'
                                    );
                                }
                            )
                            ->select(
                                DB::RAW('(SELECT IF(ds.discount_id, "diskon", "") FROM discount_services ds LEFT JOIN diskon d ON d.id = ds.discount_id
                                    WHERE ds.layanan_id = layanan.id AND d.berlaku_sampai > CURDATE() LIMIT 1) AS layanan_discount'),
                                DB::RAW('(SELECT IF(ds.discount_id, ds.discount_id, "") FROM discount_services ds LEFT JOIN diskon d ON d.id = ds.discount_id
                                    WHERE ds.layanan_id = layanan.id AND d.berlaku_sampai > CURDATE() LIMIT 1) AS grp1_discount'),
                                "layanan.*"
                            )
                            ->where('layanan.kategori_id', $layanan->id)
                            ->orderBy('layanan.nama', 'ASC')
                            ->groupBy('layanan.id')
                            ->get()
                    ];
                }
            }
        }
        echo json_encode($data);
    }

    public function _data()
    {
        return view('master-data.discount.content.data.table');
    }

    public function _json()
    {
        if (request()->ajax()) {
            return datatables()->of(DB::table($this->table)
                ->select(
                    DB::raw("IF(param != '%', CONCAT(param,' ', REPLACE(FORMAT(nominal, 0), ',','.')), CONCAT(nominal,' ',param))  AS nominals"),
                    'id',
                    'nama',
                    'berlaku_dari',
                    'berlaku_sampai'
                )
                ->orderByDesc('id')
                ->get())
                ->addColumn('action', 'master-data.discount.content.data.action_button')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $mess = null;
            $hapus = DB::table($this->table)->where('id', $id);
            DB::table('discount_detail')->where('discount_id', $id)->delete();
            DB::table('discount_services')->where('discount_id', $id)->delete();
            $hapus->delete();
            if ($hapus) {
                $mess['msg'] = 'Data sukses dihapus!';
                $mess['cd'] = 200;
            } else {
                $mess['msg'] = 'Data gagal dihapus!';
                $mess['cd'] = 500;
            }
            echo json_encode($mess);
        });
    }
}
