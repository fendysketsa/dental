<form action="{{ $action }}" id="formRegistrasi"></form>
@if(!empty($data))
<input type="hidden" name="id" value="{{ $data[0]->id }}" data-layanan="[{{ $services[0]->layanan }}]"
    data-terapis="[{{ $services[0]->terapis }}]" data-lokasi="{{ $data[0]->lokasi_id }}"
    data-reservasi="{{ $data[0]->waktu_reservasi }}" data-dp="{{ $data[0]->dp }}"
    data-jum_org="{{ $data[0]->jumlah_orang }}" data-ruang="{{ $data[0]->room_id }}"
    data-total-biaya="{{ RupiahFormat($data[0]->total_biaya) }}" data-paket="[{{ $paket[0]->paket }}]" @foreach($posisi
    as $numpos=> $pos)
data-paket-terapis-{{ $pos->posisi }}="[{{ $pktservices[$numpos]->terapis }}]" @endforeach
data-member-id="{{ $data[0]->member_id }}" form="formRegistrasi">
@endif

<div class="load-form-modal">
    <div class="modal-content">
        <div class="modal-header bg-success ">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-center"></h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-5">
                    <div class="box box-info display-future">
                        <div class="box-header with-border bg-warning">
                            <i class="fa fa-pencil-square-o"></i>
                            <h3 class="box-title">Form Registrasi</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i></button>
                            </div>
                            <div class="box-tools pull-left">
                                <div class="input-group input-group-sm to-reservasi" style="margin-left: -100px;">
                                    <button type="button" class="btn btn-default btn-xs" id="show">
                                        <em class="fa fa-question-circle text-disable"></em> reservasi
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="load-form-left"><em class='fa fa-spin fa-spinner'></em> Loading...
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-md-7">
                    <div class="box box-danger display-future">
                        <div class="box-header with-border bg-info">
                            <i class="fa fa-list"></i>
                            <h3 class="box-title">Keluhan Tersedia & Rekam Medik Umum <em class="text-danger">*</em></h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool right-to" data-widget="collapse"><i
                                        class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="load-form-right"><em class='fa fa-spin fa-spinner'></em> Loading...
                            </div>
                        </div>
                        <div class="button-action box-footer bg-gray-light hide">
                            <div class="row">
                                <div class="col-md-4 col-xs-4 col-sm-4">
                                    <a role="button" data-dismiss="modal"
                                        class="btn btn-warning col-md-12 col-xs-12 col-sm-12 cancel-form"><em
                                            class="fa fa-undo"></em>
                                        Cancel</a>
                                </div>
                                <div class="col-md-4 col-xs-4 col-sm-4">
                                    <button name="saveit" type="submit"
                                        class="btn btn-info col-md-12 col-xs-12 col-sm-12" form="formRegistrasi"><em
                                            class="fa fa-envelope"></em> Save</button>
                                </div>
                                <div class="col-md-4 col-xs-4 col-sm-4">
                                    <button name="saveprint" type="submit"
                                        class="btn btn-success col-md-12 col-xs-12 col-sm-12" form="formRegistrasi"><em
                                            class="fa fa-print"></em> Save Dan Cetak</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
