<form action="{{ $action }}" id="formSetNota"></form>
@csrf

<div class="row">
    <div class="col-md-6 col-xs-3">
        <div class="form-group">
            <div style="width:100%; height: 185px; text-align: center; position: relative" id="image">
                <img style="border-radius:50%;" width="100%" height="100%" id="preview_image"
                    src="{{ (!empty($dataE->logo) ? asset('s-home/setting/nota/uploads/' . $dataE->logo) : asset('images/noimage.jpg') ) }}" />
            </div>
            <div class="row mt-5">
                <div class="col-md-6 col-xs-6 col-sm-6">
                    <a class="btn btn-xs btn-default col-md-12 col-xs-12 col-sm-12" role="button"
                        href="javascript:changeProfile()">
                        <i class="fa fa-upload text-info"></i> </a>
                </div>
                <div class="col-md-6 col-xs-6 col-sm-6">
                    <a class="btn btn-xs btn-default col-md-12 col-xs-12 col-sm-12" role="button"
                        href="javascript:removeFile()">
                        <i class="fa fa-trash text-danger"></i> </a>
                </div>
            </div>

            <input type="file" id="file" name="logo" class="hide" form="formSetNota" />
            <input type="hidden" id="file_name" form="formSetNota" @if(!empty($dataE)) name="old_img"
                value="{{ $dataE->logo }}" @endif />
        </div>
    </div>
    <div class="col-md-6 col-xs-9">
        <div class="form-group">
            <label>Title: <em class="text-danger">*</em></label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-tag"></i>
                </div>
                <input type="text" name="title" @if(!empty($dataE)) value="{{ $dataE->title }}" @endif
                    class="form-control" placeholder="Nama..." form="formSetNota">
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xs-12">
        <div class="form-group">
            <label>Contact Info: <em class="text-danger">*</em></label>
            <textarea name="contact_info" id="contact_info" class="form-control add-style" style="height:60px;"
                placeholder="Contact info..."
                form="formSetNota">{{ (!empty($dataE) ? $dataE->contact_info : null) }}</textarea>
        </div>
        <div class="form-group">
            <label>Salutation: <em class="text-danger">*</em></label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-edit"></i>
                </div>
                <textarea name="salutation" class="form-control add-style" style="height:60px;"
                    placeholder="Salutation..."
                    form="formSetNota">{{ (!empty($dataE) ? $dataE->salutation : null) }}</textarea>
            </div>
        </div>
    </div>

</div>
