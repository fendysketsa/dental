<form action="{{ $action }}" id="formPelanggan"></form>
@csrf

<div class="load-form-modal">
    <div class="modal-content display-future">
        <div class="modal-header bg-warning">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-center"></h4>
        </div>
        <div class="modal-body">
            @if(!empty($dataE))
            <input type="hidden" name="id" value="{{ $dataE->id }}" data-email="{{ $dataE->email }}"
                form="formPelanggan">
            @endif

            <div class="row">
                <div class="col-md-7">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <div style="width:100%; height: 145px; text-align: center; position: relative"
                                            id="image">
                                            <img width="100%" height="100%" style="border-radius:50%;"
                                                id="preview_image"
                                                src="{{ (!empty($dataE->foto) ?
                                                (file_exists('storage/master-data/member/uploads/' . $dataE->foto) ? 'storage/master-data/member/uploads/' . $dataE->foto : asset('images/brokenimage.jpg')) : asset('images/noimage.jpg') ) }}" />
                                        </div>
                                        <div class="row mt-5">
                                            <div class="col-md-6 col-xs-6 col-sm-6">
                                                <a class="btn btn-xs btn-default col-md-12 col-xs-12 col-sm-12"
                                                    role="button" href="javascript:changeProfile()">
                                                    <i class="fa fa-upload text-info"></i> </a>
                                            </div>
                                            <div class="col-md-6 col-xs-6 col-sm-6">
                                                <a class="btn btn-xs btn-default col-md-12 col-xs-12 col-sm-12"
                                                    role="button" href="javascript:removeFile()">
                                                    <i class="fa fa-trash text-danger"></i> </a>
                                            </div>
                                        </div>

                                        <input type="file" id="file" name="foto" class="hide" form="formPelanggan" />
                                        <input type="hidden" id="file_name" form="formPelanggan" @if(!empty($dataE))
                                            name="old_img" value="{{ $dataE->foto }}" @endif />
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <label>No Member: <em class="text-danger">*</em></label>
                                                <div class="input-group input-group-sm date">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-sort-numeric-asc"></i>
                                                    </div>
                                                    <input type="text" name="no_member"
                                                        value="{{ (!empty($dataE) ? $dataE->no_member : $autoNom) }}"
                                                        class="form-control" placeholder="No Member..." readonly
                                                        form="formPelanggan">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Saldo:</label>
                                                <div class="input-group input-group-sm date">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-trophy"></i>
                                                    </div>
                                                    <input type="text" name="saldo" @if(!empty($dataE))
                                                        value="{{ $dataE->saldo }}" @endif class="form-control"
                                                        placeholder="Saldo..." form="formPelanggan">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Nama: <em class="text-danger">*</em></label>
                                        <div class="input-group input-group-sm date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-tag"></i>
                                            </div>
                                            <input type="text" name="nama" @if(!empty($dataE))
                                                value="{{ $dataE->nama }}" @endif class="form-control"
                                                placeholder="Nama..." form="formPelanggan">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Panggilan:</label>
                                        <div class="input-group input-group-sm date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-tags"></i>
                                            </div>
                                            <input type="text" name="nama_panggilan" @if(!empty($dataE))
                                                value="{{ $dataE->nama_panggilan }}" @endif class="form-control"
                                                placeholder="Nama Panggilan..." form="formPelanggan">
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-4 col-xs-6 col-sm-6">
                                    <div class="form-group input-group-sm">
                                        <label>Jenis Kelamin: <em class="text-danger">*</em></label>
                                        <label class="container-radio"> Laki-laki
                                            <input type="radio" name="jenis_kelamin" @if(!empty($dataE) &&
                                                $dataE->jenis_kelamin == 'Laki-laki')
                                            checked="checked" @endif value="Laki-laki" form="formPelanggan">
                                            <span class="checkmark-radio"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-4 col-xs-6 col-sm-6">
                                    <div class="form-group input-group-sm">
                                        <label>&nbsp;</label>
                                        <label class="container-radio"> Perempuan
                                            <input type="radio" name="jenis_kelamin" @if(!empty($dataE) &&
                                                $dataE->jenis_kelamin == 'Perempuan')
                                            checked="checked" @else
                                            checked="checked" @endif
                                            value="Perempuan" form="formPelanggan">
                                            <span class="checkmark-radio"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Sosial Media:</label>
                                        <div class="input-group input-group-sm date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-bullhorn"></i>
                                            </div>
                                            <input type="text" name="media_sosial" @if(!empty($dataE))
                                                value="{{ $dataE->media_sosial }}" @endif class="form-control"
                                                placeholder="Sosial Media..." form="formPelanggan">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Telepon: <em class="text-danger">*</em></label>
                                        <div class="input-group input-group-sm date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-phone"></i>
                                            </div>
                                            <input type="text" name="telepon" @if(!empty($dataE))
                                                value="{{ $dataE->telepon }}" @endif class="form-control"
                                                placeholder="Telepon..." form="formPelanggan">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label>Tempat Lahir:</label>
                                        <div class="input-group input-group-sm date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-street-view"></i>
                                            </div>
                                            <input type="text" name="tempat_lahir" @if(!empty($dataE))
                                                value="{{ $dataE->tempat_lahir }}" @endif class="form-control"
                                                placeholder="Tempat Lahir..." form="formPelanggan">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Tanggal Lahir:</label>
                                        <div class="input-group input-group-sm date on-date">
                                            <div class="input-group-addon add-on-daterpicker">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" name="tanggal_lahir" @if(!empty($dataE->tgl_lahir))
                                            value="{{ date('d-m-Y', strtotime($dataE->tgl_lahir)) }}" @endif
                                            class="form-control" placeholder="Tanggal Lahir..." form="formPelanggan"
                                            readonly="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#alamat" data-toggle="tab" aria-expanded="false">Alamat <em
                                        class="text-danger">*</em></a></li>
                            <li>
                                <a href="#domisili" data-toggle="tab" aria-expanded="false">Domisili</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="alamat">
                                <div class="form-group input-group-sm">
                                    <textarea name="alamat" cols="15" rows="4" class="form-control add-style"
                                        style="height:130px;" placeholder="Alamat..."
                                        form="formPelanggan">{{ (!empty($dataE) ? $dataE->alamat : null) }}</textarea>
                                </div>

                            </div>
                            <div class="tab-pane" id="domisili">
                                <div class="form-group input-group-sm">
                                    <textarea name="domisili" cols="15" rows="4" class="form-control add-style"
                                        style="height:130px;" placeholder="Domisili..."
                                        form="formPelanggan">{{ (!empty($dataE) ? $dataE->domisili : null) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-group" id="accordion">
                        <div class="panel box box-primary">
                            <div class="box-header with-border bg-info">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
                                    aria-expanded="false" class="collapsed for-more">
                                    <em class="fa fa-key"></em> Akses system <em
                                        class="pull-right show-more fa fa-arrow-down"></em>
                                </a>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse" aria-expanded="false">
                                <div class="box-body">
                                    <div class="load-more"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="box-footer bg-gray-light">
            <div class="row">
                <div class="col-md-6 col-xs-6 col-sm-6">
                    <a role="button" data-dismiss="modal" class="btn btn-warning col-md-12 col-xs-12 col-sm-12"><em
                            class="fa fa-undo"></em> Cancel</a>
                </div>
                <div class="col-md-6 col-xs-6 col-sm-6">
                    <button type="submit" class="btn btn-info col-md-12 col-xs-12 col-sm-12" form="formPelanggan"><em
                            class="fa fa-envelope"></em> Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
