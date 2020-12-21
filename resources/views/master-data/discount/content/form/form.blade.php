<form action="{{ $action }}" id="formDiskon"></form>
@if(!empty($dataE))
<input type="hidden" name="id" value="{{ $dataE['id'] }}" form="formDiskon">
@endif
@csrf
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label>Berlaku ? <em class="text-danger">*</em></label>
            <div class="input-group box" style="border: 3px solid #d2d6de !important; margin-bottom:0px;">
                <div class="col-xs-6">
                    <div class="row input-group input-group-sm">
                        <div class="input-group-addon add-on-daterpicker bg-green">
                            <i class="fa fa-calendar-check-o"></i>
                        </div>
                        <input type="text" name="berlaku_dari" @if(!empty($dataE))
                            value="{{ date('d-m-Y', strtotime($dataE['berlaku_dari'])) }}" @endif class="form-control"
                            placeholder="Dari..." readonly="" form="formDiskon">
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="row input-group input-group-sm date group-date-range">
                        <div class="input-group-addon bg-gray">
                            -
                        </div>
                        <input type="text" name="berlaku_sampai" @if(!empty($dataE))
                            value="{{ date('d-m-Y', strtotime($dataE['berlaku_sampai'])) }}" @endif
                            class="form-control daterpicker" placeholder="Sampai..." readonly="" form="formDiskon">
                        <div class="input-group-addon remove-on-daterpicker bg-gray">
                            <i class="fa fa-calendar-times-o"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Event Diskon: <em class="text-danger">*</em></label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-gift"></i>
                </div>
                <input type="text" name="nama" @if(!empty($dataE)) value="{{ $dataE['nama'] }}" @endif
                    class="form-control" placeholder="Event diskon..." form="formDiskon">
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Nominal: <em class="text-danger">*</em></label>
            <div class="input-group input-group-sm date nominal-to-percent">
                <div class="input-group-addon">
                    <i class="fa fa-question param-method"></i>
                </div>
                <input name="nominal" @if(!empty($dataE)) value="{{ RupiahFormat($dataE['nominal']) }}" @endif
                    class="form-control currency-percent" placeholder="Nominal..." form="formDiskon">
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group input-group-sm param">
            <label>Parameter: <em class="text-danger">*</em></label>
            <select name="param" class="form-control select2" style="width: 100%;" form="formDiskon">
                <option value=""></option>
                @foreach($dataParam as $num=> $r)
                <option value="{{ $r }}" @if(!empty($dataE) && $dataE['param']==$r) selected='selected' @endif>
                    {{ $r == 'Rp' ? 'Nominal (Rp)' : 'Persen (%)' }}
                </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="form-group input-group-sm">
    <label>Produk Tersedia:</label>
    <select name="product[]" class="form-control select2-multiple product" multiple="multiple" style="width: 100%;"
        form="formDiskon" @if(!empty($dataE)) data-selected="[{{ $dataE['product'] }}]" @endif></select>
</div>

<div class="form-group input-group-sm">
    <label>Layanan Tersedia:</label>
    <select name="services[]" class="form-control select2-multiple-services services" multiple="multiple"
        style="width: 100%;" form="formDiskon" @if(!empty($dataE)) data-selected="[{{ $dataE['services'] }}]"
        @endif></select>
</div>
