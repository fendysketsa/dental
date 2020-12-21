<form action="{{ $action }}" id="formLayanan"></form>
@csrf

<div class="load-form-modal">
    <div class="modal-content">
        <div class="modal-header bg-warning ">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-center"></h4>
        </div>
        <div class="modal-body">
            @if(!empty($dataE))
            <input type="hidden" name="id" value="{{ $dataE['id'] }}" form="formLayanan">
            @endif

            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#futama" data-toggle="tab" aria-expanded="false">Utama</a></li>
                    <li class="">
                        <a href="#fdeskripsi" data-toggle="tab" aria-expanded="false">Deskripsi</a></li>
                    <li class="">
                        <a href="#fgambar" data-toggle="tab" aria-expanded="false">Gambar</a></li>
                    <li>
                        <a href="#retouch" data-toggle="tab" aria-expanded="false">Retouch</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="futama">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group input-group-sm">
                                    <label>Kategori: <em class="text-danger">*</em></label>
                                    <select name="kategori" class="form-control select2 kategori" style="width: 100%;"
                                        @if(!empty($dataE)) data-selected="{{ $dataE['kategori_id'] }}" @endif
                                        form="formLayanan"></select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group input-group-sm">
                                    <label>Cabang: <em class="text-danger">*</em></label>
                                    <select name="cabang[]" class="form-control select2-multiple-cabang cabang"
                                        multiple="multiple" style="width: 100%;" @if(!empty($dataE))
                                        data-selected="{{ $dataE['cabang'] }}" @endif form="formLayanan"></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group input-group-sm">
                                    <label>Brand:</label>
                                    <select name="brand[]" class="form-control select2-multiple brand"
                                        multiple="multiple" style="width: 100%;" form="formLayanan" @if(!empty($dataE))
                                        data-selected="{{ $dataE['brand'] }}" @endif></select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Nama: <em class="text-danger">*</em></label>
                                            <div class="input-group input-group-sm date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-tag"></i>
                                                </div>
                                                <input type="text" name="nama" @if(!empty($dataE))
                                                    value="{{ $dataE['nama'] }}" @endif class="form-control"
                                                    placeholder="Nama..." form="formLayanan">
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Komisi: <em class="text-danger">*</em></label>
                                            <div class="input-group input-group-sm date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-money"></i>
                                                </div>
                                                <input type="text" name="komisi" @if(!empty($dataE))
                                                    value="{{ $dataE['komisi'] }}" @endif class="form-control"
                                    placeholder="Komisi..." form="formLayanan">
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Harga: <em class="text-danger">*</em></label>
                        <div class="input-group input-group-sm date">
                            <div class="input-group-addon">
                                <i class="fa fa-money"></i>
                            </div>
                            <input type="rupiah" name="harga" @if(!empty($dataE))
                                value="{{ rupiahFormat($dataE['harga']) }}" @endif class="form-control"
                                placeholder="Harga..." form="formLayanan">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Waktu Pengerjaan:</label>
                        <div class="input-group input-group-sm date">
                            <div class="input-group-addon">
                                <i class="fa fa-clock-o"></i>
                            </div>
                            <input type="text" name="waktu_pengerjaan" @if(!empty($dataE))
                                value="{{ $dataE['waktu_pengerjaan'] }}" @endif class="form-control"
                                placeholder="Waktu Pengerjaan..." form="formLayanan">
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Garansi:</label>
                        <div class="input-group input-group-sm date">
                            <div class="input-group-addon">
                                <i class="fa fa-thumbs-up"></i>
                            </div>
                            <input type="text" name="garansi" @if(!empty($dataE)) value="{{ $dataE['garansi'] }}" @endif
                                class="form-control" placeholder="Garansi..." form="formLayanan">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Waktu Garansi:</label>
                        <div class="input-group input-group-sm date">
                            <div class="input-group-addon">
                                <i class="fa fa-clock-o"></i>
                            </div>
                            <input type="text" name="waktu_garansi" @if(!empty($dataE))
                                value="{{ $dataE['waktu_garansi'] }}" @endif class="form-control"
                                placeholder="Waktu Garansi..." form="formLayanan">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="fdeskripsi">
            <div class="form-group">
                <label>Deskripsi:</label>
                <div class="input-group input-group-sm date">
                    <div class="input-group-addon">
                        <i class="fa fa-edit"></i>
                    </div>
                    <textarea name="deskripsi" class="form-control add-style" id="deskripsi" placeholder="Deskripsi..."
                        form="formLayanan">{{ (!empty($dataE) ? $dataE['deskripsi'] : null) }}</textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="fgambar">
            <div class="form-group">
                <div style="width:100%;height: 380px; border: 1px solid whitesmoke ;text-align: center;position: relative"
                    id="image">
                    <img width="100%" height="100%" id="preview_image"
                        src="{{ (!empty($dataE['gambar']) ?
                                    (file_exists('storage/master-data/service/uploads/'
                                    . $dataE['gambar']) ? 'storage/master-data/service/uploads/'
                                    . $dataE['gambar'] : asset('images/brokenimage.jpg')) : asset('images/noimage.jpg') ) }}" />
                </div>
                <div class="row mt-5">
                    <div class="col-md-6 col-xs-6 col-sm-6">
                        <a class="btn btn-xs btn-default col-md-12 col-xs-12 col-sm-12" role="button"
                            href="javascript:changePicture()">
                            <i class="fa fa-upload text-info"></i> </a>
                    </div>
                    <div class="col-md-6 col-xs-6 col-sm-6">
                        <a class="btn btn-xs btn-default col-md-12 col-xs-12 col-sm-12" role="button"
                            href="javascript:removeFile()">
                            <i class="fa fa-trash text-danger"></i> </a>
                    </div>
                </div>

                <input type="file" id="file" name="gambar" class="hide" form="formLayanan" />
                <input type="hidden" id="file_name" form="formLayanan" @if(!empty($dataE)) name="old_img"
                    value="{{ $dataE['gambar'] }}" @endif />
            </div>
        </div>
        <div class="tab-pane" id="retouch">
            <div class="form-group row col-xs-4">
                <label>Waktu <small><sup>(Hari)</sup></small> :</label>
                <div class="input-group input-group-sm date">
                    <div class="input-group-addon">
                        <i class="fa fa-clock-o"></i>
                    </div>
                    <input type="number" min="1" step="1" name="retouch_waktu" @if(!empty($dataE))
                        value="{{ $dataE['retouch_waktu'] }}" @endif class="form-control" placeholder="Waktu..."
                        form="formLayanan">
                </div>
            </div>
            <div class="form-group input-group-sm">
                <textarea name="retouch_detail" cols="15" rows="4" class="form-control add-style" style="height:120px;"
                    placeholder="Detail Syarat & Kententuan berlaku..."
                    form="formLayanan">{{ (!empty($dataE['retouch_detail']) ? $dataE['retouch_detail'] : null) }}</textarea>
            </div>
        </div>
    </div>
</div>
</div>
<div class="box-footer bg-gray-light">
    <div class="row">
        <div class="col-md-6 col-xs-6 col-sm-6">
            <a role="button" data-dismiss="modal" class="btn btn-warning col-md-12 col-xs-12 col-sm-12"><em
                    class="fa fa-undo"></em> Cancel</a>
        </div>
        <div class="col-md-6 col-xs-6 col-sm-6">
            <button type="submit" class="btn btn-info col-md-12 col-xs-12 col-sm-12" form="formLayanan"><em
                    class="fa fa-envelope"></em> Save</button>
        </div>
    </div>
</div>
</div>
</div>
