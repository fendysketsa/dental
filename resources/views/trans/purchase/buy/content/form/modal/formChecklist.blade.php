<div class="load-formCK-modal">
    <form action="{{ $actionCK }}" id="formTransCKPembelian"></form>
    @csrf
    <div class="modal-content">
        <div class="modal-header bg-warning ">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-center"></h4>
        </div>
        <div class="modal-body">
            @if(!empty($dataE))
            <input type="hidden" name="id" value="{{ $dataE->id }}" form="formTransCKPembelian">
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="box box-info">
                        <div class="box-header with-border bg-gray">
                            <i class="fa fa-tag"></i>
                            <h4 class="box-title">Data Transaksi</h4>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>No. Transaksi Pembelian:</label>
                                        <div class="input-group">
                                            <em>{{ (!empty($dataE) ? $dataE->no_pembelian : null)  }}</em>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tanggal:</label>
                                        <div class="input-group">
                                            <em>{{ (!empty($dataE) ? date('d M Y', strtotime($dataE->tanggal)) : null)  }}</em>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Keterangan:</label>
                                <div class="input-group">
                                    <em>{{ (!empty($dataE) ? (!empty($dataE->keterangan) ? $dataE->keterangan : '-') : null) }}</em>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box box-danger">
                        <div class="box-header with-border bg-success">
                            <i class="fa fa-user"></i>
                            <h4 class="box-title">Data Supplier</h4>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="form-group change-to-field">
                                        <label>Supplier:</label>
                                        <div class="input-group">
                                            <em>{{ (!empty($dataE) ? (!empty($dataE->nama) ? $dataE->nama : '-') : null) }}</em>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>No Telepon:</label>
                                        <div class="input-group">
                                            <em>{{ (!empty($dataE) ? (!empty($dataE->telepon) ? $dataE->telepon : '-') : null) }}</em>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Alamat:</label>
                                <div class="input-group">
                                    <em>{{ (!empty($dataE) ? (!empty($dataE->alamat) ? $dataE->alamat : '-') : null) }}</em>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table table-striped">
                        <thead class="bg-navy disabled color-palette">
                            <tr>
                                <th style="width:5%;"><em class="fa fa-slack"></em></th>
                                <th style="width:5%;">No</th>
                                <th style="width:30%;">Produk</th>
                                <th style="width:15%;" class="text-center">Harga Beli</th>
                                <th style="width:10%;" class="text-center">Jumlah</th>
                                <th style="width:20%;" class="text-center">Sub Total</th>
                                <th style="width:15;" class="text-center"><em class="fa fa-gears"></em></th>
                            </tr>
                        </thead>
                        <tbody class="data-check-pembelian"></tbody>
                    </table>
                    <div class="row look-check-ttal hide" style="margin-top:20px;">
                        <div class="col-md-12 col-sm-12">
                            <div class="row">
                                <div class="col-md-7 col-sm-7 col-xs-7">
                                    <span class="pull-right">
                                        <h4><strong>Total Pembelian</strong></h4>
                                    </span>
                                </div>
                                <div class="col-md-1 col-sm-1 col-xs-1">
                                    <div class="input-group">
                                        <em class="input-group-addon bg-aqua text-white text-bold">Rp</em>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <span
                                        style="font-size: 25px;font-family: sans-serif;">{{ (!empty($dataE) ? (!empty($dataE->total_pembelian) ? $dataE->total_pembelian : 0) : null) }}</span>
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
                    <a role="button" class="btn btn-warning col-md-12 col-xs-12 col-sm-12" data-dismiss="modal"><em
                            class="fa fa-undo"></em>
                        Cancel</a>
                </div>
                <div class="col-md-6 col-xs-6 col-sm-6">
                    <button type="submit" class="btn btn-info col-md-12 col-xs-12 col-sm-12"
                        form="formTransCKPembelian"><em class="fa fa-envelope"></em> Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
