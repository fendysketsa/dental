<form action="{{ $action }}" id="formKategori"></form>
@if(!empty($dataE))
<input type="hidden" name="id" value="{{ $dataE->id }}" form="formKategori">
@endif
@csrf
<div class="row">
    <div class="col-lg-3">
        <div class="form-group">
            <label>Icon <em class="text-danger">*</em></label>
            <div style="width:80px; height: 80px; text-align: center; position: relative" id="image_icon">
                <img width="100%" height="100%" id="preview_image_icon"
                    src="{{ (!empty($dataE->icon) ? 'storage/master-data/category/uploads/icon/' . $dataE->icon : asset('images/noimage.jpg') ) }}" />
            </div>
            <div class="row mt-5">
                <div class="col-md-6 col-xs-6 col-sm-6">
                    <a class="btn btn-xs btn-default col-md-12 col-xs-12 col-sm-12" role="button"
                        href="javascript:changeProfileIcon()">
                        <i class="fa fa-upload text-info"></i> </a>
                </div>
                <div class="col-md-6 col-xs-6 col-sm-6">
                    <a class="btn btn-xs btn-default col-md-12 col-xs-12 col-sm-12" role="button"
                        href="javascript:removeFileIcon()">
                        <i class="fa fa-trash text-danger"></i> </a>
                </div>
            </div>

            <input type="file" id="file_icon" name="icons" class="hide" form="formKategori" />
            <input type="hidden" id="file_name_icon" form="formKategori" @if(!empty($dataE)) name="old_img_icon"
                value="{{ $dataE->icon }}" @endif />
        </div>
    </div>
    <div class="col-md-9">
        <div class="form-group">
            <label>Nama: <em class="text-danger">*</em></label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-tag"></i>
                </div>
                <input type="text" name="nama" @if(!empty($dataE)) value="{{ $dataE->nama }}" @endif
                    class="form-control" placeholder="Nama..." form="formKategori">
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="form-group">
            <label>Slug: <em class="text-danger">*</em></label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-tag"></i>
                </div>
                <input type="text" name="slug" maxlength="10" @if(!empty($dataE)) value="{{ $dataE->slug }}" @endif
                    class="form-control" placeholder="Slug..." form="formKategori">
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-4 col-xs-3 col-sm-3">
        <div class="form-group input-group-sm">
            <label>Jenis: <em class="text-danger">*</em></label>
            <label class="container-radio"> Layanan
                <input type="radio" name="jenis" @if(!empty($dataE) && $dataE->jenis == 1) checked="checked" @else
                checked="checked" @endif value="1" form="formKategori">
                <span class="checkmark-radio"></span>
            </label>
        </div>
    </div>
    <div class="col-md-4 col-xs-3 col-sm-3">
        <div class="form-group input-group-sm">
            <label>&nbsp;</label>
            <label class="container-radio"> Produk
                <input type="radio" name="jenis" @if(!empty($dataE) && $dataE->jenis == 2) checked="checked" @endif
                value="2" form="formKategori">
                <span class="checkmark-radio"></span>
            </label>
        </div>
    </div>
</div>
<div class="form-group">
    <label>Keterangan:</label>
    <div class="input-group input-group-sm date">
        <div class="input-group-addon">
            <i class="fa fa-edit"></i>
        </div>
        <textarea name="keterangan" class="form-control add-style" placeholder="Keterangan..." style="height:50px;"
            form="formKategori">{{ (!empty($dataE) ? $dataE->keterangan : null) }}</textarea>
    </div>
</div>
