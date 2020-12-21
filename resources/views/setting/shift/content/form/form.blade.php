<form action="{{ $action }}" id="formSetShift"></form>
@if(!empty($dataE))
<input type="hidden" name="id" value="{{ $dataE->id }}" form="formSetShift">
@endif
@csrf

<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label>Nama: <em class="text-danger">*</em></label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-tag"></i>
                </div>
                <input type="text" name="nama" @if(!empty($dataE)) value="{{ $dataE->nama }}" @endif
                    class="form-control" placeholder="Nama..." form="formSetShift">
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Label:</label>
            <div id="cpLabelShift" class="input-group colorpicker-component input-group-sm date">
                <input type="text" readonly name="label" @if(!empty($dataE)) value="{{ $dataE->label }}" @else value="#000" @endif
                    class="form-control" placeholder="Nama..." form="formSetShift">
                <span class="input-group-addon"><i></i></span>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Jam Awal: <em class="text-danger">*</em></label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                </div>
                <input type="text" name="jam_awal" @if(!empty($dataE))
                    value="{{ date('H:i', strtotime($dataE->jam_awal)) }}" @endif class="form-control timepicker"
                    placeholder="Jam Awal..." readonly form="formSetShift">
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Jam Akhir: <em class="text-danger">*</em></label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                </div>
                <input type="text" name="jam_akhir" @if(!empty($dataE))
                    value="{{ date('H:i', strtotime($dataE->jam_akhir)) }}" @endif class="form-control timepicker"
                    placeholder="Jam Akhir..." readonly form="formSetShift">
            </div>
        </div>
    </div>
</div>
