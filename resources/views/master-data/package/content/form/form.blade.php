<form action="{{ $action }}" id="formPaket"></form>
@if(!empty($dataE))
<input type="hidden" name="id" value="{{ $dataE['id'] }}" form="formPaket">
@endif
@csrf

<div class="form-group">
    <div style="width:100%; height: 185px; text-align: center; position: relative" id="image">
        <img width="100%" height="100%" id="preview_image"
            src="{{ (!empty($dataE['gambar']) ? 'storage/master-data/package/uploads/' . $dataE['gambar'] : asset('images/noimage.jpg') ) }}" />
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

    <input type="file" id="file" name="gambar" class="hide" form="formPaket" />
    <input type="hidden" id="file_name" form="formPaket" @if(!empty($dataE)) name="old_img"
        value="{{ $dataE['gambar'] }}" @endif />
</div>

<div class="form-group input-group-sm">
    <label>Cabang: <em class="text-danger">*</em></label>
    <select name="cabang" class="form-control select2 cabang" style="width: 100%;" form="formPaket" @if(!empty($dataE))
        data-selected="{{ $dataE['cabang_id'] }}" @endif></select>
</div>

<div class="row">
    <div class="col-md-7">
        <div class="form-group">
            <label>Nama: <em class="text-danger">*</em></label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-tag"></i>
                </div>
                <input type="text" name="nama" @if(!empty($dataE)) value="{{ $dataE['nama'] }}" @endif
                    class="form-control" placeholder="Nama..." form="formPaket">
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="form-group">
            <label>Harga: <em class="text-danger">*</em></label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-dollar"></i>
                </div>
                <input type="rupiah" name="harga" @if(!empty($dataE)) value="{{ rupiahFormat($dataE['harga']) }}" @endif
                    class="form-control" placeholder="Harga..." form="formPaket">
            </div>
        </div>
    </div>
</div>

<div class="form-group input-group-sm">
    <label>Layanan: <em class="text-danger">*</em></label>
    <select name="layanan[]" class="form-control select2-multiple layanan" multiple="multiple" style="width: 100%;"
        form="formPaket" @if(!empty($dataE)) data-selected="{{ $dataE['layanan'] }}" @endif></select>
</div>

<div class="form-group">
    <label>Keterangan:</label>
    <div class="input-group input-group-sm date">
        <div class="input-group-addon">
            <i class="fa fa-edit"></i>
        </div>
        <textarea name="keterangan" class="form-control add-style" style="height:60px;" placeholder="Keterangan..."
            form="formPaket">{{ (!empty($dataE) ? $dataE['keterangan'] : null) }}</textarea>
    </div>
</div>
