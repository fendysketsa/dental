<form action="{{ $action }}" id="formRoom"></form>
@if(!empty($dataE))
<input type="hidden" name="id" value="{{ $dataE->id }}" form="formRoom">
@endif
@csrf

<div class="input-template" style="padding-top: .5rem; margin-bottom:15px;" @if(!empty($dataEImage))
    data-images="{{ $dataEImage }}" @endif></div>

<div class="form-group input-group-sm">
    <label>Cabang: <em class="text-danger">*</em></label>
    <select name="cabang" class="form-control select2" style="width: 100%;" form="formRoom" @if(!empty($dataE))
        data-selected="{{ $dataE->branch_id }}" @endif></select>
</div>
<div class="row">
    <div class="col-lg-7">
        <div class="form-group">
            <label>Nama: <em class="text-danger">*</em></label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-tag"></i>
                </div>
                <input type="text" name="nama" @if(!empty($dataE)) value="{{ $dataE->name }}" @endif
                    class="form-control" placeholder="Nama..." form="formRoom">
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="form-group">
            <label>Harga: <em class="text-danger">*</em></label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-money"></i>
                </div>
                <input type="rupiah" name="harga" @if(!empty($dataE)) value="{{ rupiahFormat($dataE->price) }}" @endif
                    class="form-control" placeholder="Harga..." form="formRoom">
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
        <textarea name="keterangan" class="form-control add-style" placeholder="Keterangan..." style="height:50px;"
            form="formRoom">{{ (!empty($dataE) ? $dataE->description : null) }}</textarea>
    </div>
</div>
