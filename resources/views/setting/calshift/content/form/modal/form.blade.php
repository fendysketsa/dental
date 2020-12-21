<form action="{{ $action }}" id="formSettingKalenderShift"></form>
@csrf

<div class="load-form-modal">
    <div class="modal-content">
        <div class="modal-header bg-warning ">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-center"></h4>
        </div>
        <div class="modal-body">
            <input type="hidden" readonly name="idKal" form="formSettingKalenderShift">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Tanggal: <em class="text-danger">*</em></label>
                        <div class="input-group input-group-sm date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" readonly name="tanggal" class="form-control kal-shf-peg"
                                placeholder="Tanggal..." form="formSettingKalenderShift">
                        </div>
                    </div>
                    <div class="form-group input-group-sm">
                        <label>Shift: <em class="text-danger">*</em></label>
                        <select name="shift" class="form-control select2" style="width: 100%;"
                            form="formSettingKalenderShift"></select>
                    </div>
                    <div id="noEdit" class="form-group input-group-sm">
                        <label>Pegawai: <em class="text-danger">*</em></label>
                        <select name="pegawai" class="form-control select2" style="width: 100%;"
                            form="formSettingKalenderShift"></select>
                    </div>
                    <div class="form-check ajukan-ijin hide">
                        <input type="checkbox" name="ijin" disabled class="form-check-input" id="ch-ijin">
                        <label class="form-check-label" for="ch-ijin">Ajukan Ijin ?</label>
                    </div>
                    <div class="form-group ajukan-keterangan hide">
                        <div class="input-group input-group-sm date col-lg-12">
                            <textarea name="keterangan" disabled class="form-control add-style keterangan" disabled
                                placeholder="Keterangan..." style="height:50px;"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer bg-gray-light">
            <div class="row">
                <div class="col-md-6 col-xs-6 col-sm-6">
                    <a role="button" data-dismiss="modal"
                        class="btn btn-warning col-md-12 col-xs-12 col-sm-12 cancel-form-modal"><em
                            class="fa fa-undo"></em> Cancel</a>
                </div>
                <div class="col-md-6 col-xs-6 col-sm-6">
                    <button type="submit" class="btn btn-info col-md-12 col-xs-12 col-sm-12"
                        form="formSettingKalenderShift"><em class="fa fa-envelope"></em> Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
