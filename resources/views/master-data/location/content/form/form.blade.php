<form action="{{ $action }}" id="formLokasi"></form>
@if(!empty($dataE))
<input type="hidden" name="id" value="{{ $dataE->id }}" form="formLokasi">
@endif
@csrf

<div class="form-group input-group-sm">
    <label>Cabang: <em class="text-danger">*</em></label>
    <select name="cabang" class="form-control select2" style="width: 100%;" form="formLokasi" @if(!empty($dataE))
        data-selected="{{ $dataE->cabang_id }}" @endif></select>
</div>
<div class="form-group">
    <label>Lokasi: <em class="text-danger">*</em></label>
    <div class="input-group input-group-sm date">
        <div class="input-group-addon">
            <i class="fa fa-tag"></i>
        </div>
        <input type="text" name="nama" @if(!empty($dataE)) value="{{ $dataE->nama }}" @endif class="form-control"
            placeholder="Nama..." form="formLokasi">
    </div>
</div>
