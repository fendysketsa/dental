<div class="row">
    <div class="col-md-12">
        <div class="box box-danger display-future">
            <div class="box-header with-border bg-info">
                <i class="fa fa-list-alt pull-left"></i>
                <h3 class="box-title pull-left">Data Terapis</h3>
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

                <div class="pull-left" style="margin-left:10px;">
                    <a href="javascript:void(0)" class="btn-sm btn btn-success text-warning export-komisi"><em
                            class="pull-left fa fa-file-excel-o" style="margin-top:1px;"></em>
                        Export
                        Excel!</a>
                </div>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                            class="fa fa-minus"></i></button>
                </div>
            </div>

            <div class="box-body">
                <div class="load-data">
                    <em class='fa fa-spin fa-spinner'></em> Loading...
                </div>
            </div>
        </div>
    </div>
</div>
