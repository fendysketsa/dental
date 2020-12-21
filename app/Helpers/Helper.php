<?php

function rupiahFormat($amount = 0)
{
    return number_format(intval($amount), 0, '', '.');
}

function rupiahFormatExcel($amount = 0)
{
    return number_format(intval($amount), 0, '', ',');
}

function unRupiahFormat($amount = 0)
{
    return str_replace(".", "", $amount);
}

function LoadCabang()
{
    return DB::table('cabang')
        ->leftJoin('pegawai', 'pegawai.cabang_id', '=', 'cabang.id')
        ->select(
            'cabang.*',
            DB::raw('IF((SELECT MAX(id) FROM pegawai ' .
                'where cabang_id = cabang.id AND id = pegawai.id AND user_id = ' .
                auth()->user()->id . ' limit 1), "CHECK", "") as selected')
        )
        ->groupBy('cabang.id')
        ->orderBy('cabang.nama', 'ASC')
        ->get();
}

function getNamaCabang($cabang = false)
{
    if (!empty($cabang)) {
        return DB::table('cabang')
            ->where('id', base64_decode($cabang))
            ->select(DB::RAW('CONCAT(nama, " - " ,alamat) as cabang'))
            ->first()
            ->cabang;
    }
}

function getBranch($br = false)
{
    $cabg = DB::table('pegawai')
        ->leftJoin('cabang', 'cabang.id', '=', 'pegawai.cabang_id')
        ->select(DB::raw('pegawai.*, cabang.kode'))
        ->where('user_id', auth()->user()->id);

    if (!empty($br) && $br == 'owner') {
        $cabg->where('role', 5);
    } else {
        $cabg->where('role', 4);
    }

    $dataCabang = $cabg->first();

    if (!empty($dataCabang)) {
        return session(
            [
                'cabang_id' => base64_encode($dataCabang->cabang_id),
                'cabang_code' => base64_encode($dataCabang->kode)
            ]
        );
    }
}

function Color($nom)
{
    $color = '';
    switch ($nom) {
        case 0:
            $color .= '#70CEE4';
            break;
        case 1:
            $color .= '#F8878F';
            break;
        case 2:
            $color .= '#965786';
            break;
        case 3:
            $color .= '#F8C765';
            break;
        case 4:
            $color .= '#F6404F';
            break;
    }

    return $color;
}
