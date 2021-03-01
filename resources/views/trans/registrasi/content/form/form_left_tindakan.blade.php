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
                                    <select name="diagnosis" class="form-control select2-tindakan" style="width: 100%;"
                                        form="formPeriksa"></select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group input-group-sm">
                                    <label>Tindakan: <em class="text-danger">*</em></label>
                                    <select name="tindakan" class="form-control select2-tindakan" style="width: 100%;"
                                        form="formPeriksa"></select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Catatan Tambahan:</label>
                            <div class="input-group input-group-sm date">
                                <div class="input-group-addon">
                                    <i class="fa fa-edit"></i>
                                </div>
                                <textarea name="more_catatan" class="form-control add-style-ta" id="more_catatan"
                                    placeholder="Catatan Tambahan..." form="formPeriksa"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Upload Foto Gigi (Optional)</label>
                            <div style="width:100%;height: 220px; border: 1px solid whitesmoke ;text-align: center;position: relative"
                                id="image_tindakan">
                                <img width="100%" height="100%" id="preview_image_tindakan"
                                    src="{{ ('/images/noimage.jpg') }}" />
                            </div>
                            <div class="row mt-5">
                                <div class="col-md-6 col-xs-6 col-sm-6">
                                    <a class="btn btn-xs btn-default col-md-12 col-xs-12 col-sm-12" role="button"
                                        href="javascript:loadFileTindakan()">
                                        <i class="fa fa-upload text-info"></i> </a>
                                </div>
                                <div class="col-md-6 col-xs-6 col-sm-6">
                                    <a class="btn btn-xs btn-default col-md-12 col-xs-12 col-sm-12" role="button"
                                        href="javascript:removeFileTindakan()">
                                        <i class="fa fa-trash text-danger"></i> </a>
                                </div>
                            </div>

                            <input type="file" id="file_tindakan" name="gambar_tindakan" class="hide"
                                form="formPeriksa" />
                            <input type="hidden" id="file_name_tindakan" form="formPeriksa" name="old_img_tindakan"
                                value="" />
                        </div>
                    </div>
                    <div class=" button-action box-footer bg-gray-light hide">
                        <div class="row">
                            <div class="col-md-6 col-xs-6 col-sm-6">
                                <a role="button"
                                    class="btn btn-warning col-md-12 col-xs-12 col-sm-12 cancel-form-tindakan"><em
                                        class="fa fa-undo"></em>
                                    Cancel</a>
                            </div>
                            <div class="col-md-6 col-xs-6 col-sm-6">
                                <button type="submit"
                                    class="btn btn-info col-md-12 col-xs-12 col-sm-12 save-form-tindakan"
                                    form="formPeriksa"><em class="fa fa-envelope"></em> Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
