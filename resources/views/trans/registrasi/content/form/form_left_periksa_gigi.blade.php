<div class="row">
    <div class="col-lg-12">

        <div class="row">
            <div class="col-md-8 col-xs-12 col-sm-12">
                <div class="form-group input-group-sm on-c-gigi-chg">
                    <label class="label-gigi">Pilih gigi yang telah diperiksa</label>
                    <div data-gigi="permanen" class="opt-gigi active-gigi">Gigi Permanen</div>
                    <div data-gigi="susu" class="opt-gigi">Gigi Susu</div>
                </div>
                <input type="hidden" readonly name="gigi" class="form-control opt-gigi-value" form="formPeriksa">
            </div>
        </div>

        <div class="load-content-gigi-img">
            <em class='fa fa-spin fa-spinner'></em> Loading...
        </div>

        <div class="row mt-10">
            <div class="col-md-12">
                <h4>Pemeriksaan Gigi: </h4>
                <div class="cont-tindakan"> </div>
            </div>
        </div>

        <hr style="border: 2px solid #F2F2F2;">
        <h4>Tambahan Data Untuk Pasien: </h4>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="mt-10">Keterangan:</label>
                    <div>
                        <textarea name="ringkasan_gigi" class="form-control add-style-gigi"
                            placeholder="Tuliskan catatan tambahan..." form="formPeriksa"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="mt-10">Pratinjau File:</label>
                    <div style="width:100%; height: 450px; text-align: center; position: relative" id="image_gigi">
                        <img width="100%" height="100%" id="preview_image_gigi"
                            src="{{ asset('images/noimage.jpg') }}" />
                    </div>
                    <div class="row mt-5">
                        <div class="col-md-6 col-xs-6 col-sm-6">
                            <a class="btn btn-xs btn-default col-md-12 col-xs-12 col-sm-12" role="button"
                                href="javascript:loadFile()">
                                <i class="fa fa-upload text-info"></i> </a>
                        </div>
                        <div class="col-md-6 col-xs-6 col-sm-6">
                            <a class="btn btn-xs btn-default col-md-12 col-xs-12 col-sm-12" role="button"
                                href="javascript:removeFile()">
                                <i class="fa fa-trash text-danger"></i> </a>
                        </div>
                    </div>

                    <input type="file" id="file_gigi" name="gambar_gigi" class="hide" form="formPeriksa" />
                    <input type="hidden" id="file_gigi_name" form="formPeriksa" name="old_img_gigi" />
                </div>
            </div>
        </div>

    </div>
</div>
