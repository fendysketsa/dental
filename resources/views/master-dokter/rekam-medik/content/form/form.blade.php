<form action="{{ $action }}" id="formRekam"></form>
@if(!empty($dataE))
<input type="hidden" name="id" value="{{ $dataE->id }}" form="formRekam">
<div data-status="{{ $dataE->status }}" data-input="{{ $dataE->more_input }}"
    data-input-placeholder="{{ $dataE->more_input_placeholder }}" data-input-label="{{ $dataE->more_input_label }}"
    data-set-input="{{ $dataE->set_input }}" class="edit-rekam"></div>
@endif
@csrf

<div class="form-group input-group-sm">
    <label class="container-radio" for="set_input"> Set Input
        <input type="checkbox" id="set_input" name="set_input" checked="checked" value="1" class="form-control"
            form="formRekam">
        <span class="set-input-desc">Single</span>
        <span class="checkmark-radio"></span>
    </label>
    <small id="emailHelp" class="form-text text-info"><em class="fa fa-info-circle"></em> Untuk mengeset single / multi
        choise</small>
</div>

<div class="form-group">
    <label>Pertanyaan: <em class="text-danger">*</em></label>
    <div class="input-group input-group-sm date">
        <div class="input-group-addon">
            <i class="fa fa-tag"></i>
        </div>
        <input type="text" name="nama" @if(!empty($dataE)) value="{{ $dataE->nama }}" @endif class="form-control"
            placeholder="Pertanyaan..." form="formRekam">
    </div>
</div>

<div class="form-group">
    <label>Pilihan: <em class="text-danger">*</em></label>
    <div class="input-group input-group-sm date">
        <div class="input-group-addon">
            <i class="fa fa-edit"></i>
        </div>
        <textarea name="pilihan" class="form-control add-style" placeholder="Pilihan..." style="height:100px;"
            form="formRekam">{{ (!empty($dataE) ? $dataE->option : null) }}</textarea>
    </div>
    <small id="emailHelp" class="form-text text-info"><em class="fa fa-info-circle"></em> Isikan pilihan,
        tekan Enter untuk memisahkan pilihan apabila lebih dari satu.</small>
</div>

<div class="row">
    <div class="col-md-6 col-xs-6 col-sm-6">
        <div class="form-group input-group-sm">
            <label class="container-radio" for="tambahan"> Tambahan Input
                <input type="checkbox" id="tambahan" name="tambahan_input" value="1" class="form-control"
                    form="formRekam">
                <span class="checkmark-radio"></span>
            </label>
        </div>
    </div>

    <div class="col-md-6 col-xs-6 col-sm-6">
        <div class="form-group input-group-sm">
            <label class="container-radio" for="status"> Status
                <input type="checkbox" id="status" name="status" checked="checked" value="1" class="form-control"
                    form="formRekam">
                <span class="status-desc">Aktif</span>
                <span class="checkmark-radio"></span>
            </label>
        </div>
    </div>


</div>

<div class="more-placeholder"></div>
