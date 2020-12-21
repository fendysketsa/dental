<form action="{{ $action }}" id="formSupplier"></form>
@if(!empty($dataE))
<input type="hidden" name="id" value="{{ $dataE->id }}" form="formSupplier">
@endif
@csrf

<div class="form-group input-group-sm">
    <label>Cabang: <em class="text-danger">*</em></label>
    <select name="cabang" class="form-control select2" style="width: 100%;" form="formSupplier" @if(!empty($dataE))
        data-selected="{{ $dataE->cabang_id }}" @endif></select>
</div>
<div class="form-group">
    <label>Nama: <em class="text-danger">*</em></label>
    <div class="input-group input-group-sm date">
        <div class="input-group-addon">
            <i class="fa fa-tag"></i>
        </div>
        <input type="text" name="nama" @if(!empty($dataE)) value="{{ $dataE->nama }}" @endif class="form-control"
            placeholder="Nama..." form="formSupplier">
    </div>
</div>

<div class="form-group">
    <label>Alamat: <em class="text-danger">*</em></label>
    <div class="input-group input-group-sm date">
        <div class="input-group-addon">
            <i class="fa fa-home"></i>
        </div>
        <textarea name="alamat" class="form-control add-style" placeholder="Alamat..." style="height:50px;"
            form="formSupplier">{{ (!empty($dataE) ? $dataE->alamat : null) }}</textarea>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Telepon: <em class="text-danger">*</em></label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-phone"></i>
                </div>
                <input type="text" name="telepon" @if(!empty($dataE)) value="{{ $dataE->telepon }}" @endif
                    class="form-control" placeholder="Telepon..." form="formSupplier">
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Email:</label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-envelope"></i>
                </div>
                <input type="email" name="email" @if(!empty($dataE)) value="{{ $dataE->email }}" @endif
                    class="form-control" placeholder="Email..." form="formSupplier">
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <label>Keterangan:</label>
    <div class="input-group input-group-sm date">
        <div class="input-group-addon">
            <i class="fa fa-edit"></i>
        </div>
        <textarea name="keterangan" class="form-control add-style" placeholder="Keterangan..." style="height:70px;"
            form="formSupplier">{{ (!empty($dataE) ? $dataE->keterangan : null) }}</textarea>
    </div>
</div>
