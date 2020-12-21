<form action="{{ $action }}" id="formBank"></form>
@if(!empty($dataE))
<input type="hidden" name="id" value="{{ $dataE->id }}" form="formBank">
@endif
@csrf

<div class="row">
    <div class="col-md-5">
        <div class="form-group">
            <label>Kode: <em class="text-danger">*</em></label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-code"></i>
                </div>
                <input type="text" name="kode" @if(!empty($dataE)) value="{{ $dataE->kode }}" @endif
                    class="form-control" placeholder="Kode..." form="formBank">
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="form-group">
            <label>Nama: <em class="text-danger">*</em></label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-tag"></i>
                </div>
                <input type="text" name="nama" @if(!empty($dataE)) value="{{ $dataE->nama }}" @endif
                    class="form-control" placeholder="Nama..." form="formBank">
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <label>Atas Nama: <em class="text-danger">*</em></label>
    <div class="input-group input-group-sm date">
        <div class="input-group-addon">
            <i class="fa fa-tag"></i>
        </div>
        <input type="text" name="pemilik" @if(!empty($dataE)) value="{{ $dataE->pemilik }}" @endif class="form-control"
            placeholder="Atas Nama..." form="formBank">
    </div>
</div>
