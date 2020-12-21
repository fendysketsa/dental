<?php

namespace App\Http\Controllers\Web\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SettingNotaModel;
use Illuminate\Support\Facades\File;

class NotaController extends Controller
{
    private $dir = 's-home/setting/nota/uploads/';
    public function index()
    {
        return view(
            'setting.nota.index',
            [
                'action' => route('notas.store'),
                'css' => [
                    's-home/setting/nota/nota.css',
                ],
                'js' => [
                    's-home/setting/nota/nota.js',
                    's-home/bower_components/ckeditor/ckeditor.js',
                ],
                'attribute' => [
                    'm_other' => 'true',
                    'menu_nota' => 'active menu-open',
                    'title_bc' => 'Setting - Nota',
                    'desc_bc' => 'Digunakan untuk media mengatur konten pada layout nota',
                ]
            ]
        );
    }

    public function create()
    {
        return view('setting.nota.content.form.form', [
            'action' => route('notas.store'),
            'dataE' => SettingNotaModel::find(1)
        ]);
    }

    public function store(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'title' => 'required',
            'salutation' => 'required',
            'contact_info' => 'required',
            'logo' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);
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

        $filename = null;
        if ($request->hasFile('logo') == 1) {
            $extension = $request->file('logo')->getClientOriginalExtension();
            $image = SettingNotaModel::find(1)->logo;
            File::delete($this->dir . $image);
            $filename = uniqid() . '_' . time() . '.' . $extension;
            $request->file('logo')->move($this->dir, $filename);
        } else {
            if (empty($request->old_img) && $request->old_img == '') {
                $image = SettingNotaModel::find(1)->logo;
                File::delete($this->dir . $image);
            } else {
                $filename = $request->old_img;
            }
        }

        try {

            $affected = SettingNotaModel::find(1)
                ->forceFill([
                    'logo' => $filename,
                ])
                ->fill($request->all())
                ->update(['id' => 1]);

            $mess['msg'] = 'Data sukses disimpan' . ($affected == 0  ? ", namun tidak ada perubahan" : " dan diubah");
            $mess['cd'] = 200;
        } catch (Exception $ex) {
            $mess['msg'] = 'Data gagal disimpan' . $ex;
            $mess['cd'] = 500;
        }
        echo json_encode($mess);
    }

    public function _prev()
    {
        return view('setting.nota.content.preview.preview', [
            'dataE' => SettingNotaModel::find(1)
        ]);
    }
}
