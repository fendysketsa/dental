<form action="{{ $action }}" id="formPegawai"></form>
@if(!empty($dataE))
<input type="hidden" name="id" value="{{ $dataE['id'] }}" data-email="{{ $dataE['email'] }}"
    data-cabang-lain="[{{ $dataE['cabangLain'] }}]" data-role="{{ $dataE['role'] }}"
    data-pegawai="[{{ $dataE['pegawai'] }}]" data-komisi="{{ $dataE['komisi'] }}" form="formPegawai">
@endif
@csrf

<div class="row">
    <div class="col-md-12 col-xs-12 colom-geser">
        <div class="form-group input-group-sm">
            <label>Cabang: <em class="text-danger">*</em></label>
            <select name="cabang" class="form-control select2" style="width: 100%;" form="formPegawai"
                @if(!empty($dataE)) data-selected="{{ $dataE['cabang_id'] }}" @endif></select>
        </div>
    </div>
    <div id="load-komisi"></div>
</div>

<div class="row">
    <div class="col-md-5">
        <div class="form-group">
            <div style="width:100%; height: 185px; text-align: center; position: relative" id="image">
                <img width="100%" height="100%" style="border-radius:50%;" id="preview_image"
                    src="{{ (!empty($dataE['foto']) ? 'storage/master-data/employee/uploads/' . $dataE['foto'] : asset('images/noimage.jpg') ) }}" />
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

            <input type="file" id="file" name="foto" class="hide" form="formPegawai" />
            <input type="hidden" id="file_name" form="formPegawai" @if(!empty($dataE)) name="old_img"
                value="{{ $dataE['foto'] }}" @endif />
        </div>
    </div>
    <div class="col-md-7">
        <div class="form-group">
            <label>Nama: <em class="text-danger">*</em></label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-tag"></i>
                </div>
                <input type="text" name="nama" @if(!empty($dataE)) value="{{ $dataE['nama'] }}" @endif
                    class="form-control" placeholder="Nama..." form="formPegawai">
            </div>
        </div>
        <div class="form-group">
            <label>Jabatan: <em class="text-danger">*</em></label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-coffee"></i>
                </div>
                <input type="text" name="jabatan" @if(!empty($dataE)) value="{{ $dataE['jabatan'] }}" @endif
                    class="form-control" placeholder="Jabatan..." form="formPegawai">
            </div>
        </div>
        <div class="form-group input-group-sm">
            <label>Role: <em class="text-danger">*</em></label>
            <select name="role" class="form-control select2 role-option" style="width: 100%;" form="formPegawai"
                @if(!empty($dataE)) data-selected="{{ $dataE['role'] }}" @endif></select>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-xs-12">
        <div class="load-more-acc"></div>
    </div>
</div>
