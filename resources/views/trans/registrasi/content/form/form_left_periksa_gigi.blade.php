<div class="row">
    <div class="col-lg-12">

        <div class="row">
            <div class="col-md-8 col-xs-12 col-sm-12">
                <div class="form-group input-group-sm">
                    <label class="label-gigi">Pilih gigi yang telah diperiksa</label>
                    <div data-gigi="permanen" class="opt-gigi active-gigi">Gigi Permanen</div>
                    <div data-gigi="susu" class="opt-gigi">Gigi Susu</div>
                </div>
            </div>
        </div>

        <div class="load-content-gigi-img">
            <em class='fa fa-spin fa-spinner'></em> Loading...
        </div>

        <hr style="border: 2px solid #F2F2F2;">
        <h4>Tambahan Data Untuk Pasien: </h4>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="mt-10">Keterangan:</label>
                    <div>
                        <textarea name="keterangan" class="form-control add-style-gigi"
                            placeholder="Tuliskan catatan tambahan..." form="formKategori"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="mt-10">Pratinjau File:</label>
                    <div style="width:100%; height: 450px; text-align: center; position: relative" id="image">
                        <img width="100%" height="100%" id="preview_image" src="{{ asset('images/noimage.jpg') }}" />
                    </div>
                    <div class="row mt-5">
                        <div class="col-md-6 col-xs-6 col-sm-6">
                            <a class="btn btn-xs btn-default col-md-12 col-xs-12 col-sm-12" role="button"
                                href="javascript:changeProfile()">
                                <i class="fa fa-upload text-info"></i> </a>
                        </div>
                        <div class="col-md-6 col-xs-6 col-sm-6">
                            <a class="btn btn-xs btn-default col-md-12 col-xs-12 col-sm-12" role="button"
                                href="javascript:removeFile()">
                                <i class="fa fa-trash text-danger"></i> </a>
                        </div>
                    </div>

                    <input type="file" id="file" name="gambar" class="hide" form="formSlide" />
                    <input type="hidden" id="file_name" form="formSlide" @if(!empty($dataE)) name="old_img"
                        value="{{ $dataE->gambar }}" @endif />
                </div>
            </div>
        </div>

    </div>
</div>
