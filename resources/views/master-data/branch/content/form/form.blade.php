<form action="{{ $action }}" id="formCabang"></form>
@if(!empty($dataE))
<input type="hidden" name="id" value="{{ $dataE->id }}" form="formCabang">
@endif
@csrf

<div class="form-group">
    <label>Nama: <em class="text-danger">*</em></label>
    <div class="input-group input-group-sm date">
        <div class="input-group-addon">
            <i class="fa fa-tag"></i>
        </div>
        <input type="text" @if(!empty($dataE)) value="{{ $dataE->nama }}" @endif name="nama" class="form-control"
            placeholder="Nama..." form="formCabang">
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Kode: <em class="text-danger">*</em></label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-code"></i>
                </div>
                <input type="text" @if(!empty($dataE)) value="{{ $dataE->kode }}" @endif name="kode" maxlength="4"
                    class="form-control" placeholder="Kode..." form="formCabang">
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Telepon: <em class="text-danger">*</em></label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-phone"></i>
                </div>
                <input type="text" @if(!empty($dataE)) value="{{ $dataE->telepon }}" @endif name="telepon"
                    class="form-control" placeholder="Telepon..." form="formCabang">
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <label>Alamat:</label>
    <div class="input-group input-group-sm date">
        <div class="input-group-addon">
            <i class="fa fa-home"></i>
        </div>
        <textarea name="alamat" class="form-control add-style" placeholder="Alamat..." style="height:40px;"
            form="formCabang">{{ (!empty($dataE) ? $dataE->alamat : null) }}</textarea>
    </div>
</div>
