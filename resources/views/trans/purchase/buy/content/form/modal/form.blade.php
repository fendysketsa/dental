<form action="{{ $action }}" id="formTransPembelian"></form>
@csrf

<div class="load-form-modal">
    @if(!empty($dataE))
    <input type="hidden" name="id" value="{{ $dataE->id }}" form="formTransPembelian">
    @endif

    <div class="modal-content">
        <div class="modal-header bg-warning ">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-center"></h4>
        </div>
        <div class="modal-body">
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
                                        <label>No. Transaksi Pembelian: <em class="text-danger">*</em></label>
                                        <div class="input-group input-group-sm date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-sort-numeric-asc"></i>
                                            </div>
                                            <input name="no_pembelian" type="text" class="form-control" min="1"
                                                value="{{ (!empty($dataE) ? $dataE->no_pembelian : $autoNoPembelian) }}"
                                                readonly form="formTransPembelian">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tanggal: <em class="text-danger">*</em></label>
                                        <div class="input-group input-group-sm date on-date">
                                            <div class="input-group-addon add-on-daterpicker">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input name="tanggal" type="text" @if(!empty($dataE))
                                                value="{{ date('d-m-Y', strtotime($dataE->updated_at)) }}" @endif
                                                class="form-control" placeholder="Tanggal..." readonly
                                                form="formTransPembelian">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Keterangan:</label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-addon">
                                        <i class="fa fa-pencil"></i>
                                    </div>
                                    <textarea name="keterangan" cols="15" rows="4" class="form-control min-height-ta"
                                        placeholder="Keterangan..."
                                        form="formTransPembelian">{{ (!empty($dataE) ? $dataE->keterangan : null) }}</textarea>
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
                                        <label>Supplier: <em class="text-danger">*</em></label>
                                        <div class="input-group input-group-sm">
                                            <em class="f-input-an">
                                                <select name="ssupplier" class="select2 form-control"
                                                    style="width: 100%;" @if(!empty($dataE))
                                                    data-selected="{{ $dataE->supplier_id }}" @endif
                                                    form="formTransPembelian"></select>
                                            </em>
                                            <div class="input-group-addon bg-green add">
                                                <i class="fa fa-user-plus"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>No Telepon: <em class="text-danger to-fill"></em></label>
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-addon">
                                                <i class="fa fa-phone"></i>
                                            </div>
                                            <input type="text" disabled class="form-control telepon"
                                                placeholder="No Telepon..." form="formTransPembelian">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Alamat: <em class="text-danger to-fill"></em></label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-addon">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <textarea disabled cols="5" rows="4" class="form-control min-height-ta alamat"
                                        placeholder="Alamat..." form="formTransPembelian"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table table-striped">
                        <thead class="bg-navy disabled color-palette">
                            <tr>
                                <th style="width:5%;">No</th>
                                <th style="width:20%;">Produk <em class="text-danger">*</em></th>
                                <th style="width:15%;" class="text-center">Harga Beli</th>
                                <th style="width:10%;" class="text-center">Jumlah</th>
                                <th style="width:10%;" class="text-center">Sisa Stok</th>
                                <th style="width:15%;" class="text-center">Harga Jual</th>
                                <th style="width:15%;" class="text-center">Sub Total</th>
                                <th style="width:10;" class="text-center"><em class="fa fa-gears"></em></th>
                            </tr>
                        </thead>
                        <tbody class="data-pembelian"></tbody>
                    </table>
                    <div class="row" style="margin-top:20px;">
                        <div class="col-md-3 col-sm-3 col-xs-3 add-rows">
                            <button type="button" class="btn btn-success btn-sm col-xs-12 disabled" id="add-row"><em
                                    class="fa fa-plus"></em> Baris</button>
                        </div>
                        <div class="col-md-9 col-sm-9 col-xs-9">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <span class="pull-right">
                                        <h4><strong>Total Pembelian</strong></h4>
                                    </span>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <div class="input-group">
                                        <span class="input-group-addon bg-aqua text-white">Rp</span>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <input type="text" readonly name="total_pembelian"
                                        class="form-control total-belanja" value="0" form="formTransPembelian">
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
                        form="formTransPembelian"><em class="fa fa-envelope"></em> Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
