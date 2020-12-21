<div class="box box-info">
    <div class="box-body">
        <ul class="nav nav-tabs">
            <li class="active pull-right">
                <a href="#iLayanan" data-toggle="tab" aria-expanded="false">Layanan</a>
            </li>
            <li class="pull-right">
                <a href="#iPaket" data-toggle="tab" aria-expanded="false">Paket</a>
            </li>
            <li class="pull-right">
                <a href="#iProduk" data-toggle="tab" aria-expanded="false">Produk</a>
            </li>
            <li class="pull-left">
                <div class="input-group box"
                    style="margin-top: -2.8px; border: 3px solid #d2d6de !important; margin-bottom:0px;">
                    <div class="col-xs-6">
                        <div class="row input-group input-group-sm rodok-lebar">
                            <div class="input-group-addon add-on-daterpicker bg-green">
                                <i class="fa fa-calendar-check-o"></i>
                            </div>
                            <input type="text" name="berlaku_dari" class="form-control" placeholder="Dari..."
                                readonly="">
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="row input-group input-group-sm date group-date-range">
                            <div class="input-group-addon bg-gray">
                                -
                            </div>
                            <input type="text" name="berlaku_sampai" class="form-control daterpicker"
                                placeholder="Sampai..." readonly="">
                            <div class="input-group-addon remove-on-daterpicker bg-gray">
                                <i class="fa fa-calendar-times-o"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>

        <div class="row mt-20">
            <div class="col-xs-12">
                <div class="row">
                    <div class="tab-content">
                        <div class="tab-pane active" id="iLayanan">
                            <div class="col-md-12">
                                <div class="box box-danger display-future">
                                    <div class="box-header with-border bg-success">
                                        <i class="fa fa-list-alt"></i>
                                        <h3 class="box-title">Informasi Pendapatan Layanan</h3>
                                        <div class="box-tools pull-right">
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                    class="fa fa-minus"></i></button>
                                        </div>
                                    </div>

                                    <div class="box-body">
                                        <div class="load-data-layanan"><em class='fa fa-spin fa-spinner'></em>
                                            Loading...
                                        </div>
                                        <div class="row text-left">
                                            <div class="col-xs-4">
                                                <div class="row">
                                                    <div class="col-xs-12 text-left">
                                                        <h5>TOTAL PENDAPATAN LAYANAN</h5>
                                                        <div class="input-group">
                                                            <span class="input-group-addon bg-green input-group-lg">
                                                                Rp
                                                            </span>
                                                            <div
                                                                class="form-control bg-white no-border total-pendapatan-layanan">
                                                                <em class="fa fa-spin fa-spinner"></em>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-4">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <h5>TOTAL PELAYANAN</h5>
                                                        <div class="input-group">
                                                            <span class="input-group-addon bg-green input-group-lg">
                                                                <em class="fa fa-inbox"></em>
                                                            </span>
                                                            <div class="form-control bg-white no-border total-layanan">
                                                                <em class="fa fa-spin fa-spinner"></em>
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
                        <div class="tab-pane" id="iPaket">
                            <div class="col-md-12">
                                <div class="box box-danger display-future">
                                    <div class="box-header with-border bg-success">
                                        <i class="fa fa-list-alt"></i>
                                        <h3 class="box-title">Informasi Pendapatan Paket</h3>
                                        <div class="box-tools pull-right">
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                    class="fa fa-minus"></i></button>
                                        </div>
                                    </div>

                                    <div class="box-body">
                                        <div class="load-data-paket"><em class='fa fa-spin fa-spinner'></em>
                                            Loading...
                                        </div>
                                        <div class="row text-left">
                                            <div class="col-xs-4">
                                                <div class="row">
                                                    <div class="col-xs-12 text-left">
                                                        <h5>TOTAL PENDAPATAN PAKET</h5>
                                                        <div class="input-group">
                                                            <span class="input-group-addon bg-green input-group-lg">
                                                                Rp
                                                            </span>
                                                            <div
                                                                class="form-control bg-white no-border total-pendapatan-paket">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-4">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <h5>TOTAL PELAYANAN PAKET</h5>
                                                        <div class="input-group">
                                                            <span class="input-group-addon bg-green input-group-lg">
                                                                <em class="fa fa-inbox"></em>
                                                            </span>
                                                            <div class="form-control bg-white no-border total-paket">
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
                        <div class="tab-pane" id="iProduk">
                            <div class="col-md-12">
                                <div class="box box-danger display-future">
                                    <div class="box-header with-border bg-info">
                                        <i class="fa fa-list-alt"></i>
                                        <h3 class="box-title">Informasi Penjualan Produk</h3>
                                        <div class="box-tools pull-right">
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                    class="fa fa-minus"></i></button>
                                        </div>
                                    </div>

                                    <div class="box-body">
                                        <div class="load-data-produk"><em class='fa fa-spin fa-spinner'></em> Loading...
                                        </div>
                                        <div class="row text-left">
                                            <div class="col-xs-4">
                                                <div class="row">
                                                    <div class="col-xs-12 text-left">
                                                        <h5>TOTAL PENDAPATAN PRODUK</h5>
                                                        <div class="input-group">
                                                            <span class="input-group-addon bg-green input-group-lg">
                                                                Rp
                                                            </span>
                                                            <div
                                                                class="form-control bg-white no-border total-pendapatan-produk">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-4">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <h5>TOTAL PRODUK</h5>
                                                        <div class="input-group">
                                                            <span class="input-group-addon bg-green input-group-lg">
                                                                <em class="fa fa-inbox"></em>
                                                            </span>
                                                            <div class="form-control bg-white no-border total-produk">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
