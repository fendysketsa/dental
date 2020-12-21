<form action="{{ $action }}" id="formTransPengeluaran"></form>
@csrf

<div class="load-form-modal">
    <div class="modal-content">
        <div class="modal-header bg-warning ">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-center"></h4>
        </div>
        <div class="modal-body">
            @if(!empty($dataE))
            <input type="hidden" name="id" value="{{ $dataE->id }}" form="formTransPengeluaran">
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border bg-gray">
                            <i class="fa fa-tag"></i>
                            <h4 class="box-title">Data Transaksi</h4>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6 col-xs-6">
                                    <div class="form-group">
                                        <label>No. Transaksi Pengeluaran: <em class="text-danger">*</em></label>
                                        <div class="input-group input-group-sm date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-sort-numeric-asc"></i>
                                            </div>
                                            <input name="no_pengeluaran" type="text" class="form-control" min="1"
                                                value="{{ (!empty($dataE) ? $dataE->no_pengeluaran : $autoNoPengeluaran) }}"
                                                readonly form="formTransPengeluaran">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <div class="form-group">
                                        <label>Tanggal: <em class="text-danger">*</em></label>
                                        <div class="input-group input-group-sm date on-date">
                                            <div class="input-group-addon add-on-daterpicker">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input name="tanggal" type="text" @if(!empty($dataE))
                                                value="{{ date('d-m-Y', strtotime($dataE->updated_at)) }}" @endif
                                                class="form-control" placeholder="Tanggal..." readonly
                                                form="formTransPengeluaran">
                                        </div>
                                    </div>
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
                                <th style="width:35%;">Keterangan <em class="text-danger">*</em></th>
                                <th style="width:20%;" class="text-center">Harga</th>
                                <th style="width:10%;" class="text-center">Jumlah</th>
                                <th style="width:20%;" class="text-center">Sub Total</th>
                                <th style="width:10;" class="text-center"><em class="fa fa-gears"></em></th>
                            </tr>
                        </thead>
                        <tbody class="data-pengeluaran"></tbody>
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
                                        <h4><strong>Total Pengeluaran</strong></h4>
                                    </span>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <div class="input-group">
                                        <span class="input-group-addon bg-aqua text-white">Rp</span>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <input type="text" readonly name="total_pengeluaran"
                                        class="form-control total-belanja" value="0" form="formTransPengeluaran">
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
                        form="formTransPengeluaran"><em class="fa fa-envelope"></em> Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
