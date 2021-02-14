<form action="{{ $action }}" id="formRegistrasi"></form>
@csrf

<div class="f-reservasi"></div>
<div class="f-codereferal"></div>
<div class="form-group change-to-field noMember" data-auto-nom="{{ $autoNom }}">
    <label>No Member: <em class="text-danger">*</em></label>
    <div class="input-group input-group-sm">
        <div class="input-group-addon">
            <i class="fa fa-sort-numeric-asc"></i>
        </div>
        <em class="f-input-an">
            <select name="sno_member" class="select2 input-group-sm form-control f-member" style="width: 100%;"
                form="formRegistrasi"></select>
        </em>
        <div class="input-group-addon bg-green add">
            <i class="fa fa-plus"></i>
        </div>
    </div>
    {{-- <div class="pull-right auto-nom hide" style="font-style:italic; font-size:11px;"><small>generate no member <em
                class="fa fa-bolt btn btn-dark btn-xs mg-l-xs"></em></small></div> --}}
</div>
<div class="f-new-member"></div>
