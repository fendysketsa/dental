<div class="row">
    <div class="col-md-12">
        <div class="box box-danger display-future">
            <div class="box-header with-border bg-info">
                <i class="fa fa-calendar"></i>
                <h3 class="box-title">Tahun Kunjungan</h3>
                <select id="fil-y"></select>
            </div>

            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#chart" data-toggle="tab" aria-expanded="false">Chart</a></li>
                        <li>
                            <a href="#data-pengunjung" data-toggle="tab" aria-expanded="false">List Pengunjung</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="chart">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box-body">
                                        <div class="load-data-chart">
                                            <em class='fa fa-spin fa-spinner'></em> Loading...
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="data-pengunjung">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box box-info display-future">
                                        <div class="box-header with-border bg-warning">
                                            <i class="fa fa-list-alt"></i>
                                            <h3 class="box-title">10 Pengunjung Teratas : </h3>
                                            <select id="fil-month"></select>
                                            <div class="box-tools">
                                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                        class="fa fa-minus"></i></button>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            <div class="load-data-pengunjung">
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
    </div>
</div>
