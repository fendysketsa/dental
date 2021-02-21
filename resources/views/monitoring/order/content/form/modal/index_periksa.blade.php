<form action="{{ $action }}" id="formRegistrasi"></form>
@if(!empty($data))
<input type="hidden" name="id" value="{{ $data[0]->id }}" data-layanan="[{{ $services[0]->layanan }}]"
    data-terapis="[{{ $services[0]->terapis }}]" data-lokasi="{{ $data[0]->lokasi_id }}"
    data-reservasi="{{ $data[0]->waktu_reservasi }}" data-dp="{{ $data[0]->dp }}"
    data-jum_org="{{ $data[0]->jumlah_orang }}" data-ruang="{{ $data[0]->room_id }}"
    data-total-biaya="{{ RupiahFormat($data[0]->total_biaya) }}" data-member-id="{{ $data[0]->member_id }}"
form="formRegistrasi">
@endif

<div class="load-form-modal-periksa">
    <div class="modal-content">
        <div class="modal-header bg-success ">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-center"></h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-9" style="border-right:1px solid #E0E0E0">
                    <div class="box box-info display-future">
                        <div class="box-header with-border bg-warning">
                            <i class="fa fa-pencil-square-o"></i>
                            <h3 class="box-title">Form Rekam Medik</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="load-form-left-periksa"><em class='fa fa-spin fa-spinner'></em> Loading...
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box box-danger display-future">
                        <div class="box-header with-border bg-info">
                            <i class="fa fa-info"></i>
                            <h3 class="box-title">Informasi Member</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="load-informasi-right-periksa"><em class='fa fa-spin fa-spinner'></em> Loading...
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
