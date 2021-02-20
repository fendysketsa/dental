<form action="{{ $action }}" id="formRegistrasi"></form>
@if(!empty($data))
<input type="hidden" name="id" value="{{ $data[0]->id }}" data-member-id="{{ $data[0]->member_id }}"
    form="formRegistrasi">
@endif

<div class="modal-content">
    <div class="modal-header bg-success ">
        <button type="button" class="close mod-vol-1" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center">Form Tindakan | Pemerikaan Gigi</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info display-future">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group input-group-sm">
                                    <label>Diagnosis: <em class="text-danger">*</em></label>
                                    <select name="cabang" class="form-control select2" style="width: 100%;"
                                        form="formProduk"></select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group input-group-sm">
                                    <label>Tindakan: <em class="text-danger">*</em></label>
                                    <select name="kategori" class="form-control select2" style="width: 100%;"
                                        form="formProduk"></select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Catatan Tambahan:</label>
                            <div class="input-group input-group-sm date">
                                <div class="input-group-addon">
                                    <i class="fa fa-edit"></i>
                                </div>
                                <textarea name="deskripsi" class="form-control add-style-ta" id="deskripsi"
                                    placeholder="Catatan Tambahan..." form="formLayanan"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Upload Foto Gigi (Optional)</label>
                            <div style="width:100%;height: 220px; border: 1px solid whitesmoke ;text-align: center;position: relative"
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
                    <div class="button-action box-footer bg-gray-light hide">
                        <div class="row">
                            <div class="col-md-6 col-xs-6 col-sm-6">
                                <a role="button" class="btn btn-warning col-md-12 col-xs-12 col-sm-12 cancel-form"><em
                                        class="fa fa-undo"></em>
                                    Cancel</a>
                            </div>
                            <div class="col-md-6 col-xs-6 col-sm-6">
                                <button type="submit" class="btn btn-info col-md-12 col-xs-12 col-sm-12"
                                    form="formSlide"><em class="fa fa-envelope"></em> Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
