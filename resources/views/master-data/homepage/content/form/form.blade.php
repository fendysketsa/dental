<form action="{{ $action }}" id="formHomePage"></form>
@if(!empty($dataE))
<input type="hidden" name="id" value="{{ $dataE->id }}" form="formHomePage">
@endif
@csrf

<ul class="nav nav-tabs">
    <li class="active">
        <a href="#fgambar" data-toggle="tab" aria-expanded="false">Gambar & Video
            <sup>(Embed)</sup></a></li>
    <li class="">
        <a href="#fdeskripsi" data-toggle="tab" aria-expanded="false">Deskripsi <em class="text-danger">*</em></a></li>
</ul>

<div class="tab-content">
    <div class="tab-pane active" id="fgambar">

        <div class="form-group">
            <div style="width:100%; height: 185px; text-align: center; position: relative" id="image">
                <img width="100%" height="100%" id="preview_image"
                    src="{{ (!empty($dataE->gambar) ? 'storage/master-data/home-page/uploads/' . $dataE->gambar : asset('images/noimage.jpg') ) }}" />
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

            <input type="file" id="file" name="gambar" class="hide" form="formHomePage" />
            <input type="hidden" id="file_name" form="formHomePage" @if(!empty($dataE)) name="old_img"
                value="{{ $dataE->gambar }}" @endif />
        </div>

    </div>
    <div class="tab-pane" id="fdeskripsi">
        <div class="form-group">
            <label>&nbsp;</label>
            <textarea name="deskripsi" class="form-control add-style" id="deskripsi" placeholder="Deskripsi..."
                form="formHomePage">{{ (!empty($dataE) ? $dataE->deskripsi : null) }}</textarea>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-8 col-md-8">
        <div class="form-group">
            <label>Judul: <em class="text-danger">*</em></label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-tag"></i>
                </div>
                <input type="text" name="judul" @if(!empty($dataE)) value="{{ $dataE->judul }}" @endif
                    class="form-control" placeholder="Judul..." form="formHomePage">
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4">
        <div class="form-group">
            <label>Video: <sup>(Embed)</sup></label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-tag"></i>
                </div>
                <input type="text" name="video" @if(!empty($dataE)) value="{{ $dataE->video }}" @endif
                    class="form-control" placeholder="Video Embed..." form="formHomePage">
            </div>
        </div>
    </div>
</div>
