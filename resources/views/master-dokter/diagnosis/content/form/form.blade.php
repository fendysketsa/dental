<form action="{{ $action }}" id="formDiagnosis"></form>
@if(!empty($dataE))
<input type="hidden" name="id" value="{{ $dataE->id }}" form="formDiagnosis">
<div data-status="{{ $dataE->status }}" class="edit-diagnosis"></div>
@endif
@csrf
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label>Nama: <em class="text-danger">*</em></label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-tag"></i>
                </div>
                <input type="text" name="nama" @if(!empty($dataE)) value="{{ $dataE->nama }}" @endif
                    class="form-control" placeholder="Nama..." form="formDiagnosis">
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12">
        <div class="form-group input-group-sm">
            <label class="container-radio" for="status"> Status
                <input type="checkbox" id="status" name="status" @if(!empty($dataE) && $dataE->status == 1)
                checked="checked" @else
                checked="checked" @endif value="1" form="formDiagnosis">
                <span class="checkmark-radio"></span>
                <span class="status-desc">Aktif</span>
            </label>
        </div>
    </div>
</div>
