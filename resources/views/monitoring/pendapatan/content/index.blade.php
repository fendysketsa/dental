<div class="row">
    <div class="col-md-12">
        <div class="box box-danger display-future">
            <div class="box-header with-border bg-info">
                <i class="fa fa-search pull-left tinggi-filter-range-date"></i>
                <h3 class="box-title pull-left tinggi-filter-range-date">Pendapatan</h3>
                <div class="row pull-left" style="margin-left:10px;">
                    <div class="col-xs-12">
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
                                <div class="row input-group input-group-sm date group-date-range rodok-lebar">
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
                    </div>
                </div>

                <div class="box-tools">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                            class="fa fa-minus"></i></button>
                </div>
            </div>

            <div class="box-body get-total-pengeluaran">
                <div class="row">
                    <div class="col-lg-3 col-xs-3">
                        <div class="small-box bg-blue">
                            <div class="inner">
                                <h3><sup style="font-size:20px;">Rp</sup>
                                    <em class="text-bold t-pendapatan">
                                        <em class="fa fa-spin fa-spinner"></em>
                                    </em>
                                </h3>
                                <p>Pendapatan</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-cash"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xs-3">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3><sup style="font-size:20px;">Rp</sup>
                                    <em class="text-bold t-pemasukan">
                                        <em class="fa fa-spin fa-spinner"></em>
                                    </em>
                                </h3>
                                <p>Pemasukan</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xs-3">
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h3><sup style="font-size:20px;">Rp</sup>
                                    <em class="text-bold t-pengeluaran">
                                        <em class="fa fa-spin fa-spinner"></em>
                                    </em>
                                </h3>
                                <p>( Pengeluaran + Pembelian )</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-scissors"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xs-3">
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h3><sup style="font-size:20px;">Rp</sup>
                                    <em class="text-bold t-modale">
                                        <em class="fa fa-spin fa-spinner"></em>
                                    </em>
                                </h3>
                                <p>Modal</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-ios-book"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box box-disabled display-future">
                    <div class="box-header with-border bg-default">
                        <i class="fa fa-list-alt"></i>
                        <h3 class="box-title">Data Pendapatan</h3>
                    </div>
                    <div class="box-body">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#pemasukan" data-toggle="tab" aria-expanded="false">Pemasukan</a></li>
                                <li>
                                    <a href="#pengeluaran" data-toggle="tab" aria-expanded="false">Pengeluaran +
                                        Pembelian</a></li>
                                <li>
                                    <a href="#modal" data-toggle="tab" aria-expanded="false">Modal</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="pemasukan">
                                    <div class="load-data-pemasukan">
                                        <em class='fa fa-spin fa-spinner'></em> Loading...
                                    </div>
                                </div>
                                <div class="tab-pane" id="pengeluaran">
                                    <div class="load-data-pengeluaran">
                                        <em class='fa fa-spin fa-spinner'></em> Loading...
                                    </div>
                                </div>
                                <div class="tab-pane" id="modal">
                                    <div class="load-data-modal">
                                        <em class='fa fa-spin fa-spinner'></em> Loading...
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
