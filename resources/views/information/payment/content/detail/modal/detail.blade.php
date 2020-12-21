<div class="modal-content">
    <div class="modal-header bg-success ">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center"></h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-3">
                <div class="box box-info display-future">
                    <div class="box-header with-border bg-success">
                        <i class="fa fa-info"></i>
                        <h3 class="box-title">Informasi Transaksi</h3>
                        @if(!empty($data))
                        <em id="detailPembayaran" data-cara-bayar="{{$data[0]->cara_bayar_kasir }}"
                            data-diskon="{{$data[0]->diskon }}" data-nominal-bayar="{{$data[0]->nominal_bayar }}"
                            data-kembalian-bayar="{{$data[0]->kembalian }}"
                            data-jam-transaksi="{{ date('H:i', strtotime($data[0]->created_at)) }}"
                            data-tanggal-transaksi="{{ date('Y-m-d', strtotime($data[0]->created_at)) }}"
                            data-transaksi-no="{{$data[0]->no_transaksi }}" value="{{ $data[0]->id }}"
                            data-produk="[{{ $produk[0]->produk }}]" data-produk-jumlah="[{{ $produk[0]->jumlah }}]"
                            data-produk-harga="[{{ $produk[0]->harga }}]" data-layanan="[{{ $services[0]->layanan }}]"
                            data-terapis="[{{ $services[0]->terapis }}]" data-lokasi="{{ $data[0]->lokasi_id }}"
                            data-reservasi="{{ $data[0]->waktu_reservasi }}"
                            data-jam-reservasi="{{ date('H:i', strtotime($data[0]->waktu_reservasi)) }}"
                            data-tanggal-reservasi="{{ date('Y-m-d', strtotime($data[0]->waktu_reservasi)) }}"
                            data-dp="{{ $data[0]->dp }}" data-jum_org="{{ $data[0]->jumlah_orang }}"
                            data-total-biaya="{{ $data[0]->total_biaya }}" data-paket="[{{ $paket[0]->paket }}]"
                            @foreach($posisi as $numpos=> $pos)
                            data-paket-terapis-{{ $pos->posisi }}="[{{ $pktservices[$numpos]->terapis }}]"
                            @endforeach data-member="{{ $data[0]->member_id }}"></em>
                        @endif
                    </div>
                    <div class="box-body">
                        <div class="load-detail-left-info-transaksi"><em class='fa fa-spin fa-spinner'></em>
                            Loading...
                        </div>
                    </div>
                    <div class="box-header with-border bg-warning">
                        <i class="fa fa-info"></i>
                        <h3 class="box-title">Informasi Member</h3>
                    </div>
                    <div class="box-body">
                        <div class="load-detail-left-info-member"><em class='fa fa-spin fa-spinner'></em>
                            Loading...
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-9" style="border-left:3px solid #f4f4f4;">
                <div class="box box-danger display-future">
                    <div class="box-header with-border bg-info">
                        <i class="fa fa-list"></i>
                        <h3 class="box-title">Layanan, Paket dan Produk Tersedia</h3>
                    </div>
                    <div class="box-body">
                        <div class="load-detail-right-order">
                            <div class="text-center">
                                <em class='fa fa-spin fa-spinner'></em>
                                Loading...
                            </div>
                        </div>
                        <div class="load-detail-right-paket">
                            <div class="text-center">
                                <em class='fa fa-spin fa-spinner'></em>
                                Loading...
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-striped hover">
                                    <thead class="bg-navy disabled color-palette">
                                        <tr>
                                            <th style="width:5%;">No</th>
                                            <th style="width:40%;">Produk</th>
                                            <th style="width:15%;" class="text-center">Harga</th>
                                            <th style="width:10%;" class="text-center">Jumlah</th>
                                            {{-- <th style="width:10%;" class="text-center">Disc</th> --}}
                                            <th style="width:20%;" class="text-center">Sub Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="data-product">
                                        <tr class="loading-data-produk">
                                            <td colspan="6" class="text-center"><em class='fa fa-spin fa-spinner'></em>
                                                Loading...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row" style="margin-top:20px;">
                            <div class="col-md-3 col-sm-3 col-xs-3"></div>
                            <div class="col-md-9 col-sm-9 col-xs-9">
                                <div class="row">
                                    <div class="col-md-5 col-sm-5 col-xs-5">
                                        <span class="pull-right">
                                            <h4><strong>Total Produk</strong></h4>
                                        </span>
                                    </div>
                                    <div class="col-md-1 col-sm-1 col-xs-1">
                                        <div class="input-group">
                                            <span class="input-group-addon bg-aqua text-white">Rp</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <em class="total-belanja-produk" style="font-size:20px;">loading...</em>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5 col-sm-5 col-xs-5">
                                        <span class="pull-right">
                                            <h4><strong>Diskon</strong></h4>
                                        </span>
                                    </div>
                                    <div class="col-md-1 col-sm-1 col-xs-1">
                                        <div class="input-group">
                                            <span class="input-group-addon text-white">
                                                <em class="fa fa-cut"></em>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <em class="t-diskon" style="font-size:20px;">loading...</em>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5 col-sm-5 col-xs-5">
                                        <span class="pull-right">
                                            <h4><strong>Total Biaya</strong></h4>
                                        </span>
                                    </div>
                                    <div class="col-md-1 col-sm-1 col-xs-1">
                                        <div class="input-group">
                                            <span class="input-group-addon bg-aqua text-white">Rp</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <em class="total-belanja" style="font-size:20px;">loading...</em>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5 col-sm-5 col-xs-5">
                                        <span class="pull-right">
                                            <h4><strong>Cara Bayar</strong></h4>
                                        </span>
                                    </div>
                                    <div class="col-md-1 col-sm-1 col-xs-1">:</div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <em class="cara-bayar" style="font-size:20px;">loading...</em>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5 col-sm-5 col-xs-5">
                                        <span class="pull-right">
                                            <h4><strong>Nominal Bayar</strong></h4>
                                        </span>
                                    </div>
                                    <div class="col-md-1 col-sm-1 col-xs-1">
                                        <div class="input-group">
                                            <span class="input-group-addon bg-aqua text-white">Rp</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <em class="total-nominal-bayar" style="font-size:20px;">loading...</em>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5 col-sm-5 col-xs-5">
                                        <span class="pull-right">
                                            <h4><strong>Kembalian</strong></h4>
                                        </span>
                                    </div>
                                    <div class="col-md-1 col-sm-1 col-xs-1">
                                        <div class="input-group">
                                            <span class="input-group-addon bg-aqua text-white">Rp</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <em class="total-kembalian" style="font-size:20px;">loading...</em>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
