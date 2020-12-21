<form action="{{ $action }}" id="formBrand"></form>
@if(!empty($dataE))
<input type="hidden" name="id" value="{{ $dataE->id }}" form="formBrand">
@endif
@csrf

<div class="form-group">
    <div style="width:100%; height: 185px; text-align: center; position: relative" id="image">
        <img width="100%" height="100%" id="preview_image"
            src="{{ (!empty($dataE->gambar) ? 'storage/master-data/brand/uploads/' . $dataE->gambar : asset('images/noimage.jpg') ) }}" />
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

    <input type="file" id="file" name="gambar" class="hide" form="formBrand" />
    <input type="hidden" id="file_name" form="formBrand" @if(!empty($dataE)) name="old_img" value="{{ $dataE->gambar }}"
        @endif />
</div>

<div class="form-group">
    <label>Nama: <em class="text-danger">*</em></label>
    <div class="input-group input-group-sm date">
        <div class="input-group-addon">
            <i class="fa fa-tag"></i>
        </div>
        <input type="text" name="nama" @if(!empty($dataE)) value="{{ $dataE->nama }}" @endif class="form-control"
            placeholder="Nama..." form="formBrand">
    </div>
</div>
