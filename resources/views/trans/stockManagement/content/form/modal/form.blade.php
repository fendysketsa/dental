<form action="{{ $action }}" id="formStockManagement"></form>
@csrf

<div class="load-form-modal">
    <div class="modal-content">
        <div class="modal-header bg-success ">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-center"></h4>
        </div>
        <div class="modal-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <td style="width: 60px">Produk</td>
                        <td style="width: 10px">:</td>
                        <th>@if(!empty($dataE)) {{ $dataE['nama'] }} @endif</th>
                    </tr>
                    <tr>
                        <td>Kategori</td>
                        <td>:</td>
                        <th>@if(!empty($dataE)) {{ $dataE['kategori'] }} @endif</th>
                    </tr>
                    <tr>
                        <td>Stok</td>
                        <td>:</td>
                        <th class="last-stok">
                            @if(!empty($dataE)) {{ $dataE['stok'] }} @endif
                        </th>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <div class="form-group">
                                <div class="col-lg-12 input-group input-group-sm date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-database"></i>
                                    </div>
                                    <input type="text" name="stok" class="form-control" placeholder="Masukkan Stok..."
                                        form="formStockManagement">
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="keterangan-opname"></tr>
                </thead>
            </table>
        </div>
        <div class="box-footer bg-gray-light">
            <div class="row">
                <div class="col-md-6 col-xs-6 col-sm-6">
                    <a role="button" data-dismiss="modal" class="btn btn-warning col-md-12 col-xs-12 col-sm-12"><em
                            class="fa fa-undo"></em> Cancel</a>
                </div>
                <div class="col-md-6 col-xs-6 col-sm-6">
                    <button type="submit" class="btn btn-info col-md-12 col-xs-12 col-sm-12"
                        form="formStockManagement"><em class="fa fa-envelope"></em> Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
