<form action="{{ $action }}" id="formKasir"></form>
@csrf

<table class="table table-striped hover">
    <thead class="bg-navy disabled color-palette">
        <tr>
            <th style="width:5%;">No</th>
            <th style="width:25%;">Produk</th>
            <th style="width:15%;" class="text-center">Harga</th>
            <th style="width:10%;" class="text-center">Jumlah</th>
            {{-- <th style="width:10%;" class="text-center">Disc</th> --}}
            <th style="width:23%;" class="text-center">Sub Total</th>
            <th style="width:12%;"><em class="fa fa-gears"></em></th>
        </tr>
    </thead>
    <tbody class="data-product">
        <tr class="loading-data-produk">
            <td colspan="6" class="text-center"><em class='fa fa-spin fa-spinner'></em> Loading...</td>
        </tr>
    </tbody>
</table>
<div class="row" style="margin-top:20px;">
    <div class="col-md-3 col-sm-3 col-xs-3 add-rows">
        <button type="button" class="btn btn-success btn-sm col-xs-12 disabled" id="add-row"><em
                class="fa fa-plus"></em> Baris</button>
    </div>
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
                <em class="total-belanja-produk">0</em>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5 col-sm-5 col-xs-5">
                <span class="pull-right">
                    <h4><strong>Diskon</strong><small id="with-nom-p"></small></h4>
                    <input form="formKasir" readonly type="hidden" value="0" class="form-control input-sm g-t-bel">
                </span>
            </div>
            <div class="col-md-1 col-sm-1 col-xs-1">
                <div class="input-group">
                    <span class="input-group-addon text-white">
                        <em class="fa fa-cut"></em>
                    </span>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 input-group-sm">
                <select form="formKasir" id="diskon" class="form-control input-sm" style="width:100%;"></select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-5 col-sm-5 col-xs-5">
                <span class="pull-right">
                    <h4><strong>Voucher</strong></h4>
                </span>
            </div>
            <div class="col-md-1 col-sm-1 col-xs-1">
                <div class="input-group">
                    <span class="input-group-addon text-white">
                        <em class="fa fa-gift"></em>
                    </span>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 input-group-sm">
                <select name="voucher" id="voucher" class="form-control input-sm" style="width:100%;"></select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5 col-sm-5 col-xs-5">
                <span class="pull-right">
                    <h4><strong>Total Tagihan</strong></h4>
                </span>
            </div>
            <div class="col-md-1 col-sm-1 col-xs-1">
                <div class="input-group">
                    <span class="input-group-addon bg-aqua text-white">Rp</span>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6">
                <em class="total-belanja f-s-20">0</em>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5 col-sm-5 col-xs-5 col-offset-1">
                <span class="pull-right">
                    <h4><strong><em class="fa fa-money text-success"></em> Pembayaran</strong></h4>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5 col-sm-5 col-xs-5">
                <span class="pull-right">
                    <h4><strong>Pilih Pembayaran</strong></h4>
                </span>
            </div>

            <div class="col-md-7 col-sm-7 col-xs-7 input-group-sm">
                <select form="formKasir" name="cara_bayar" id="cara_bayar" class="form-control input-sm"
                    style="width:100%;"></select>
            </div>
        </div>
        <div class="row" id="content-methode-bayar"></div>
        <div class="row" id="content-bayar"></div>
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
            <div class="col-md-6 col-sm-6 col-xs-6 input-group-sm">
                <input form="formKasir" disabled readonly type="text" name="bayar" value="0"
                    class="form-control input-sm">
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
                <em class="kembalian-belanja">0</em>
                <input form="formKasir" readonly type="hidden" name="kembalian" value="0" class="form-control input-sm">
            </div>
        </div>
        {{-- <div class="row">
            <div class="col-md-5 col-sm-5 col-xs-5">
                <span class="pull-right">
                    <h4><strong>Hutang</strong></h4>
                </span>
            </div>
            <div class="col-md-1 col-sm-1 col-xs-1">
                <div class="input-group">
                    <span class="input-group-addon bg-aqua text-white">Rp</span>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6">
                <em class="hutang-belanja">0</em>
                <input form="formKasir" readonly type="text" name="hutang_belanja" value="0" class="form-control input-sm">
            </div>
        </div> --}}
    </div>
</div>
