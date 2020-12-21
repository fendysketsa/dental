<form action="{{ $action }}" id="formSetModal"></form>
@if(!empty($dataE))
<input type="hidden" name="id" value="{{ $dataE->id }}" form="formSetModal">
@endif
@csrf

<div class="form-group input-group-sm">
    <label>Shift: <em class="text-danger">*</em></label>
    <select @if(!empty($dataE)) disabled @endif name="shift" class="form-control select2" style="width: 100%;"
        @if(!empty($dataE)) data-selected="{{ $dataE->shift_id }}" @endif form="formSetModal"></select>
</div>

<div class="form-group">
    <label>Modal Nominal: <em class="text-danger">*</em></label>
    <div class="input-group input-group-sm date">
        <div class="input-group-addon">
            <i class="fa fa-tag"></i>
        </div>
        <input type="text" name="nominal" @if(!empty($dataE)) value="{{ rupiahFormat($dataE->nominal) }}" @endif
            class="form-control" placeholder="Modal nominal..." form="formSetModal">
    </div>
</div>
