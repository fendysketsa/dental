<form action="{{ $action }}" id="formPromo"></form>
@if(!empty($dataE))
<input type="hidden" name="id" value="{{ $dataE->id }}" form="formPromo">
@endif
@csrf

<ul class="nav nav-tabs">
    <li class="active">
        <a href="#fpromo" data-toggle="tab" aria-expanded="true">Promo <em class="text-danger">*</em></a></li>
    <li class="">
        <a href="#fdeskripsi" data-toggle="tab" aria-expanded="false">Deskripsi</a></li>
</ul>

<div class="tab-content">
    <div class="tab-pane active" id="fpromo">
        <div class="form-group">
            <div style="width:100%; height: 185px; text-align: center; position: relative" id="image">
                <img width="100%" height="100%" id="preview_image"
                    src="{{ (!empty($dataE->gambar) ? 'storage/master-data/promo/uploads/' . $dataE->gambar : asset('images/noimage.jpg') ) }}" />
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

            <input type="file" id="file" name="gambar" class="hide" form="formPromo" />
            <input type="hidden" id="file_name" form="formPromo" @if(!empty($dataE)) name="old_img"
                value="{{ $dataE->gambar }}" @endif />
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Berlaku ? <em class="text-danger">*</em></label>
                    <div class="input-group box" style="border: 3px solid #d2d6de !important; margin-bottom:0px;">
                        <div class="col-xs-6">
                            <div class="row input-group input-group-sm">
                                <div class="input-group-addon add-on-daterpicker bg-green">
                                    <i class="fa fa-calendar-check-o"></i>
                                </div>
                                <input type="text" name="berlaku_dari" @if(!empty($dataE))
                                    value="{{ date('d-m-Y', strtotime($dataE->berlaku_dari)) }}" @endif
                                    class="form-control" placeholder="Dari..." readonly="" form="formPromo">
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="row input-group input-group-sm date group-date-range">
                                <div class="input-group-addon bg-gray">
                                    -
                                </div>
                                <input type="text" name="berlaku_sampai" @if(!empty($dataE))
                                    value="{{ date('d-m-Y', strtotime($dataE->berlaku_sampai)) }}" @endif
                                    class="form-control daterpicker" placeholder="Sampai..." readonly=""
                                    form="formPromo">
                                <div class="input-group-addon remove-on-daterpicker bg-gray">
                                    <i class="fa fa-calendar-times-o"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group input-group-sm">
                    <label>Cabang:</label>
                    <select name="cabang" class="form-control select2" style="width: 100%;" form="formPromo"
                        @if(!empty($dataE)) data-selected="{{ $dataE->cabang_id }}" @endif></select>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane" id="fdeskripsi">
        <div class="form-group">
            <label>&nbsp;</label>
            <textarea name="deskripsi" class="form-control add-style" id="deskripsi" placeholder="Deskripsi..."
                form="formPromo">{{ (!empty($dataE) ? $dataE->deskripsi : null) }}</textarea>
        </div>
    </div>
</div>
