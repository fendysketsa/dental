<form action="{{ $action }}" id="formProduk"></form>
@csrf

<div class="load-form-modal">
    <div class="modal-content">
        <div class="modal-header bg-warning ">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-center"></h4>
        </div>
        <div class="modal-body">
            @if(!empty($dataE))
            <input type="hidden" name="id" value="{{ $dataE->id }}" form="formProduk">
            @endif

            <div class="row">
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group input-group-sm">
                                <label>Cabang: <em class="text-danger">*</em></label>
                                <select name="cabang" class="form-control select2" style="width: 100%;"
                                    @if(!empty($dataE)) data-selected="{{ $dataE->cabang_id }}" @endif
                                    form="formProduk"></select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group input-group-sm">
                                <label>Kategori: <em class="text-danger">*</em></label>
                                <select name="kategori" class="form-control select2" style="width: 100%;"
                                    @if(!empty($dataE)) data-selected="{{ $dataE->kategori_id }}" @endif
                                    form="formProduk"></select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Nama: <em class="text-danger">*</em></label>
                        <div class="input-group input-group-sm date">
                            <div class="input-group-addon">
                                <i class="fa fa-tag"></i>
                            </div>
                            <input type="text" name="nama" @if(!empty($dataE)) value="{{ $dataE->nama }}" @endif
                                class="form-control" placeholder="Nama..." form="formProduk">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Harga Beli: <em class="text-danger">*</em></label>
                                <div class="input-group input-group-sm date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-dollar"></i>
                                    </div>
                                    <input type="rupiah" name="harga_beli" @if(!empty($dataE))
                                        value="{{ rupiahFormat($dataE->harga_beli) }}" @endif class="form-control"
                                        placeholder="Harga Beli..." form="formProduk">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Harga Jual: <em class="text-danger">*</em></label>
                                <div class="input-group input-group-sm date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-dollar"></i>
                                    </div>
                                    <input type="rupiah" name="harga_jual" @if(!empty($dataE))
                                        value="{{ rupiahFormat($dataE->harga_jual) }}" @endif class="form-control"
                                        placeholder="Harga Jual..." form="formProduk">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <div class="form-group">
                                <label>Harga Jual Member: <em class="text-danger">*</em></label>
                                <div class="input-group input-group-sm date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-dollar"></i>
                                    </div>
                                    <input type="rupiah" name="harga_jual_member" @if(!empty($dataE))
                                        value="{{ rupiahFormat($dataE->harga_jual_member) }}" @endif
                                        class="form-control" placeholder="Harga Jual Member..." form="formProduk">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Stok Opname: <em class="text-danger">*</em></label>
                                <div class="input-group input-group-sm date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-database"></i>
                                    </div>
                                    <input type="text" name="stok" @if(!empty($dataE)) disabled readonly
                                        value="{{ rupiahFormat($dataE->stok) }}" @endif class="form-control"
                                        placeholder="Stok Opname..." form="formProduk">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-7">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#keterangan" data-toggle="tab" aria-expanded="false">Keterangan</a></li>
                            <li>
                                <a href="#gambar" data-toggle="tab" aria-expanded="false">Gambar</a></li>
                            <?php /* <li>
                                <a href="#retouch" data-toggle="tab" aria-expanded="false">Retouch</a></li> */ ?>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="keterangan">
                                <div class="form-group input-group-sm">
                                    <textarea name="keterangan" cols="15" rows="4" class="form-control add-style"
                                        style="height:120px;" placeholder="Keterangan..."
                                        form="formProduk">{{ (!empty($dataE->keterangan) ? $dataE->keterangan : null) }}</textarea>
                                </div>
                            </div>
                            <div class="tab-pane" id="gambar">
                                <div class="form-group">
                                    <div style="width:100%;height: 170px; border: 1px solid whitesmoke ;text-align: center;position: relative"
                                        id="image">
                                        <img width="100%" height="100%" id="preview_image"
                                            src="{{ (!empty($dataE->gambar) ? 'storage/master-data/product/uploads/' . $dataE->gambar : asset('images/noimage.jpg') ) }}" />
                                    </div>
                                    <div class="row mt-5">
                                        <div class="col-md-6 col-xs-6 col-sm-6">
                                            <a class="btn btn-xs btn-default col-md-12 col-xs-12 col-sm-12"
                                                role="button" href="javascript:changePicture()">
                                                <i class="fa fa-upload text-info"></i> </a>
                                        </div>
                                        <div class="col-md-6 col-xs-6 col-sm-6">
                                            <a class="btn btn-xs btn-default col-md-12 col-xs-12 col-sm-12"
                                                role="button" href="javascript:removeFile()">
                                                <i class="fa fa-trash text-danger"></i> </a>
                                        </div>
                                    </div>

                                    <input type="file" id="file" name="gambar" class="hide" form="formProduk" />
                                    <input type="hidden" id="file_name" form="formProduk" @if(!empty($dataE))
                                        name="old_img" value="{{ $dataE->gambar }}" @endif />
                                </div>
                            </div>
                            <?php /* <div class="tab-pane" id="retouch">
                                <div class="form-group row col-xs-4">
                                    <label>Waktu <small><sup>(Hari)</sup></small> :</label>
                                    <div class="input-group input-group-sm date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                        <input type="number" min="1" step="1" name="retouch_waktu" @if(!empty($dataE))
                                            value="{{ $dataE->retouch_waktu }}" @endif class="form-control"
                                            placeholder="Waktu..." form="formProduk">
                                    </div>
                                </div>
                                <div class="form-group input-group-sm">
                                    <textarea name="retouch_detail" cols="15" rows="4" class="form-control add-style"
                                        style="height:120px;" placeholder="Detail Syarat & Kententuan berlaku..."
                                        form="formProduk">{{ (!empty($dataE->retouch_detail) ? $dataE->retouch_detail : null) }}</textarea>
                                </div>
                            </div> */ ?>
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
                    <button type="submit" class="btn btn-info col-md-12 col-xs-12 col-sm-12" form="formProduk"><em
                            class="fa fa-envelope"></em> Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
