<form action="{{ $action }}" id="formVoucher"></form>
@csrf

<div class="load-form-modal">
    <div class="modal-content">
        <div class="modal-header bg-warning ">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-center"></h4>
        </div>
        <div class="modal-body">
            @if(!empty($dataE))
            <input type="hidden" name="id" value="{{ $dataE['id'] }}" form="formVoucher">
            @endif
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nama: <em class="text-danger">*</em></label>
                        <div class="input-group input-group-sm date">
                            <div class="input-group-addon">
                                <i class="fa fa-tag"></i>
                            </div>
                            <input type="text" name="nama" @if(!empty($dataE)) value="{{ $dataE['nama'] }}" @endif
                                class="form-control" placeholder="Nama..." form="formVoucher">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group input-group-sm">
                                <label>Treatment: <em class="text-danger">*</em></label>
                                <select name="layanan[]" class="form-control select2-multiple layanan"
                                    multiple="multiple" style="width: 100%;" form="formVoucher" @if(!empty($dataE))
                                    data-selected="{{ $dataE['layanan'] }}" @endif></select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Diskon %: <em class="text-danger">*</em></label>
                                <div class="input-group input-group-sm date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-cut"></i>
                                    </div>
                                    <input type="number" name="diskon" @if(!empty($dataE))
                                        value="{{ $dataE['diskon'] }}" @endif class="form-control" min="1" max="100"
                                        placeholder="Diskon..." form="formVoucher">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Berlaku ? <em class="text-danger">*</em></label>
                                <div class="input-group box"
                                    style="border: 3px solid #d2d6de !important; margin-bottom:0px;">
                                    <div class="col-xs-6">
                                        <div class="row input-group input-group-sm rodok-lebar">
                                            <div class="input-group-addon add-on-daterpicker bg-green">
                                                <i class="fa fa-calendar-check-o"></i>
                                            </div>
                                            <input type="text" name="berlaku_dari" @if(!empty($dataE))
                                                value="{{ date('d-m-Y', strtotime($dataE['berlaku_dari'])) }}" @endif
                                                class="form-control" placeholder="Dari..." readonly=""
                                                form="formVoucher">
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="row input-group input-group-sm date group-date-range rodok-lebar">
                                            <div class="input-group-addon bg-gray">
                                                -
                                            </div>
                                            <input type="text" name="berlaku_sampai" @if(!empty($dataE))
                                                value="{{ date('d-m-Y', strtotime($dataE['berlaku_sampai'])) }}" @endif
                                                class="form-control daterpicker" placeholder="Sampai..." readonly=""
                                                form="formVoucher">
                                            <div class="input-group-addon remove-on-daterpicker bg-gray">
                                                <i class="fa fa-calendar-times-o"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#keterangan" data-toggle="tab" aria-expanded="false">Keterangan</a></li>
                            <li>
                                <a href="#gambar" data-toggle="tab" aria-expanded="false">Gambar</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="keterangan">
                                <div class="input-group-sm">
                                    <textarea name="keterangan" cols="15" rows="4" class="form-control add-style"
                                        style="height:150px;" placeholder="Keterangan..."
                                        form="formVoucher">{{ (!empty($dataE->keterangan) ? $dataE['keterangan'] : null)  }}</textarea>
                                </div>
                            </div>
                            <div class="tab-pane" id="gambar">
                                <div class="form-group">
                                    <div style="width:100%;height: 110px; border: 1px solid whitesmoke ;text-align: center;position: relative"
                                        id="image">
                                        <img width="100%" height="100%" id="preview_image"
                                            src="{{ (!empty($dataE['gambar']) ? 'storage/master-data/voucher/uploads/' . $dataE['gambar'] : asset('images/noimage.jpg') ) }}" />
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

                                    <input type="file" id="file" name="gambar" class="hide" form="formVoucher" />
                                    <input type="hidden" id="file_name" form="formVoucher" @if(!empty($dataE))
                                        name="old_img" value="{{ $dataE['gambar'] }}" @endif />
                                </div>
                            </div>
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
                    <button type="submit" class="btn btn-info col-md-12 col-xs-12 col-sm-12" form="formVoucher"><em
                            class="fa fa-envelope"></em> Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
