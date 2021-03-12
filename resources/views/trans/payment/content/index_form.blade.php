<form action="{{ $action }}" id="formKasir"></form>
@if(!empty($data))
<input type="hidden" name="id" data-jam-transaksi="{{ date('H:i', strtotime($data[0]->created_at)) }}"
    data-tanggal-transaksi="{{ date('Y-m-d', strtotime($data[0]->created_at)) }}"
    data-transaksi-no="{{$data[0]->no_transaksi }}" value="{{ $data[0]->id }}" data-produk="[{{ $produk[0]->produk }}]"
    data-produk-jumlah="[{{ $produk[0]->jumlah }}]" data-produk-harga="[{{ $produk[0]->harga }}]"
    data-ruangan-harga="{{ $data[0]->harga_ruangan }}" data-layanan="[{{ $services[0]->layanan }}]"
    data-layanan-tambahan="{{ $services_add }}" data-terapis="[{{ $services[0]->terapis }}]"
    data-lokasi="{{ $data[0]->lokasi_id }}" data-reservasi="{{ $data[0]->waktu_reservasi }}"
    data-jam-reservasi="{{ date('H:i', strtotime($data[0]->waktu_reservasi)) }}"
    data-tanggal-reservasi="{{ date('Y-m-d', strtotime($data[0]->waktu_reservasi)) }}" data-dp="{{ $data[0]->dp }}"
    data-jum_org="{{ $data[0]->jumlah_orang }}" data-total-biaya="{{ $data[0]->total_biaya }}"
    data-paket="[{{ $paket[0]->paket }}]" @foreach($posisi as $numpos=> $pos)
data-paket-terapis-{{ $pos->posisi }}="[{{ $pktservices[$numpos]->terapis }}]" @endforeach
data-member="{{ $data[0]->member_id }}" form="formKasir">
@endif

<div class="row">
    <div class="col-md-3">
        <div class="box box-info display-future">
            <div class="box-header with-border bg-success">
                <i class="fa fa-info"></i>
                <h3 class="box-title">Informasi Transaksi</h3>
            </div>
            <div class="box-body">
                <div class="load-form-left-info-transaksi"><em class='fa fa-spin fa-spinner'></em> Loading...
                </div>
            </div>

            <div class="box-header with-border bg-warning">
                <i class="fa fa-info"></i>
                <h3 class="box-title">Informasi Member</h3>
            </div>
            <div class="box-body">
                <div class="load-form-left-info-member"><em class='fa fa-spin fa-spinner'></em> Loading...
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-9" style="border-left:3px solid #f4f4f4;">
        <div class="box box-danger display-future">
            <div class="box-header with-border bg-info">
                <i class="fa fa-list"></i>
                <h3 class="box-title">Treatment dan Layanan Tambahan</h3>
            </div>
            <div class="box-body">
                <div class="load-form-right-order"><em class='fa fa-spin fa-spinner'></em> Loading order...
                </div>
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
                        <button name="saveit" type="submit" class="btn btn-info col-md-12 col-xs-12 col-sm-12"
                            form="formKasir"><em class="fa fa-envelope"></em> Save</button>
                    </div>
                    <div class="col-md-4 col-xs-4 col-sm-4">
                        <button name="saveprint" type="submit" class="btn btn-success col-md-12 col-xs-12 col-sm-12"
                            form="formKasir"><em class="fa fa-print"></em> Save Dan Cetak</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
